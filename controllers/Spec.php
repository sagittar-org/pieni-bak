<?php
class Spec extends Controller {

	public function __construct()
	{
		parent::__construct();
		if ( ! in_array(uri('actor'), config('spec')['actor_list']))
		{
			show_404();
		}
	}

	// トップページ
	public function index()
	{
		load_template(__FUNCTION__);
	}

	// エンティティ表示
	public function table($actor, $class)
	{
		load_template(__FUNCTION__, ['actor' => $actor, 'class' => $class]);
	}
}
