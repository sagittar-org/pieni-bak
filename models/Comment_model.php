<?php
class Comment_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);
		$this->overwrite('select_hash', [
			'comment_id' => NULL,
			'comment_post_id' => NULL,
			'post_name' => NULL,
			'comment_writer' => NULL,
			'comment_created' => NULL,
			'comment_text' => NULL,
		]);
		$this->overwrite('set_list', [
			'comment_writer',
			'comment_text',
		]);
		$this->overwrite('join_hash', [
			'comment_post' => [
				'table' => '`post`',
				'cond' => '`post_id` = `comment_post_id`',
			],
		]);
		$this->overwrite('order_by_hash', ['comment_id_desc' => '`comment_id` DESC']);
		$this->overwrite('display', 'comment_writer');
		$this->overwrite('use_card', TRUE);
		$this->append('fixed_hash', 'comment_created', 'CURRENT_TIMESTAMP');
		$this->append('where_hash', 'simple', 'CONCAT(`post_name`, `comment_writer`, `comment_text`) LIKE "%$1%"');
		$this->append('hidden_list', 'comment_post_id');
		switch ($this->actor)
		{
		case 'a':
			$this->remove('action_list', 'view');
			$this->remove('action_list', 'add');
			break;
		case 'm':
			$this->remove('action_list', 'view');
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
			$this->append('where_list', "`post_member_id` = {$this->auth['id']}");
			break;
		case 'g':
			$this->remove('action_list', 'view');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
			switch ($this->alias)
			{
			case 'comment':
				$this->remove('action_list', 'index');
				$this->remove('action_list', 'add');
				break;
			case 'post_comment':
				$this->append('fixed_hash', 'comment_post_id', $this->parent_id);
				break;
			}
			break;
		}
		switch ($this->alias)
		{
		case 'post_comment':
			$this->remove('select_hash', 'post_name');
			$this->append('where_list', "`comment_post_id` = {$this->parent_id}");
			break;
		}
	}
}
