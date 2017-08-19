<?php
class Spec extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	// トップページ
	public function index()
	{
		load_template(__FUNCTION__);
	}

	// エンティティ表示
	public function table($actor, $class)
	{
		load_template(__FUNCTION__, ['actor' => $actor, 'class' => $class]);
	}

	// DBからモデルを生成
	public function compile()
	{
		load_library('db');
		$table_list = "'".implode("', '", config('uri')['table_list'])."'";
		$actor_list = "'".implode("', '", array_reverse(array_keys(config('uri')['actor_hash'])))."'";
		$action_list = "'".implode("', '", config('uri')['action_list'])."'";
		$alias_list = "'".implode("', '", array_merge(config('uri')['table_list'], config('uri')['alias_list']))."'";
		$result = library('db')->query("SELECT * FROM `spec` ORDER BY
`spec_table` IS NULL DESC, FIELD(`spec_table`, {$table_list}),
`spec_alias` IS NULL DESC, FIELD(`spec_alias`, {$alias_list}),
`spec_action` IS NULL DESC, FIELD(`spec_action`, {$actor_list}),
`spec_actor` IS NULL DESC, FIELD(`spec_actor`, {$actor_list}),
`spec_method` IS NULL DESC, FIELD(`spec_method`, 'overwrite', 'append', 'remove'),
`spec_key1` IS NULL DESC, FIELD(`spec_key1`, 'primary_key', 'display', 'use_card', 'has_hash', 'action_list', 'row_action_hash', 'select_hash', 'hidden_list', 'set_list', 'fixed_hash', 'success_hash', 'join_hash', 'where_list', 'where_hash', 'order_by_hash', 'limit_list'),
`spec_id` ASC
");
r(library('db')->last_query);
		while (($row = $result->fetch_assoc()))
		{
			// エイリアス終了
			if ((isset($last_row) && $row['spec_alias'] !== $last_row['spec_alias']) && $last_row['spec_alias'] !== NULL)
			{
				$indent = substr($indent, 0, strlen($indent) - 1);
				echo "{$indent}}\n";
			}

			// アクター終了
			if ((isset($last_row) && $row['spec_action'] !== $last_row['spec_action']) && $last_row['spec_action'] !== NULL)
			{
				$indent = substr($indent, 0, strlen($indent) - 1);
				echo "{$indent}}\n";
			}

			// アクター終了
			if ((isset($last_row) && $row['spec_actor'] !== $last_row['spec_actor']) && $last_row['spec_actor'] !== NULL)
			{
				$indent = substr($indent, 0, strlen($indent) - 1);
				echo "{$indent}}\n";
			}

			// テーブル終了
			if (isset($last_row) && $row['spec_table'] !== $last_row['spec_table'])
			{
				echo "\t}\n}\n";
			}

			// テーブル開始
			if ( ! isset($last_row) OR $row['spec_table'] !== $last_row['spec_table'])
			{
				$indent = "\t\t";
				echo "<?php\nclass ".ucfirst($row['spec_table'])."_model extends Crud_model {\n\n\tpublic function __construct(\$params)\n\t{\n\t\tparent::__construct(\$params);\n\n";
			}

			// アクター開始
			if (( ! isset($last_row) OR $row['spec_actor'] !== $last_row['spec_actor']) && $row['spec_actor'] !== NULL)
			{
				echo "\n{$indent}if (\$this->actor === '{$row['spec_actor']}')\n{$indent}{\n";
				$indent .= "\t";
			}

			// アクション開始
			if (( ! isset($last_row) OR $row['spec_action'] !== $last_row['spec_action']) && $row['spec_action'] !== NULL)
			{
				echo "\n{$indent}if (\$this->actor === '{$row['spec_action']}')\n{$indent}{\n";
				$indent .= "\t";
			}

			// エイリアス開始
			if (( ! isset($last_row) OR $row['spec_alias'] !== $last_row['spec_alias']) && $row['spec_alias'] !== NULL)
			{
				echo "\n{$indent}if (\$this->alias === '{$row['spec_alias']}')\n{$indent}{\n";
				$indent .= "\t";
			}

			switch ($row['spec_method'])
			{
			case 'overwrite':
				switch ($row['spec_key1'])
				{
				case 'primary_key':
				case 'display':
				case 'use_card':
					$value = $row['spec_value'];
					break;
				default:
					show_500("Illigal key for method '{$row['spec_method']}' ('{$row['spec_key1']}')");
					break;
				}
				echo "{$indent}\$this->{$row['spec_method']}('{$row['spec_key1']}', {$value});\n";
				break;
			case 'append':
				switch ($row['spec_key1'])
				{
				case 'action_list':
				case 'hidden_list':
				case 'set_list':
				case 'where_list':
				case 'limit_list':
					$value = $row['spec_value'];
					break;
				case 'has_hash':
				case 'row_action_hash':
				case 'select_hash':
				case 'fixed_hash':
				case 'success_hash':
				case 'join_hash':
				case 'where_hash':
				case 'order_by_hash':
					$value = "'{$row['spec_key2']}', {$row['spec_value']}";
					break;
				default:
					show_500("Illigal key for method '{$row['spec_method']}' ('{$row['spec_key1']}')");
					break;
				}
				echo "{$indent}\$this->{$row['spec_method']}('{$row['spec_key1']}', {$value});\n";
				break;
			case 'remove':
				switch ($row['spec_key1'])
				{
				case 'action_list':
				case 'hidden_list':
				case 'set_list':
				case 'where_list':
				case 'limit_list':
					$value = $row['spec_value'];
					break;
				case 'has_hash':
				case 'row_action_hash':
				case 'select_hash':
				case 'fixed_hash':
				case 'success_hash':
				case 'join_hash':
				case 'where_hash':
				case 'order_by_hash':
					$value = $row['spec_value'];
					break;
				default:
					show_500("Illigal key for method '{$row['spec_method']}' ('{$row['spec_key1']}')");
					break;
				}
				echo "{$indent}\$this->{$row['spec_method']}('{$row['spec_key1']}', {$value});\n";
				break;
			default:
				show_500("Unknown Method ('{$row['spec_method']}')");
				break;
			}
			$last_row = $row;
		}

		// エイリアス終了
		if ($last_row['spec_alias'] !== NULL)
		{
			$indent = substr($indent, 0, strlen($indent) - 1);
			echo "{$indent}}\n";
		}

		// アクター終了
		if ($last_row['spec_action'] !== NULL)
		{
			$indent = substr($indent, 0, strlen($indent) - 1);
			echo "{$indent}}\n";
		}

		// アクター終了
		if ($last_row['spec_actor'] !== NULL)
		{
			$indent = substr($indent, 0, strlen($indent) - 1);
			echo "{$indent}}\n";
		}

		// テーブル終了
		echo "\t}\n}\n";
	}
}
