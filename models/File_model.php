<?php
class File_model extends Crud_model {

	public function __construct($params)
	{
                parent::__construct($params);
		$this->action_list = ['index', 'add', 'edit', 'delete'];
		$this->row_action_hash['download'] = 'view';
		$this->select_hash = [
			'file_id' => NULL,
			'file_name' => NULL,
			'file_created' => NULL,
			'file_file' => NULL,
		];
		$this->set_list = [
			'file_name',
			'file_file',
		];
		$this->fixed_hash = ['file_created' => 'CURRENT_TIMESTAMP'];
		$this->where_hash = ['simple' => '`file_name` LIKE "%$1%"'];
		$this->order_by_hash = ['file_id_desc' => "`file_id` DESC"];
		switch ($this->actor)
		{
		case 'm':
			$this->action_list = [];
			break;
		case 'g':
			$this->action_list = [];
			break;
		}
		switch ($this->action)
		{
		case 'index':
			unset($this->select_hash['file_file']);
			break;
		case 'delete':
			unset($this->select_hash['file_file']);
			break;
		}
		switch ($this->alias)
		{
		case 'file_edit':
			unset($this->select_hash['file_created']);
			break;
		case 'file_delete':
			unset($this->select_hash['file_file']);
			break;
		}
	}
}
