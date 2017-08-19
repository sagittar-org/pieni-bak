<?php
class Member_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);

		$this->overwrite('primary_key', 'member_id');
		$this->overwrite('display', 'member_name');
		$this->overwrite('use_card', TRUE);
		$this->append('has_hash', 'member_post', 'post');
		$this->append('action_hash', 'proxy', 'row');
		$this->append('action_hash', 'index', 'index');
		$this->append('action_hash', 'view', 'view');
		$this->append('action_hash', 'add', 'add');
		$this->append('action_hash', 'edit', 'edit');
		$this->append('action_hash', 'delete', 'delete');
		$this->append('select_hash', 'member_id', NULL);
		$this->append('select_hash', 'member_name', NULL);
		$this->append('select_hash', 'member_email', NULL);
		$this->append('hidden_list', 'member_id');
		$this->append('set_list', 'member_name');
		$this->append('set_list', 'member_email');
		$this->append('set_list', 'member_password');
		$this->append('where_hash', 'simple', 'CONCAT(`member_name`, `member_email`) LIKE "%$1%"');
		$this->append('order_by_hash', 'member_id_desc', '`member_id` DESC');
		$this->append('limit_list', 10);

		// アクター:会員
		if ($this->actor === 'm'):
			$this->append('where_list', "`member_id` = '{$this->auth['id']}'");
			$this->remove('action_hash', 'proxy');
			$this->remove('action_hash', 'index');
			$this->remove('action_hash', 'add');
			$this->remove('action_hash', 'delete');
		endif;

		// アクター:ゲスト
		if ($this->actor === 'g'):
			$this->remove('action_hash', 'proxy');
			$this->remove('action_hash', 'add');
			$this->remove('action_hash', 'edit');
			$this->remove('action_hash', 'delete');
		endif;
	}
}
