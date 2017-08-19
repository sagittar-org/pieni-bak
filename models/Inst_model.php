<?php
class Inst_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);
		$this->overwrite('primary_key', 'spec_id');
		$this->append('action_list', 'index');
		$this->append('action_list', 'view');
		$this->append('action_list', 'add');
		$this->append('action_list', 'edit');
		$this->append('action_list', 'delete');
		$this->append('select_hash', 'spec_id', NULL);
		$this->append('order_by_hash', 'spec_id_desc', '`spec_id` DESC');
		$this->append('limit_list', 100);
	}
}
