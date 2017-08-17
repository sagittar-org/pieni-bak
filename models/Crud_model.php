<?php
class Crud_model {

	private $db;
	private $post;
	private $get;

	public function __construct($params)
	{
		$this->db              = load_library('db');
		$this->post            = isset($params['post']) ? $params['post'] : [];
		$this->get             = isset($params['get']) ? $params['get'] : [];
		$this->auth            = isset($params['auth']) ? $params['auth'] : [];
		$this->actor           = isset($params['actor']) ? $params['actor'] : uri('actor');
		$this->table           = $params['class'];
		$this->alias           = $params['alias'];
		$this->action          = isset($params['method']) ? $params['method'] : 'index';
		$this->parent_id       = isset($params['parent_id']) ? $params['parent_id'] : NULL;
		$this->session         = isset($params['session']) ? $params['session'] : [];
		$this->primary_key     = "{$this->table}_id";
		$this->has_hash        = [];
		$this->action_list     = ['index', 'view', 'add', 'edit', 'delete'];
		$this->row_action_hash = [];
		$this->select_hash     = ["{$this->table}_id" => NULL, "{$this->table}_name" => NULL];
		$this->join_hash       = [];
		$this->set_list        = ["{$this->table}_name"];
		$this->fixed_hash      = [];
		$this->where_list      = [];
		$this->where_hash      = [];
		$this->order_by_hash   = ["{$this->primary_key}_desc" => "`{$this->primary_key}` DESC"];
		$this->limit_list      = [10];
		$this->display         = "{$this->table}_name";
		$this->hidden_list     = [$this->primary_key];
		$this->use_card        = FALSE;
		$this->success_hash    = [];
	}

	// 行数と結果を取得
	public function index()
	{
		// セッションを更新
		$this->_index_session();

		// 行数を取得
		$this->db->select('num_rows', 'COUNT(*)');
		$this->db->from($this->table);
		foreach ($this->join_hash as $key => $join)
		{
			$this->db->join($key, $join['table'], $join['cond']);
		}
		foreach ($this->where_list as $where)
		{
			$this->db->where($where);
		}
		$this->num_rows = $this->db->get()->fetch_assoc()['num_rows'];

		// 結果を取得
		foreach ($this->select_hash as $key => $select)
		{
			$this->db->select($key, $select);
		}
		foreach ($this->join_hash as $key => $join)
		{
			$this->db->join($key, $join['table'], $join['cond']);
		}
		foreach ($this->where_list as $where)
		{
			$this->db->where($where);
		}
		$this->db->from($this->table);
		$this->db->order_by($this->order_by_hash[$this->order_by]);
		$this->db->limit($this->limit);
		$this->db->offset($this->offset);
		$this->result = $this->db->get();
		return $this;
	}

	// 行を取得
	public function view($id)
	{
		foreach ($this->select_hash as $key => $select)
		{
			$this->db->select($key, $select);
		}
		foreach ($this->join_hash as $key => $join)
		{
			$this->db->join($key, $join['table'], $join['cond']);
		}
		foreach ($this->where_list as $where)
		{
			$this->db->where($where);
		}
		$this->db->from($this->table);
		$this->db->where("`{$this->primary_key}` = {$id}");
		$this->row = $this->db->get()->fetch_assoc();
		return $this;
	}

	// 行を追加
	public function add($data)
	{
		$this->db->from($this->table);
		foreach ($this->set_list as $set)
		{
			if (preg_match('/_password$/', $set))
			{
				if ($data[$set] === '')
				{
					continue;
				}
				if (config('password')['hash'] === TRUE)
				{
					$data[$set] = password_hash($data[$set], PASSWORD_DEFAULT);
				}
			}
			$this->db->set($set, "'".$this->db->mysqli->real_escape_string($data[$set])."'");
		}
		foreach ($this->fixed_hash as $key => $fixed)
		{
			$this->db->set($key, $fixed);
		}
		$this->result = $this->db->insert();
		$this->insert_id = $this->db->insert_id;
		return $this;
	}

	// 行を編集
	public function edit($id, $data)
	{
		$this->db->from($this->table);
		foreach ($this->join_hash as $key => $join)
		{
			$this->db->join($key, $join['table'], $join['cond']);
		}
		foreach ($this->set_list as $set)
		{
			if (preg_match('/_password$/', $set))
			{
				if ($data[$set] === '')
				{
					continue;
				}
				if (config('password')['hash'] === TRUE)
				{
					$data[$set] = password_hash($data[$set], PASSWORD_DEFAULT);
				}
			}
			$this->db->set($set, "'".$this->db->mysqli->real_escape_string($data[$set])."'");
		}
		$this->db->where("`{$this->primary_key}` = {$id}");
		foreach ($this->where_list as $where)
		{
			$this->db->where($where);
		}
		$this->result = $this->db->update();
		return $this;
	}

	// 行を削除
	public function delete($id)
	{
		$this->db->from($this->table);
		foreach ($this->join_hash as $key => $join)
		{
			$this->db->join($key, $join['table'], $join['cond']);
		}
		$this->db->where("`{$this->primary_key}` = {$id}");
		foreach ($this->where_list as $where)
		{
			$this->db->where($where);
		}
		$this->result = $this->db->delete();
		return $this;
	}

	// セッションを更新
	protected function _index_session()
	{
		if ($this->session === [] OR isset($this->post["{$this->alias}_clear"]))
		{
			$return = TRUE;
			$this->session = [];
		}
		if ( ! isset($this->session['where_hash']))
		{
			$this->session['where_hash'] = [];
			foreach ($this->where_hash as $key => $where)
			{
				$this->session['where_hash'][$key] = '';
			}
			$this->where_hash = $this->session['where_hash'];
		}
		if ( ! isset($this->session['order_by']))
		{
			$this->order_by = $this->session['order_by'] = array_keys($this->order_by_hash)[0];
		}
		if ( ! isset($this->session['limit']))
		{
			$this->limit = $this->session['limit'] = $this->limit_list[0];
		}
		if ( ! isset($this->session['offset']))
		{
			$this->offset = $this->session['offset'] = 0;
		}
		if (isset($return) && $return === TRUE)
		{
			return;
		}
		if (isset($this->get["{$this->alias}_offset"]) && intval($this->get["{$this->alias}_offset"]) !== intval($this->session['offset']))
		{
			$this->session['offset'] = max(0, intval($this->get["{$this->alias}_offset"]));
		}
		foreach ($this->where_hash as $key => $where)
		{
			if (isset($this->post["{$this->alias}_where_hash_{$key}"]))
			{
				$this->session['where_hash'][$key] = $this->post["{$this->alias}_where_hash_{$key}"];
			}
		}
		if (isset($this->post["{$this->alias}_order_by"]))
		{
			$this->session['order_by'] = $this->post["{$this->alias}_order_by"];
		}
		if (isset($this->post["{$this->alias}_limit"]))
		{
			$this->session['limit'] = intval($this->post["{$this->alias}_limit"]);
		}
		if ($this->post !== [])
		{
			$this->session['offset'] = 0;
		}
		foreach ($this->where_hash as $key => $where)
		{
			if (isset($this->session['where_hash'][$key]) && $this->session['where_hash'][$key] !== '')
			{
				$this->where_list[] = str_replace('$1', $this->db->mysqli->real_escape_string($this->session['where_hash'][$key]), $where);
			}
		}
		$this->order_by = $this->session['order_by'];
		$this->limit = intval($this->session['limit']);
		$this->offset = intval($this->session['offset']);
	}

	// デバッグ情報を表示
	public function error()
	{
		return $this->db->mysqli->error;
	}

	// 行を返す
	public function row()
	{
		return $this->result->fetch_assoc();
	}

	// メンバの添字・連想配列から要素を削除する
	public function remove($array, $element)
	{
		if (array_values($this->$array) === $this->$array)
		{
			$this->$array = array_merge(array_diff($this->$array, [$element]));
		}
		else
		{
			unset($this->$array[$element]);
		}
	}

	// メンバの添字・添字配列へ要素を追加する
	public function append($array, $element, $value = NULL)
	{
		if ($value === NULL)
		{
			$this->$array[] = $element;
		}
		else
		{
			$this->$array[$element] = $value;
		}
	}

	// メンバの値を上書きする
	public function overwrite($key, $value)
	{
		$this->$key = $value;
	}
}
