<?php
class Post_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);

		$this->overwrite('primary_key', 'post_id');
		$this->overwrite('display', 'post_name');
		$this->overwrite('use_card', TRUE);
		$this->append('has_hash', 'post_comment', 'comment');
		$this->append('action_hash', 'index', 'index');
		$this->append('action_hash', 'view', 'view');
		$this->append('action_hash', 'add', 'add');
		$this->append('action_hash', 'edit', 'edit');
		$this->append('action_hash', 'delete', 'delete');
		$this->append('select_hash', 'post_id', NULL);
		$this->append('select_hash', 'post_member_id', NULL);
		$this->append('select_hash', 'member_name', NULL);
		$this->append('select_hash', 'post_name', NULL);
		$this->append('select_hash', 'post_created', NULL);
		$this->append('select_hash', 'post_text', NULL);
		$this->append('select_hash', 'post_image', NULL);
		$this->append('select_hash', 'count_comment', NULL);
		$this->append('hidden_list', 'post_id');
		$this->append('hidden_list', 'post_member_id');
		$this->append('set_list', 'post_name');
		$this->append('set_list', 'post_text');
		$this->append('set_list', 'post_image');
		$this->append('fixed_hash', 'post_created', 'CURRENT_TIMESTAMP');
		$this->append('join_hash', 'post_member', ['table' => '`member`', 'cond' => '`member_id` = `post_member_id`']);
		$this->append('join_hash', 'post_comment', ['table' => '(SELECT `comment_post_id`, COUNT(*) AS `count_comment` FROM `comment` GROUP BY `comment_post_id`)', 'cond' => '`comment_post_id` = `post_id`']);
		$this->append('where_hash', 'simple', 'CONCAT(`member_name`, `post_name`, `post_text`) LIKE "%$1%"');
		$this->append('order_by_hash', 'post_id_desc', '`post_id` DESC');
		$this->append('limit_list', 10);

		// アクター:管理者
		if ($this->actor === 'a'):
			$this->remove('action_hash', 'add');
		endif;

		// アクター:会員
		if ($this->actor === 'm'):
			$this->append('fixed_hash', 'post_member_id', $this->auth['id']);
			$this->append('where_list', "`post_member_id` = {$this->auth['id']}");
		endif;

		// アクター:ゲスト
		if ($this->actor === 'g'):
			$this->remove('action_hash', 'add');
			$this->remove('action_hash', 'edit');
			$this->remove('action_hash', 'delete');
		endif;

		// アクション:index
		if ($this->action === 'index'):
			$this->remove('select_hash', 'post_text');
		endif;

		// アクション:delete
		if ($this->action === 'delete'):
			$this->remove('select_hash', 'post_image');
		endif;

		// エイリアス:投稿 (member_post)
		if ($this->alias === 'member_post'):
			$this->append('where_list', "`post_member_id` = {$this->parent_id}");
			$this->remove('select_hash', 'post_member_id');
			$this->remove('select_hash', 'member_name');
		endif;
	}
}
