<?php
class Comment_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);

		$this->overwrite('primary_key', 'comment_id');
		$this->overwrite('display', 'comment_writer');
		$this->overwrite('use_card', TRUE);
		$this->append('action_list', 'index');
		$this->append('action_list', 'add');
		$this->append('action_list', 'edit');
		$this->append('action_list', 'delete');
		$this->append('select_hash', 'comment_id', NULL);
		$this->append('select_hash', 'comment_post_id', NULL);
		$this->append('select_hash', 'post_name', NULL);
		$this->append('select_hash', 'comment_writer', NULL);
		$this->append('select_hash', 'comment_created', NULL);
		$this->append('select_hash', 'comment_text', NULL);
		$this->append('hidden_list', 'comment_id');
		$this->append('hidden_list', 'comment_post_id');
		$this->append('set_list', 'comment_writer');
		$this->append('set_list', 'comment_text');
		$this->append('fixed_hash', 'comment_created', 'CURRENT_TIMESTAMP');
		$this->append('join_hash', 'comment_post', ['table' => '`post`', 'cond' => '`post_id` = `comment_post_id`']);
		$this->append('where_hash', 'simple', 'CONCAT(`post_name`, `comment_writer`, `comment_text`) LIKE "%$1%"');
		$this->append('order_by_hash', 'comment_id_desc', '`comment_id` DESC');
		$this->append('limit_list', 10);

		if ($this->actor === 'a')
		{
			$this->remove('action_list', 'add');
		}

		if ($this->actor === 'm')
		{
			$this->append('where_list', "`post_member_id` = {$this->auth['id']}");
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
		}

		if ($this->actor === 'g')
		{
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');

			if ($this->alias === 'comment')
			{
				$this->remove('action_list', 'index');
				$this->remove('action_list', 'add');
			}
		}

		if ($this->alias === 'post_comment')
		{
			$this->append('fixed_hash', 'comment_post_id', $this->parent_id);
			$this->append('where_list', "`comment_post_id` = {$this->parent_id}");
			$this->remove('select_hash', 'post_name');
		}
	}
}
