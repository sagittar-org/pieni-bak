<?php
class News_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);

		$this->overwrite('primary_key', 'news_id');
		$this->overwrite('display', 'news_name');
		$this->overwrite('use_card', TRUE);
		$this->append('action_hash', 'index', 'index');
		$this->append('action_hash', 'view', 'view');
		$this->append('action_hash', 'add', 'add');
		$this->append('action_hash', 'edit', 'edit');
		$this->append('action_hash', 'delete', 'delete');
		$this->append('select_hash', 'news_id', NULL);
		$this->append('select_hash', 'news_admin_id', NULL);
		$this->append('select_hash', 'admin_name', NULL);
		$this->append('select_hash', 'news_name', NULL);
		$this->append('select_hash', 'news_created', NULL);
		$this->append('select_hash', 'news_text', NULL);
		$this->append('select_hash', 'news_image', NULL);
		$this->append('hidden_list', 'news_id');
		$this->append('hidden_list', 'news_admin_id');
		$this->append('set_list', 'news_name');
		$this->append('set_list', 'news_text');
		$this->append('set_list', 'news_image');
		$this->append('fixed_hash', 'news_created', 'CURRENT_TIMESTAMP');
		$this->append('join_hash', 'news_admin', ['table' => '`admin`', 'cond' => '`admin_id` = `news_admin_id`']);
		$this->append('where_hash', 'simple', 'CONCAT(`news_name`, `news_text`) LIKE "%$1%"');
		$this->append('order_by_hash', 'news_id_desc', '`news_id` DESC');
		$this->append('limit_list', 10);

		// アクター:管理者
		if ($this->actor === 'a'):
			$this->append('fixed_hash', 'news_admin_id', $this->auth['id']);
		endif;

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
			$this->remove('action_hash', 'add');
			$this->remove('action_hash', 'edit');
			$this->remove('action_hash', 'delete');
		endif;

		// アクション:index
		if ($this->action === 'index'):
			$this->remove('select_hash', 'news_text');
		endif;

		// アクション:delete
		if ($this->action === 'delete'):
			$this->remove('select_hash', 'news_image');
		endif;
	}
}
