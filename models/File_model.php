<?php
class File_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);
		$this->append('action_list', 'index');
		$this->append('action_list', 'add');
		$this->append('action_list', 'edit');
		$this->append('action_list', 'delete');

		$this->overwrite('select_hash', [
			'file_id' => NULL,
			'file_name' => NULL,
			'file_created' => NULL,
			'file_file' => NULL,
		]);
		$this->overwrite('set_list', [
			'file_name',
			'file_file',
		]);
		$this->overwrite('order_by_hash', ['file_id_desc' => "`file_id` DESC"]);
		$this->append('row_action_hash', 'download', 'view');
		$this->append('fixed_hash', 'file_created', 'CURRENT_TIMESTAMP');
		$this->append('where_hash', 'simple', '`file_name` LIKE "%$1%"');
		switch ($this->actor)
		{
		case 'm':
			$this->remove('action_list', 'index');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
			break;
		case 'g':
			$this->remove('action_list', 'index');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
			break;
		}
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
