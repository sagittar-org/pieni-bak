<?php
class Admin_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);

		$this->overwrite('use_card', TRUE);
		$this->append('action_list', 'index');
		$this->append('action_list', 'view');
		$this->append('action_list', 'add');
		$this->append('action_list', 'edit');
		$this->append('action_list', 'delete');
		$this->append('select_hash', 'admin_id', NULL);
		$this->append('select_hash', 'admin_name', NULL);
		$this->append('select_hash', 'admin_email', NULL);
		$this->append('hidden_list', 'admin_id');
		$this->append('set_list', 'admin_name');
		$this->append('set_list', 'admin_email');
		$this->append('set_list', 'admin_password');
		$this->append('where_hash', 'simple', 'CONCAT(`admin_name`, `admin_email`) LIKE "%$1%"');
		$this->append('order_by_hash', 'admin_id_asc', '`admin_id` DESC');
		$this->append('limit_list', 10);

		if ($this->actor === 'm')
		{
			$this->remove('action_list', 'index');
			$this->remove('action_list', 'view');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
		}

		if ($this->actor === 'g')
		{
			$this->remove('action_list', 'index');
			$this->remove('action_list', 'view');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
		}
	}
}
