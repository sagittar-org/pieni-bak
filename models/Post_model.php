<?php
class Post_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);
		$this->overwrite('use_card', TRUE);
		$this->append('has_hash', 'post_comment', 'comment');
		$this->append('action_list', 'index');
		$this->append('action_list', 'view');
		$this->append('action_list', 'add');
		$this->append('action_list', 'edit');
		$this->append('action_list', 'delete');
		$this->append('select_hash', 'post_id', NULL);
		$this->append('select_hash', 'post_member_id', NULL);
		$this->append('select_hash', 'member_name', NULL);
		$this->append('select_hash', 'post_name', NULL);
		$this->append('select_hash', 'post_created', NULL);
		$this->append('select_hash', 'post_text', NULL);
		$this->append('select_hash', 'post_image', NULL);
		$this->append('select_hash', 'count_comment', NULL);
		$this->append('hidden_list', 'post_id');
		$this->append('hidden_list' , 'post_member_id');
		$this->append('set_list', 'post_name');
		$this->append('set_list', 'post_text');
		$this->append('set_list', 'post_image');
		$this->append('fixed_hash', 'post_created', 'CURRENT_TIMESTAMP');
		$this->append('join_hash', 'post_member', [
			'table' => '`member`',
			'cond' => '`member_id` = `post_member_id`',
		]);
		$this->append('join_hash', 'post_comment', [
			'table' => '(SELECT `comment_post_id`, COUNT(*) AS `count_comment` FROM `comment` GROUP BY `comment_post_id`)',
			'cond' => '`comment_post_id` = `post_id`',
		]);
		$this->append('where_hash' , 'simple', 'CONCAT(`member_name`, `post_name`, `post_text`) LIKE "%$1%"');
		$this->append('order_by_hash', 'post_id_desc', '`post_id` DESC');
		$this->append('limit_list', 10);

		if ($this->actor === 'a')
		{
			$this->remove('action_list', 'add');
		}

		if ($this->actor === 'm')
		{
			$this->remove('select_hash', 'post_member_id');
			$this->append('where_list' , "`post_member_id` = {$this->auth['id']}");
			$this->append('fixed_hash' , 'post_member_id', $this->auth['id']);
		}

		if ($this->actor === 'g')
		{
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
		}

		if ($this->action === 'index')
		{
			$this->remove('select_hash', 'post_text');
		}

		if ($this->action === 'delete')
		{
			$this->remove('select_hash', 'post_image');
		}

		if ($this->alias === 'member_post')
		{
			$this->remove('select_hash', 'post_member_id');
			$this->remove('select_hash', 'member_name');
			$this->append('where_list', "`post_member_id` = {$this->parent_id}");
		}
	}
}
