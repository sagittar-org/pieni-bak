<?php
class Admin_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);
		$this->overwrite('use_card',TRUE);
		$this->append('action_list', 'index');
		$this->append('action_list', 'view');
		$this->append('action_list', 'add');
		$this->append('action_list', 'edit');
		$this->append('action_list', 'delete');

		$this->overwrite('select_hash', [
			'admin_id' => NULL,
			'admin_name' => NULL,
			'admin_email' => NULL,
		]);
		$this->overwrite('set_list', [
			'admin_name',
			'admin_email',
			'admin_password',
		]);
		$this->overwrite('order_by_hash', ['admin_id_asc' => "`admin_id` DESC"]);
		$this->append('where_hash', 'simple', 'CONCAT(`admin_name`, `admin_email`) LIKE "%$1%"');
		switch ($this->actor)
		{
		case 'm':
			$this->remove('action_list', 'index');
			$this->remove('action_list', 'view');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
			break;
		case 'g':
			$this->remove('action_list', 'index');
			$this->remove('action_list', 'view');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
			break;
		}
	}
}
