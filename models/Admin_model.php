<?php
class Admin_model extends Crud_model {

	public function __construct($params)
	{
                parent::__construct($params);
		$this->select_hash = [
			'admin_id' => NULL,
			'admin_name' => NULL,
			'admin_email' => NULL,
		];
		$this->set_list = [
			'admin_name',
			'admin_email',
			'admin_password',
		];
		$this->where_hash = ['simple' => 'CONCAT(`admin_name`, `admin_email`) LIKE "%$1%"'];
		$this->order_by_hash = ['admin_id_asc' => "`admin_id` DESC"];
		$this->use_card = TRUE;
		switch ($this->actor)
		{
		case 'm':
			$this->action_list = [];
			break;
		case 'g':
			$this->action_list = [];
			break;
		}
	}
}
