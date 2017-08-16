<?php
class Member_model extends Crud_model {

	public function __construct($params)
	{
                parent::__construct($params);
		$this->has_hash = ['member_post' => 'post'];
		$this->row_action_hash['proxy'] = 'view';
		$this->select_hash = [
			'member_id' => NULL,
			'member_name' => NULL,
			'member_email' => NULL,
		];
		$this->set_list = [
			'member_name',
			'member_email',
			'member_password',
		];
		$this->where_hash = ['simple' => 'CONCAT(`member_name`, `member_email`) LIKE "%$1%"'];
		$this->order_by_hash = ['member_id_asc' => "`member_id` DESC"];
		$this->use_card = TRUE;
		switch ($this->actor)
		{
		case 'm':
			$this->action_list = ['view', 'edit'];
			$this->row_action_hash = [];
			$this->where_list[] = "`member_id` = '{$this->auth['id']}'";
			break;
		case 'g':
			$this->action_list = ['index', 'view'];
			$this->row_action_hash = [];
			break;
		}
	}
}
