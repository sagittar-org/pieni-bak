<?php
class Crud extends Controller {

	public function __construct()
	{
		parent::__construct();
		$this->model = load_model(uri('class'), array_merge(uri(), [
			'session' => isset($_SESSION[uri('actor')]['index'][uri('alias')]) ? $_SESSION[uri('actor')]['index'][uri('alias')] : [],
			'post' => $_POST,
			'get' => $_GET,
			'auth' => $_SESSION[uri('actor')]['auth'],
		]));

		if (in_array(uri('class'), config('uri')['table_list']))
		{
			if ( ! in_array(uri('method'), array_keys($this->model->action_hash)))
			{
				show_404();
			}
		}
	}

	// 代理ログイン
	public function proxy()
	{
		$db = load_library('db');
		$key = array_flip(config('uri')['actor_hash'])[uri('class')];
		$row = $db->query(str_replace('$1', uri('id'), config('auth')[$key]['proxy']))->fetch_assoc();
		if ($row === NULL)
		{
			flash(l('crud_proxy_failed', [], TRUE), 'danger');
			redirect($key, TRUE, FALSE);
		}
		$_SESSION[$key] = ['auth' => $row];
		flash(l('crud_proxy_succeeded', [], TRUE), 'success');
		redirect($key, TRUE, FALSE);
	}

	// 一覧
	public function index()
	{
		$this->model->index();
		$_SESSION[uri('actor')]['index'][uri('alias')] = $this->model->session;
		if ($_POST !== [])
		{
			redirect(uri('uri_string'), FALSE, FALSE);
		}
		load_template('index', ['model' => $this->model]);
	}

	// 詳細
	public function view()
	{
		$this->model->view(uri('id'));
		if ($this->model->row === NULL)
		{
			show_404();
		}
		foreach ($this->model->has_hash as $key => $has)
		{
			load_model($has, [
				'actor'     => uri('actor'),
				'class'     => $has,
				'alias'     => $key,
				'method'    => 'index',
				'parent_id' => uri('id'),
				'session'   => isset($_SESSION[uri('actor')]['index'][$key]) ? $_SESSION[uri('actor')]['index'][$key] : [],
				'post'      => $_POST,
				'get'       => $_GET,
				'auth'      => $_SESSION[uri('actor')]['auth'],
			]);
			if ( ! in_array('index', array_keys(model($key)->action_hash)))
			{
				continue;
			}
			model($key)->index();
			$_SESSION[uri('actor')]['index'][$key] = model($key)->session;
			$this->model->has_hash[$key] = [
				'parent_row' => $this->model->row,
				'model' => model($key),
			];
		}
		if ($_POST !== [])
		{
			redirect(uri('uri_string'), FALSE, FALSE);
		}
		is_ajax() ? exit(json_encode($this->model->row)) : load_template('view', ['model' => $this->model]);
	}

	// 追加
	public function add()
	{
		$this->model->add($_POST);
		if ($this->model->result === TRUE)
		{
			flash(l('crud_add_succeeded', [], TRUE), 'success');
		}
		else
		{
			flash(l('crud_add_failed', [], TRUE), 'warning');
		}
		$this->post_add();
	}

	// 修正
	public function edit()
	{
		if ($_POST === [])
		{
			$this->model->view(uri('id'));
			if ($this->model->row === NULL)
			{
				show_404();
			}
			exit(json_encode($this->model->row));
		}
		$this->model->edit(uri('id'), $_POST);
		if ($this->model->result === TRUE)
		{
			flash(l('crud_edit_succeeded', [], TRUE), 'success');
		}
		else
		{
			flash(l('crud_edit_failed', [], TRUE), 'warning');
		}
		$this->post_edit();
	}

	// 削除
	public function delete()
	{
		if ($_POST === [])
		{
			$this->model->view(uri('id'));
			if ($this->model->row === NULL)
			{
				show_404();
			}
			exit(json_encode($this->model->row));
		}
		$this->model->delete(uri('id'));
		if ($this->model->result === TRUE)
		{
			flash(l('crud_delete_succeeded', [], TRUE), 'success');
		}
		else
		{
			flash(l('crud_delete_failed', [], TRUE), 'warning');
		}
		$this->post_delete();
	}

	// ダウンロード
	public function download()
	{
		$this->model->view(uri('id'));
		if ($this->model->row === NULL)
		{
			show_404();
		}
		$name = $this->model->row["{$this->model->table}_name"];
		list($type, $data) = explode(';base64,', $this->model->row["{$this->model->table}_file"]);
		$type = str_replace('data:', '', $type);
		$data = base64_decode($data);
		$size = strlen($data);
		ob_get_clean();
		header('Content-Type: '.$type);
		header('Content-Length: '.$size);
		header('Content-disposition: attachment; filename="'.$name.'"');
		echo $data;
	}

	public function post_add() {}
	public function post_edit() {}
	public function post_delete() {}
}
