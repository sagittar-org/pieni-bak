<?php
class Db_library {

	public function __construct()
	{
		$this->debug = config('db')['debug'];
		$this->mysqli = new mysqli(
			config('db')['host'],
			config('db')['username'],
			config('db')['passwd'],
			config('db')['dbname']
		);
		$this->reset_query();
	}

	// 組み立てをクリア
	public function reset_query()
	{
		unset($this->select_list);
		unset($this->from);
		$this->join_list = [];
		$this->where_list = [];
		unset($this->order_by);
		unset($this->limit);
		unset($this->offset);
		$this->set_hash = [];
		$this->set_list = [];
		return $this;
	}

	// クエリを実行
	public function query($query)
	{
		$this->reset_query();
		$this->last_query = $query;
		if ($this->debug === TRUE)
		{
			flash("<pre>{$query}</pre>");
		}
		$result = $this->mysqli->query($query);
		$this->insert_id = $this->mysqli->insert_id;
		if ($result === FALSE)
		{
			log_message("{$this->last_query}\n{$this->mysqli->error}");
			if (is_ajax())
			{
				header('HTTP', TRUE, 500);
				if ($this->debug === TRUE)
				{
					echo $this->last_query;
					echo $this->mysqli->error;
				}
				else
				{
					echo 'Database Error';
				}
				exit;
			}
			if ($this->debug === TRUE)
			{
				flash(nl2br($this->mysqli->error), 'danger');
			}
			load_template('db', [], 'errors');
			exit;
		}
		return $result;
	}

	// SELECT句キャッシュを組み立て
	public function select($key, $select = NULL)
	{
		if ($select === NULL)
		{
			$this->select_list[] = "`{$key}`";
		}
		else
		{
			$this->select_list[] = "{$select} AS `{$key}`";
		}
		return $this;
	}

	// FROM句キャッシュを組み立て
	public function from($from)
	{
		$this->from = $from;
		return $this;
	}

	// JOIN句キャッシュを組み立て
	public function join($key, $table, $cond)
	{
		$this->join_list[] = "{$table} AS `{$key}` ON {$cond}";
		return $this;
	}

	// WHERE句キャッシュを組み立て
	public function where($where)
	{
		$this->where_list[] = $where;
		return $this;
	}

	// ORDER BY句キャッシュを組み立て
	public function order_by($order_by)
	{
		$this->order_by = $order_by;
		return $this;
	}

	// LIMIT句キャッシュを組み立て
	public function limit($limit)
	{
		$this->limit = $limit;
		return $this;
	}

	// OFFSET句キャッシュを組み立て
	public function offset($offset)
	{
		$this->offset = $offset;
		return $this;
	}

	// 更新系キャッシュを組み立て
	public function set($key, $set)
	{
		$this->set_hash[$key] = $set;
		$this->set_list[] = "`{$key}` = {$set}";
		return $this;
	}

	// SELECT文を実行
	public function get()
	{
		$query  = "SELECT\n\t".implode(",\n\t", $this->select_list)."\n";
		$query .= "FROM `{$this->from}`\n";
		foreach ($this->join_list as $join)
		{
			$query .= "LEFT JOIN {$join}\n";
		}
		$query .= "WHERE 1\n";
		foreach ($this->where_list as $where)
		{
			$query .= "AND {$where}\n";
		}
		if (isset($this->order_by))
		{
			$query .= "ORDER BY {$this->order_by}\n";
		}
		if (isset($this->limit))
		{
			$query .= "LIMIT {$this->limit}\n";
		}
		if (isset($this->offset))
		{
			$query .= "OFFSET {$this->offset}\n";
		}
		return $this->query($query);
	}

	// INSERT文を実行
	public function insert()
	{
		$query  = "INSERT INTO `{$this->from}` (\n";
		$query .= "\t`".implode("`,\n\t`", array_keys($this->set_hash))."`\n) VALUES (\n";
		$query .= "\t".implode(",\n\t", $this->set_hash)."\n)\n";
		return $this->query($query);
	}

	// UPDATE文を実行
	public function update()
	{
		$query  = "UPDATE `{$this->from}`\n";
		foreach ($this->join_list as $join)
		{
			$query .= "LEFT JOIN {$join}\n";
		}
		$query .= "SET\n";
		$query .= "\t".implode(",\n\t", $this->set_list)."\n";
		$query .= "WHERE 1\n";
		foreach ($this->where_list as $where)
		{
			$query .= "AND {$where}\n";
		}
		return $this->query($query);
	}

	// DELETE文を実行
	public function delete()
	{
		$query  = "DELETE FROM `{$this->from}`\n";
		$query .= "WHERE 1\n";
		foreach ($this->where_list as $where)
		{
			$query .= "AND {$where}\n";
		}
		return $this->query($query);
	}
}
