<?php
class Directive_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);
		$this->overwrite('display', 'directive_id');
		$this->append('action_list', 'index');
		$this->append('action_list', 'view');
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
		$this->append('order_by_hash', 'directive_id_desc', '`directive_id` DESC');
		$this->append('limit_list', 100);
	}
}
