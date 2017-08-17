<?php
class Member_model extends Crud_model {

	public function __construct($params)
	{
                parent::__construct($params);
		$this->overwrite('has_hash', 'member_post', 'post');
		$this->overwrite('select_hash', [
			'member_id'    => NULL,
			'member_name'  => NULL,
			'member_email' => NULL,
		]);
		$this->overwrite('set_list', [
			'member_name',
			'member_email',
			'member_password',
		]);
		$this->overwrite('use_card', TRUE);
		$this->append('row_action_hash', 'proxy', 'view');
		$this->append('where_hash', 'simple', 'CONCAT(`member_name`, `member_email`) LIKE "%$1%"');
		$this->append('order_by_hash', 'member_id_desc', "`member_id` DESC");
		switch ($this->actor)
		{
		case 'm':
			$this->remove('action_list', 'index');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'delete');
			$this->remove('row_action_hash', 'proxy');
			$this->append('where_list', "`member_id` = '{$this->auth['id']}'");
			break;
		case 'g':
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
			$this->remove('row_action_hash', 'proxy');
			break;
		}
	}
}
