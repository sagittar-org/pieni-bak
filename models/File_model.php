<?php
class File_model extends Crud_model {

	public function __construct($params)
	{
                parent::__construct($params);
		$this->remove('action_list', 'view');
		$this->append('row_action_hash', 'download', 'view');
		$this->overwrite('select_hash', [
			'file_id'      => NULL,
			'file_name'    => NULL,
			'file_created' => NULL,
			'file_file'    => NULL,
		]);
		$this->overwrite('set_list', [
			'file_name',
			'file_file',
		]);
		$this->append('fixed_hash', 'file_created', 'CURRENT_TIMESTAMP');
		$this->append('where_hash', 'simple', '`file_name` LIKE "%$1%"');
		$this->overwrite('order_by_hash', ['file_id_desc' => "`file_id` DESC"]);
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
