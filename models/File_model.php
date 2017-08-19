<?php
class File_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);

		$this->overwrite('primary_key', 'file_id');
		$this->overwrite('display', 'file_name');
		$this->overwrite('use_card', FALSE);
		$this->append('action_hash', 'download', 'row');
		$this->append('action_hash', 'index', 'index');
		$this->append('action_hash', 'add', 'add');
		$this->append('action_hash', 'edit', 'edit');
		$this->append('action_hash', 'delete', 'delete');
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

		if ($this->actor === 'm'):
			$this->remove('action_hash', 'index');
			$this->remove('action_hash', 'add');
			$this->remove('action_hash', 'edit');
			$this->remove('action_hash', 'delete');
		endif;

		if ($this->actor === 'g'):
			$this->remove('action_hash', 'index');
			$this->remove('action_hash', 'add');
			$this->remove('action_hash', 'edit');
			$this->remove('action_hash', 'delete');
		endif;

		if ($this->action === 'index'):
			$this->remove('select_hash', 'file_file');
		endif;

		if ($this->action === 'delete'):
			$this->remove('select_hash', 'file_file');
		endif;
	}
}
