<?php
class Comment_model extends Crud_model {

	public function __construct($params)
	{
                parent::__construct($params);
		$this->select_hash = [
			'comment_id'      => NULL,
			'comment_post_id' => NULL,
			'post_name'       => NULL,
			'comment_writer'  => NULL,
			'comment_created' => NULL,
			'comment_text'    => NULL,
		];
		$this->join_hash = [
			'comment_post' => [
				'table' => '`post`',
				'cond' => '`post_id` = `comment_post_id`',
			],
		];
		$this->set_list = [
			'comment_writer',
			'comment_text',
		];
		$this->fixed_hash = ['comment_created' => 'CURRENT_TIMESTAMP'];
		$this->where_hash = ['simple' => 'CONCAT(`post_name`, `comment_writer`, `comment_text`) LIKE "%$1%"'];
		$this->order_by_hash = ['comment_id_desc' => "`comment_id` DESC"];
		$this->hidden_list[] = 'comment_post_id';
		$this->display = 'comment_writer';
		$this->use_card = TRUE;
		switch ($this->actor)
		{
		case 'a':
			$this->action_list = ['index', 'edit', 'delete'];
			break;
		case 'm':
			$this->action_list = ['index'];
			$this->where_list[] = "`post_member_id` = {$this->auth['id']}";
			break;
		case 'g':
			$this->action_list = [];
			switch ($this->alias)
			{
			case 'post_comment':
				$this->action_list= ['index', 'add'];
				$this->fixed_hash['comment_post_id'] = $this->parent_id;
				break;
			}
			break;
		}
		switch ($this->alias)
		{
		case 'post_comment':
			unset($this->select_hash['post_name']);
			$this->where_list = ["`comment_post_id` = {$this->parent_id}"];
			$this->where_hash = [];
			break;
		}
	}
}
