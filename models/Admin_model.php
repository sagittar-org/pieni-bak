<?php
class Admin_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);

		$this->overwrite('primary_key', 'admin_id');
		$this->overwrite('display', 'admin_name');
		$this->overwrite('use_card', TRUE);
		$this->append('action_hash', 'index', 'index');
		$this->append('action_hash', 'view', 'view');
		$this->append('action_hash', 'add', 'add');
		$this->append('action_hash', 'edit', 'edit');
		$this->append('action_hash', 'delete', 'delete');
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

		// アクター:会員
		if ($this->actor === 'm'):
			$this->remove('action_hash', 'index');
			$this->remove('action_hash', 'view');
			$this->remove('action_hash', 'add');
			$this->remove('action_hash', 'edit');
			$this->remove('action_hash', 'delete');
		endif;

		// アクター:ゲスト
		if ($this->actor === 'g'):
			$this->remove('action_hash', 'index');
			$this->remove('action_hash', 'view');
			$this->remove('action_hash', 'add');
			$this->remove('action_hash', 'edit');
			$this->remove('action_hash', 'delete');
		endif;
	}
}
