<?php
class Post_model extends Crud_model {

	public function __construct($params)
	{
		parent::__construct($params);
		$this->overwrite('use_card' , TRUE);
		$this->append('has_hash', 'post_comment', 'comment');
		$this->append('action_list', 'index');
		$this->append('action_list', 'view');
		$this->append('action_list', 'add');
		$this->append('action_list', 'edit');
		$this->append('action_list', 'delete');

		$this->overwrite('select_hash', [
			'post_id' => NULL,
			'post_member_id' => NULL,
			'member_name' => NULL,
			'post_name' => NULL,
			'post_created' => NULL,
			'post_text' => NULL,
			'post_image' => NULL,
			'count_comment' => NULL,
		]);
		$this->overwrite('set_list', [
			'post_name',
			'post_text',
			'post_image',
		]);
		$this->overwrite('join_hash', [
			'post_member' => [
				'table' => '`member`',
				'cond' => '`member_id` = `post_member_id`',
			],
			'post_comment' => [
				'table' => '(SELECT `comment_post_id`, COUNT(*) AS `count_comment` FROM `comment` GROUP BY `comment_post_id`)',
				'cond' => '`comment_post_id` = `post_id`',
			],
		]);
		$this->overwrite('order_by_hash', ['post_id_desc' => "`post_id` DESC"]);
		$this->append('fixed_hash' , 'post_created', 'CURRENT_TIMESTAMP');
		$this->append('where_hash' , 'simple', 'CONCAT(`member_name`, `post_name`, `post_text`) LIKE "%$1%"');
		$this->append('hidden_list' , 'post_member_id');
		switch ($this->actor)
		{
		case 'a':
			$this->remove('action_list', 'add');
			break;
		case 'm':
			$this->remove('select_hash', 'post_member_id');
			$this->append('where_list' , "`post_member_id` = {$this->auth['id']}");
			$this->append('fixed_hash' , 'post_member_id', $this->auth['id']);
			break;
		case 'g':
			$this->remove('action_list', 'add');
			$this->remove('action_list', 'edit');
			$this->remove('action_list', 'delete');
			break;
		}
		switch ($this->action)
		{
		case 'index':
			$this->remove('select_hash', 'post_text');
			break;
		case 'delete':
			$this->remove('select_hash', 'post_image');
			break;
		}
		switch ($this->alias)
		{
		case 'member_post':
			$this->remove('select_hash', 'post_member_id');
			$this->remove('select_hash', 'member_name');
			$this->append('where_list', "`post_member_id` = {$this->parent_id}");
			break;
		}
	}
}
