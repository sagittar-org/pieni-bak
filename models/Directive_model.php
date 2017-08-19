<?php
class Directive_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);

		$table_list = "'".implode("', '", config('uri')['table_list'])."'";
		$actor_list = "'".implode("', '", array_reverse(array_keys(config('uri')['actor_hash'])))."'";
		$action_list = "'".implode("', '", config('uri')['action_list'])."'";
		$alias_list = "'".implode("', '", array_merge(config('uri')['table_list'], config('uri')['alias_list']))."'";

		$this->overwrite('display', 'directive_id');
		$this->append('action_list', 'index');
		$this->append('action_list', 'add');
		$this->append('action_list', 'edit');
		$this->append('action_list', 'delete');
		$this->append('select_hash', 'directive_id', NULL);
		$this->append('select_hash', 'directive_table', NULL);
		$this->append('select_hash', 'directive_actor', NULL);
		$this->append('select_hash', 'directive_action', NULL);
		$this->append('select_hash', 'directive_alias', NULL);
		$this->append('select_hash', 'directive_method', NULL);
		$this->append('select_hash', 'directive_key1', NULL);
		$this->append('select_hash', 'directive_key2', NULL);
		$this->append('select_hash', 'directive_value', NULL);
		$this->append('hidden_list', 'directive_id');
		$this->append('set_list', 'directive_table');
		$this->append('set_list', 'directive_actor');
		$this->append('set_list', 'directive_action');
		$this->append('set_list', 'directive_alias');
		$this->append('set_list', 'directive_method');
		$this->append('set_list', 'directive_key1');
		$this->append('set_list', 'directive_key2');
		$this->append('set_list', 'directive_value');
		$this->append('where_hash', 'directive_table', '`directive_table` = "$1"');
		$this->append('where_hash', 'directive_actor', '`directive_actor` = "$1"');
		$this->append('where_hash', 'directive_action', '`directive_action` = "$1"');
		$this->append('where_hash', 'directive_alias', '`directive_alias` = "$1"');
		$this->append('where_hash', 'directive_method', '`directive_method` = "$1"');
		$this->append('where_hash', 'directive_key1', '`directive_key1` = "$1"');
		$this->append('where_hash', 'directive_key2', '`directive_key2` = "$1"');
		$this->append('where_hash', 'directive_value', '`directive_value` LIKE "%$1%"');
		$this->append('order_by_hash', 'directive', "`directive_table` IS NULL DESC, FIELD(`directive_table`, {$table_list}),
`directive_alias` IS NULL DESC, FIELD(`directive_alias`, {$alias_list}),
`directive_action` IS NULL DESC, FIELD(`directive_action`, {$actor_list}),
`directive_actor` IS NULL DESC, FIELD(`directive_actor`, {$actor_list}),
`directive_method` IS NULL DESC, FIELD(`directive_method`, 'overwrite', 'append', 'remove'),
`directive_key1` IS NULL DESC, FIELD(`directive_key1`, 'primary_key', 'display', 'use_card', 'has_hash', 'action_list', 'row_action_hash', 'select_hash', 'hidden_list', 'set_list', 'fixed_hash', 'success_hash', 'join_hash', 'where_list', 'where_hash', 'order_by_hash', 'limit_list'),
`directive_id` ASC");
		$this->append('limit_list', 100);
	}
}
