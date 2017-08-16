<?php
class News_model extends Crud_model {

	public function __construct($params)
	{
                parent::__construct($params);
		$this->select_hash = [
			'news_id'       => NULL,
			'news_admin_id' => NULL,
			'admin_name'    => NULL,
			'news_name'     => NULL,
			'news_created'  => NULL,
			'news_text'     => NULL,
			'news_image'    => NULL,
		];
		$this->join_hash = [
			'news_admin' => [
				'table' => '`admin`',
				'cond' => '`admin_id` = `news_admin_id`',
			],
		];
		$this->set_list = [
			'news_name',
			'news_text',
			'news_image',
		];
		$this->fixed_hash = ['news_created'  => 'CURRENT_TIMESTAMP'];
		$this->where_hash = ['simple' => 'CONCAT(`news_name`, `news_text`) LIKE "%$1%"'];
		$this->order_by_hash = ['news_id_desc' => "`news_id` DESC"];
		$this->hidden_list[] = 'news_admin_id';
		$this->use_card = TRUE;
		switch ($this->actor)
		{
		case 'a':
			$this->fixed_hash['news_admin_id'] = $this->auth['id'];
			break;
		case 'm':
			$this->action_list = [];
			break;
		case 'g':
			$this->action_list = ['index', 'view'];
			break;
		}
		switch ($this->action)
		{
		case 'index':
			unset($this->select_hash['news_text']);
			break;
		}
		switch ($this->alias)
		{
		case 'news_delete':
			unset($this->select_hash['news_image']);
			break;
		}
	}
}
