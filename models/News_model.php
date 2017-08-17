<?php
class News_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);
		$this->overwrite('use_card', TRUE);
		$this->append('action_list', 'index');
		$this->append('action_list', 'view');
		$this->append('action_list', 'add');
		$this->append('action_list', 'edit');
		$this->append('action_list', 'delete');
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
		$this->append('join_hash', 'news_admin', [
			'table' => '`admin`',
			'cond' => '`admin_id` = `news_admin_id`',
		]);
		$this->append('where_hash', 'simple', 'CONCAT(`news_name`, `news_text`) LIKE "%$1%"');
		$this->append('order_by_hash', 'news_id_desc', '`news_id` DESC');
		$this->append('limit_list', 10);

		$this->actor('a');
			$this->append('fixed_hash', 'news_admin_id', $this->auth['id']);
		$this->actor();

		$this->actor('m');
			$this->remove('action_list', 'index');
			$this->remove('action_list', 'view');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
		$this->actor();

		$this->actor('g');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
		$this->actor();

		switch ($this->action)
		{
		case 'index':
			$this->remove('select_hash', 'news_text');
			break;
		}
		switch ($this->alias)
		{
		case 'news_delete':
			$this->remove('select_hash', 'news_image');
			break;
		}
	}
}
