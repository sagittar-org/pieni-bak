<?php
class Directive_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);

		$table_list = "'".implode("', '", config('uri')['table_list'])."'";
		$actor_list = "'".implode("', '", array_reverse(array_keys(config('uri')['actor_hash'])))."'";
		$action_list = "'".implode("', '", array_keys(config('uri')['action_hash']))."'";
		$alias_list = "'".implode("', '", array_merge(config('uri')['table_list'], config('uri')['alias_list']))."'";

		$this->overwrite('primary_key', 'directive_id');
		$this->overwrite('display', 'directive_id');
		$this->overwrite('use_card', FALSE);
		$this->append('action_hash', 'generate', 'table');
		$this->append('action_hash', 'regularize', 'table');
		$this->append('action_hash', 'compile', 'table');
		$this->append('action_hash', 'decompile', 'table');
		$this->append('action_hash', 'index', 'index');
		$this->append('action_hash', 'add', 'add');
		$this->append('action_hash', 'edit', 'edit');
		$this->append('action_hash', 'delete', 'delete');
		$this->append('select_hash', 'directive_id', NULL);
		$this->append('select_hash', 'directive_table', NULL);
		$this->append('select_hash', 'directive_actor', NULL);
		$this->append('select_hash', 'directive_action', NULL);
		$this->append('select_hash', 'directive_alias', NULL);
		$this->append('select_hash', 'directive_method', NULL);
		$this->append('select_hash', 'directive_directive', NULL);
		$this->append('select_hash', 'directive_key', NULL);
		$this->append('select_hash', 'directive_value', NULL);
		$this->append('hidden_list', 'directive_id');
		$this->append('set_list', 'directive_table');
		$this->append('set_list', 'directive_actor');
		$this->append('set_list', 'directive_action');
		$this->append('set_list', 'directive_alias');
		$this->append('set_list', 'directive_method');
		$this->append('set_list', 'directive_directive');
		$this->append('set_list', 'directive_key');
		$this->append('set_list', 'directive_value');
		$this->append('where_hash', 'directive_table', '`directive_table` = "$1"');
		$this->append('where_hash', 'directive_actor', '`directive_actor` = "$1"');
		$this->append('where_hash', 'directive_action', '`directive_action` = "$1"');
		$this->append('where_hash', 'directive_alias', '`directive_alias` = "$1"');
		$this->append('where_hash', 'directive_method', '`directive_method` = "$1"');
		$this->append('where_hash', 'directive_directive', '`directive_directive` = "$1"');
		$this->append('where_hash', 'directive_key', '`directive_key` = "$1"');
		$this->append('where_hash', 'directive_value', '`directive_value` LIKE "%$1%"');
		$this->append('order_by_hash', 'directive', "
FIELD(`directive_table`, {$table_list}),
FIELD(`directive_actor`, '', {$actor_list}),
FIELD(`directive_action`, '', {$action_list}),
FIELD(`directive_alias`, '', {$alias_list}),
FIELD(`directive_method`, 'overwrite', 'append', 'remove'),
FIELD(`directive_directive`, 'primary_key', 'display', 'use_card', 'has_hash', 'action_hash', 'select_hash', 'hidden_list', 'set_list', 'null_list', 'fixed_hash', 'success_hash', 'join_hash', 'where_list', 'where_hash', 'order_by_hash', 'limit_list'),
`directive_id` ASC");
		$this->append('limit_list', 100);

		if ($this->actor === 'm')
		{
			$this->remove('action_hash', 'genarate');
			$this->remove('action_hash', 'regularize');
			$this->remove('action_hash', 'compile');
			$this->remove('action_hash', 'decompile');
			$this->remove('action_hash', 'index');
			$this->remove('action_hash', 'add');
			$this->remove('action_hash', 'edit');
			$this->remove('action_hash', 'delete');
		}

		if ($this->actor === 'g')
		{
			$this->remove('action_hash', 'genarate');
			$this->remove('action_hash', 'regularize');
			$this->remove('action_hash', 'compile');
			$this->remove('action_hash', 'decompile');
			$this->remove('action_hash', 'index');
			$this->remove('action_hash', 'add');
			$this->remove('action_hash', 'edit');
			$this->remove('action_hash', 'delete');
		}

		if ($this->action === 'edit'):
			$this->remove('set_list', 'directive_table');
			$this->remove('set_list', 'directive_actor');
			$this->remove('set_list', 'directive_action');
			$this->remove('set_list', 'directive_alias');
			$this->remove('set_list', 'directive_method');
			$this->remove('set_list', 'directive_directive');
			$this->remove('set_list', 'directive_key');
		endif;
	}
}
