<?php
class Directive extends Crud {

	public function __construct()
	{
		parent::__construct();
	}

	private function load_schema() {
		$book = PHPExcel_IOFactory::load(config('package_list')[0].'/models/schema.xlsx');

		for ($i = 0; $i < $book->getSheetCount(); $i++) {
			$book->setActiveSheetIndex($i);
			$sheet = $book->getActiveSheet();
			$hash = [];
			for ($r = 2; $r < 1000; $r++) {
				if ($sheet->getCell('A'.$r)->getValue() === null) {
					break;
				}
				$row = [
					'name' => $sheet->getCell('A'.$r)->getValue(),
					'type' => $sheet->getCell('B'.$r)->getValue(),
					'null' => $sheet->getCell('C'.$r)->getValue(),
					'extra' => $sheet->getCell('D'.$r)->getValue(),
					'ja' => $sheet->getCell('F'.$r)->getValue(),
				];
				$hash[] = $row;
			}

			$fields = [];
			foreach ($hash as $row) {
				$fields[] = "`{$row['name']}` {$row['type']} ".($row['null'] === null ? 'NOT NULL' : 'DEFAULT NULL')." {$row['extra']} comment '{$row['ja']}'";
			}
			$result = library('db')->query("DROP TABLE IF EXISTS `".$sheet->getTitle()."`");
			$result = library('db')->query("CREATE TABLE `".$sheet->getTitle()."` (\n\t".implode(",\n\t", $fields)."\n)");
		}
	}

	public function load_data()
	{
		$book = PHPExcel_IOFactory::load(config('package_list')[0].'/models/data.xlsx');

		$sheetsCount = $book->getSheetCount();
		for ($i = 0; $i < $sheetsCount; $i++) {
			$book->setActiveSheetIndex($i);
			$sheet = $book->getActiveSheet();
			$title = $sheet->getTitle();
			$hash_s = [];
			$row_max = $sheet->getHighestRow();
			library('db')->query("TRUNCATE `{$title}`");
			if ($row_max < 2) continue;

			for ($c = 0; $c < 1000; $c++) {
				if ($sheet->getCellByColumnAndRow($c, 1)->getValue() === null) {
					break;
				}
			}
			$col_max = $c - 1;

			for ($n = 2; $n <= $row_max; $n++) {
				if ($sheet->getCell('A'.$n)->getValue() === null) {
					break;
				}
				$row = [];
				for ($c = 0; $c <= $col_max; $c++) {
					$row[$sheet->getCellByColumnAndRow($c, 1)->getValue()] = $sheet->getCellByColumnAndRow($c, $n)->getValue();
				}
				$hash_s[] = $row;
			}
			$fields = [];
			$column = [];
			$column_s = [];
			foreach ($hash_s[0] as $key => $col) {
					$fields[] = $key;
			}
			foreach ($hash_s as $hash) {
				foreach ($hash as $key => $col) {
					$column[] = ($col === null ? 'NULL' : '"'.$col.'"');
				}
				$column_s[] = "(".implode(",", $column).")";
				$column = [];
			}
			$sql = "INSERT INTO `".$title."` (".implode(",", $fields).") VALUES ".implode(",", $column_s);
			library('db')->query($sql);
		}
	}

	// DBスキーマからモデルを生成
	public function generate()
	{
		$this->load_schema();
		$this->load_data();
		foreach (config('uri')['table_list'] as $table)
		{
			if ($table === 'directive')
			{
				continue;
			}
			library('db')->query("DELETE FROM `directive` WHERE `directive_table` = '{$table}' AND `directive_directive` IN ('select_hash', 'set_list', 'null_list')");
			$result = library('db')->query("SHOW COLUMNS FROM `{$table}`");
			while (($row = $result->fetch_assoc()))
			{
				r($row['Field']);
				library('db')->query("INSERT INTO `directive` (`directive_table`, `directive_actor`, `directive_action`, `directive_alias`, `directive_method`, `directive_directive`, `directive_key`, `directive_value`) VALUES ('{$table}', '', '', '', 'append', 'select_hash', '{$row['Field']}', 'NULL')");
				if ($row['Field'] === "{$table}_id")
				{
					continue;
				}
				library('db')->query("INSERT INTO `directive` (`directive_table`, `directive_actor`, `directive_action`, `directive_alias`, `directive_method`, `directive_directive`, `directive_key`, `directive_value`) VALUES ('{$table}', '', '', '', 'append', 'set_list', '', '\'{$row['Field']}\'')");
				if ($row['Null'] === 'YES')
				{
					library('db')->query("INSERT INTO `directive` (`directive_table`, `directive_actor`, `directive_action`, `directive_alias`, `directive_method`, `directive_directive`, `directive_key`, `directive_value`) VALUES ('{$table}', '', '', '', 'append', 'null_list', '', '\'{$row['Field']}\'')");
				}
			}
			$this->schema(FALSE, "`directive_table` = '{$table}' AND `directive_directive` IN ('select_hash', 'set_list', 'null_list')");
		}
		foreach (config('uri')['table_list'] as $table)
		{
			if ($table === 'directive')
			{
				continue;
			}
			if (file_exists('models/'.ucfirst($table).'_model.php'))
			{
				continue;
			}
			library('db')->query("DELETE FROM `directive` WHERE `directive_table` = '{$table}' AND `directive_directive` NOT IN ('select_hash', 'set_list', 'null_list')");
			$line_list = [
				['overwrite', 'primary_key', '', "'{$table}_id'"],
				['overwrite', 'display', '', "'{$table}_name'"],
				['overwrite', 'use_card', '', 'FALSE'],
				['append', 'action_hash', 'index', "'index'"],
				['append', 'action_hash', 'view', "'view'"],
				['append', 'action_hash', 'add', "'add'"],
				['append', 'action_hash', 'edit', "'edit'"],
				['append', 'action_hash', 'delete', "'delete'"],
				['append', 'hidden_list', '', "'{$table}_id'"],
				['append', 'where_hash', 'simple', "'CONCAT(`{$table}_name`) LIKE \"%\$1%\"'"],
				['append', 'order_by_hash', "{$table}_id_desc", "'`{$table}_id` DESC'"],
				['append', 'limit_list', '', '10'],
				['append', 'limit_list', '', '30'],
				['append', 'limit_list', '', '100'],
			];
			foreach ($line_list as $line)
			{
				library('db')->query("INSERT INTO `directive` (`directive_table`, `directive_actor`, `directive_action`, `directive_alias`, `directive_method`, `directive_directive`, `directive_key`, `directive_value`) VALUES ('{$table}', '', '', '', '".implode("', '", array_map([library('db')->mysqli, 'real_escape_string'], $line))."')");
			}
			library('db')->query("INSERT INTO `directive` (`directive_table`, `directive_actor`, `directive_action`, `directive_alias`, `directive_method`, `directive_directive`, `directive_key`, `directive_value`) VALUES ('{$table}', '".array_keys(config('uri')['actor_hash'])[0]."', '', '', 'remove', 'action_hash', '', 'add')");
			library('db')->query("INSERT INTO `directive` (`directive_table`, `directive_actor`, `directive_action`, `directive_alias`, `directive_method`, `directive_directive`, `directive_key`, `directive_value`) VALUES ('{$table}', '".array_keys(config('uri')['actor_hash'])[0]."', '', '', 'remove', 'action_hash', '', 'edit')");
			library('db')->query("INSERT INTO `directive` (`directive_table`, `directive_actor`, `directive_action`, `directive_alias`, `directive_method`, `directive_directive`, `directive_key`, `directive_value`) VALUES ('{$table}', '".array_keys(config('uri')['actor_hash'])[0]."', '', '', 'remove', 'action_hash', '', 'delete')");
			$this->decompile(FALSE, "`directive_table` = '{$table}' AND `directive_directive` NOT IN ('select_hash', 'set_list', 'null_list')");
		}
		flash(l('crud_generate_succeeded', [], TRUE), 'success');
		redirect('directive');
	}

	// モデルを正規化
	public function regularize()
	{
		$this->compile(FALSE);
		$this->decompile(FALSE);
		flash(l('crud_regularize_succeeded', [], TRUE), 'success');
		redirect('directive');
	}

	// モデルからDBを生成
	public function compile($flash = TRUE)
	{
		$stack = [];
		$table = 'admin';
		$actor = '';
		$action = '';
		$alias = '';
		library('db')->query('TRUNCATE `directive`');
		foreach (config('uri')['table_list'] as $table)
		{
			if ($table === 'directive')
			{
				continue;
			}
			foreach (explode("\n", trim(shell_exec('cat '.config('package_list')[0].'/models/'.ucfirst($table)."_model.php | grep \
-e '\$this->overwrite' \
-e '\$this->append' \
-e '\$this->remove' \
-e 'if (\$this->actor ===' \
-e 'if (\$this->action ===' \
-e 'if (\$this->alias ===' \
-e 'endif;'"), "\n")) as $line)
			{
				$row = NULL;
				if (preg_match('/^\s*\$this->overwrite\(\'([^\']+)\', (.+)\);/', $line, $matches))
				{
					$row = [$table, $actor, $action, $alias, 'overwrite', $matches[1], '', $matches[2]];
				}
				else if (preg_match('/^\s*\$this->append\(\'([^\']+_list)\', (.+)\);/', $line, $matches))
				{
					$row = [$table, $actor, $action, $alias, 'append', $matches[1], '', $matches[2]];
				}
				else if (preg_match('/^\s*\$this->append\(\'([^\']+_hash)\', \'([^\']+)\', (.+)\);/', $line, $matches))
				{
					$row = [$table, $actor, $action, $alias, 'append', $matches[1], $matches[2], $matches[3]];
				}
				elseif (preg_match('/^\s*\$this->remove\(\'([^\']+)\', \'([^\']+)\'\);/', $line, $matches))
				{
					$row = [$table, $actor, $action, $alias, 'remove', $matches[1], '', $matches[2]];
				}
				else if (preg_match('/^\s*if \(\$this->actor === \'([^\']+)\'/', $line, $matches))
				{
					$stack[] = 'actor';
					$actor = $matches[1];
				}
				else if (preg_match('/^\s*if \(\$this->action === \'([^\']+)\'/', $line, $matches))
				{
					$stack[] = 'action';
					$action = $matches[1];
				}
				else if (preg_match('/^\s*if \(\$this->alias === \'([^\']+)\'/', $line, $matches))
				{
					$stack[] = 'alias';
					$alias = $matches[1];
				}
				else if (preg_match('/^\s*endif;/', $line))
				{
					${array_pop($stack)} = '';
				}
				else
				{
					die('ERROR: '.$table.' '.$line);
				}
				if ($row !== NULL)
				{
					library('db')->query("INSERT INTO `directive` (`directive_table`, `directive_actor`, `directive_action`, `directive_alias`, `directive_method`, `directive_directive`, `directive_key`, `directive_value`) VALUES ('".implode("', '", array_map([library('db')->mysqli, 'real_escape_string'], $row))."')");
				}
			}
		}
		if ($flash === TRUE)
		{
			flash(l('crud_compile_succeeded', [], TRUE), 'success');
			redirect('directive');
		}
	}

	// DBからスキーマモデルを生成
	public function schema($flash = TRUE, $where = '1')
	{
		load_library('db');
		$result = library('db')->query("SELECT * FROM `directive` WHERE {$where} ORDER BY
FIELD(`directive_directive`, 'select_hash', 'set_list', 'null_list', 'primary_key', 'display', 'use_card', 'has_hash', 'action_hash', 'hidden_list', 'fixed_hash', 'success_hash', 'join_hash', 'where_list', 'where_hash', 'order_by_hash', 'limit_list'),
`directive_id` ASC
");
		while (($row = $result->fetch_assoc()))
		{
			// テーブル開始
			if ( ! isset($last_row) OR $row['directive_table'] !== $last_row['directive_table'])
			{
				$table = $row['directive_table'];
				ob_start();
				$indent = "\t\t";
				echo "<?php\nclass ".ucfirst($row['directive_table'])."_schema extends Crud_model {\n\n\tpublic function __construct(\$params)\n\t{\n\t\tparent::__construct(\$params);\n\n";
			}

			switch ($row['directive_method'])
			{
			case 'overwrite':
				switch ($row['directive_directive'])
				{
				case 'table':
				case 'primary_key':
				case 'display':
				case 'use_card':
					$value = $row['directive_value'];
					break;
				default:
					show_500("Illigal key for method '{$row['directive_method']}' ('{$row['directive_directive']}')");
					break;
				}
				echo "{$indent}\$this->{$row['directive_method']}('{$row['directive_directive']}', {$value});\n";
				break;
			case 'append':
				switch ($row['directive_directive'])
				{
				case 'hidden_list':
				case 'set_list':
				case 'null_list':
				case 'where_list':
				case 'limit_list':
					$value = $row['directive_value'];
					break;
				case 'has_hash':
				case 'action_hash':
				case 'select_hash':
				case 'fixed_hash':
				case 'success_hash':
				case 'join_hash':
				case 'where_hash':
				case 'order_by_hash':
					$value = "'{$row['directive_key']}', {$row['directive_value']}";
					break;
				default:
					show_500("Illigal key for method '{$row['directive_method']}' ('{$row['directive_directive']}')");
					break;
				}
				echo "{$indent}\$this->{$row['directive_method']}('{$row['directive_directive']}', {$value});\n";
				break;
			case 'remove':
				switch ($row['directive_directive'])
				{
				case 'hidden_list':
				case 'set_list':
				case 'null_list':
				case 'where_list':
				case 'limit_list':
					$value = $row['directive_value'];
					break;
				case 'has_hash':
				case 'action_hash':
				case 'select_hash':
				case 'fixed_hash':
				case 'success_hash':
				case 'join_hash':
				case 'where_hash':
				case 'order_by_hash':
					$value = $row['directive_value'];
					break;
				default:
					show_500("Illigal key for method '{$row['directive_method']}' ('{$row['directive_directive']}')");
					break;
				}
				echo "{$indent}\$this->{$row['directive_method']}('{$row['directive_directive']}', '{$value}');\n";
				break;
			default:
				show_500("Unknown Method ('{$row['directive_method']}')");
				break;
			}
			$last_row = $row;
		}

		// テーブル終了
		echo "\t}\n}\n";
		$ob = ob_get_clean();
		if ( ! file_exists(config('package_list')[0].'/models'))
		{
			mkdir(config('package_list')[0].'/models');
		}
		file_put_contents(config('package_list')[0].'/models/'.ucfirst($table).'_schema.php', $ob);

		if ($flash === TRUE)
		{
			flash(l('crud_schema_succeeded', [], TRUE), 'success');
			redirect('directive');
		}
	}

	// DBからモデルを生成
	public function decompile($flash = TRUE, $where = '1')
	{
		load_library('db');
		$table_list = "'".implode("', '", config('uri')['table_list'])."'";
		$actor_list = "'".implode("', '", array_reverse(array_keys(config('uri')['actor_hash'])))."'";
		$action_list = "'".implode("', '", array_keys(config('uri')['action_hash']))."'";
		$alias_list = "'".implode("', '", array_merge(config('uri')['table_list'], config('uri')['alias_list']))."'";
		$result = library('db')->query("SELECT * FROM `directive` WHERE {$where} ORDER BY
FIELD(`directive_table`, {$table_list}),
FIELD(`directive_actor`, '', {$actor_list}),
FIELD(`directive_action`, '', {$action_list}),
FIELD(`directive_alias`, '', {$alias_list}),
FIELD(`directive_directive`, 'select_hash', 'set_list', 'null_list', 'primary_key', 'display', 'use_card', 'has_hash', 'action_hash', 'hidden_list', 'fixed_hash', 'success_hash', 'join_hash', 'where_list', 'where_hash', 'order_by_hash', 'limit_list'),
`directive_id` ASC
");
		while (($row = $result->fetch_assoc()))
		{
			// エイリアス終了
			if ((isset($last_row) && $row['directive_alias'] !== $last_row['directive_alias']) && $last_row['directive_alias'] !== '')
			{
				$indent = substr($indent, 0, strlen($indent) - 1);
				echo "{$indent}endif;\n";
			}

			// アクション終了
			if ((isset($last_row) && $row['directive_action'] !== $last_row['directive_action']) && $last_row['directive_action'] !== '')
			{
				$indent = substr($indent, 0, strlen($indent) - 1);
				echo "{$indent}endif;\n";
			}

			// アクター終了
			if ((isset($last_row) && $row['directive_actor'] !== $last_row['directive_actor']) && $last_row['directive_actor'] !== '')
			{
				$indent = substr($indent, 0, strlen($indent) - 1);
				echo "{$indent}endif;\n";
			}

			// テーブル終了
			if (isset($last_row) && $row['directive_table'] !== $last_row['directive_table'])
			{
				echo "\t}\n}\n";
				$ob = ob_get_clean();
				if ( ! file_exists(config('package_list')[0].'/models'))
				{
					mkdir(config('package_list')[0].'/models');
				}
				file_put_contents(config('package_list')[0].'/models/'.ucfirst($table).'_model.php', $ob);
			}

			// テーブル開始
			if ( ! isset($last_row) OR $row['directive_table'] !== $last_row['directive_table'])
			{
				$table = $row['directive_table'];
				ob_start();
				$indent = "\t\t";
				echo "<?php\nrequire_once '".ucfirst($row['directive_table'])."_schema.php';\n\nclass ".ucfirst($row['directive_table'])."_model extends ".ucfirst($row['directive_table'])."_schema {\n\n\tpublic function __construct(\$params)\n\t{\n\t\tparent::__construct(\$params);\n\n";
			}

			// アクター開始
			if (( ! isset($last_row) OR $row['directive_actor'] !== $last_row['directive_actor']) && $row['directive_actor'] !== '')
			{
				echo "\n{$indent}// ".l('actor', [], TRUE).':'.l($row['directive_actor'], [], TRUE);
				echo "\n{$indent}if (\$this->actor === '{$row['directive_actor']}'):\n";
				$indent .= "\t";
			}

			// アクション開始
			if (( ! isset($last_row) OR $row['directive_action'] !== $last_row['directive_action']) && $row['directive_action'] !== '')
			{
				echo "\n{$indent}// ".l('action', [], TRUE).':'.l($row['directive_action'], [], TRUE);
				echo "\n{$indent}if (\$this->action === '{$row['directive_action']}'):\n";
				$indent .= "\t";
			}

			// エイリアス開始
			if (( ! isset($last_row) OR $row['directive_alias'] !== $last_row['directive_alias']) && $row['directive_alias'] !== '')
			{
				echo "\n{$indent}// ".l('alias', [], TRUE).':'.l($row['directive_alias'], [], TRUE)." (".h($row['directive_alias'], TRUE).")";
				echo "\n{$indent}if (\$this->alias === '{$row['directive_alias']}'):\n";
				$indent .= "\t";
			}

			switch ($row['directive_method'])
			{
			case 'overwrite':
				switch ($row['directive_directive'])
				{
				case 'table':
				case 'primary_key':
				case 'display':
				case 'use_card':
					$value = $row['directive_value'];
					break;
				default:
					show_500("Illigal key for method '{$row['directive_method']}' ('{$row['directive_directive']}')");
					break;
				}
				echo "{$indent}\$this->{$row['directive_method']}('{$row['directive_directive']}', {$value});\n";
				break;
			case 'append':
				switch ($row['directive_directive'])
				{
				case 'hidden_list':
				case 'set_list':
				case 'null_list':
				case 'where_list':
				case 'limit_list':
					$value = $row['directive_value'];
					break;
				case 'has_hash':
				case 'action_hash':
				case 'select_hash':
				case 'fixed_hash':
				case 'success_hash':
				case 'join_hash':
				case 'where_hash':
				case 'order_by_hash':
					$value = "'{$row['directive_key']}', {$row['directive_value']}";
					break;
				default:
					show_500("Illigal key for method '{$row['directive_method']}' ('{$row['directive_directive']}')");
					break;
				}
				echo "{$indent}\$this->{$row['directive_method']}('{$row['directive_directive']}', {$value});\n";
				break;
			case 'remove':
				switch ($row['directive_directive'])
				{
				case 'hidden_list':
				case 'set_list':
				case 'null_list':
				case 'where_list':
				case 'limit_list':
					$value = $row['directive_value'];
					break;
				case 'has_hash':
				case 'action_hash':
				case 'select_hash':
				case 'fixed_hash':
				case 'success_hash':
				case 'join_hash':
				case 'where_hash':
				case 'order_by_hash':
					$value = $row['directive_value'];
					break;
				default:
					show_500("Illigal key for method '{$row['directive_method']}' ('{$row['directive_directive']}')");
					break;
				}
				echo "{$indent}\$this->{$row['directive_method']}('{$row['directive_directive']}', '{$value}');\n";
				break;
			default:
				show_500("Unknown Method ('{$row['directive_method']}')");
				break;
			}
			$last_row = $row;
		}
		// エイリアス終了
		if ($last_row['directive_alias'] !== '')
		{
			$indent = substr($indent, 0, strlen($indent) - 1);
			echo "{$indent}endif;\n";
		}

		// アクション終了
		if ($last_row['directive_action'] !== '')
		{
			$indent = substr($indent, 0, strlen($indent) - 1);
			echo "{$indent}endif;\n";
		}

		// アクター終了
		if ($last_row['directive_actor'] !== '')
		{
			$indent = substr($indent, 0, strlen($indent) - 1);
			echo "{$indent}endif;\n";
		}

		// テーブル終了
		echo "\t}\n}\n";
		$ob = ob_get_clean();
		if ( ! file_exists(config('package_list')[0].'/models'))
		{
			mkdir(config('package_list')[0].'/models');
		}
		file_put_contents(config('package_list')[0].'/models/'.ucfirst($table).'_model.php', $ob);

		if ($flash === TRUE)
		{
			flash(l('crud_decompile_succeeded', [], TRUE), 'success');
			redirect('directive');
		}
	}
}
