<?php
class File_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);

		$this->append('action_list', 'index');
		$this->append('action_list', 'add');
		$this->append('action_list', 'edit');
		$this->append('action_list', 'delete');
		$this->append('row_action_hash', 'download', 'view');
		$this->append('select_hash', 'file_id', NULL);
		$this->append('select_hash', 'file_name', NULL);
		$this->append('select_hash', 'file_created', NULL);
		$this->append('select_hash', 'file_file', NULL);
		$this->append('hidden_list', 'file_id');
		$this->append('set_list', 'file_name');
		$this->append('set_list', 'file_file');
		$this->append('fixed_hash', 'file_created', 'CURRENT_TIMESTAMP');
		$this->append('where_hash', 'simple', '`file_name` LIKE "%$1%"');
		$this->append('order_by_hash', 'file_id_desc', '`file_id` DESC');
		$this->append('limit_list', 10);

		$this->actor('m');
			$this->remove('action_list', 'index');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
		$this->actor();

		$this->actor('g');
			$this->remove('action_list', 'index');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
		$this->actor();

		switch ($this->action)
		{
		case 'index':
			$this->remove('select_hash', 'file_file');
			break;
		case 'delete':
			$this->remove('select_hash', 'file_file');
			break;
		}
	}
}
