<?php
class Controller {

	public function __construct()
	{
		// セッション開始
		session_name(config('session')['name']);
		session_start();

		// 出力バッファ開始
		ob_start();

		// アクター用セッション領域
		if ( ! isset($_SESSION[uri('actor')]))
		{
			$_SESSION[uri('actor')] = [];
		}
		if ( ! isset($_SESSION[uri('actor')]['auth']))
		{
			$_SESSION[uri('actor')]['auth'] = [];
		}

		// 認証制御
		if ( ! isset($_SESSION[uri('actor')]['auth']['id']) && isset(config('auth')[uri('actor')]['login']) && uri('class') !== 'auth')
		{
			$redirect = uri('uri_string');
			if ($_GET !== [])
			{
				$redirect .= '?'.http_build_query($_GET);
			}
			$_SESSION[uri('actor')]['auth']['redirect'] = $redirect;
			redirect('auth/login/'.uri('actor'), TRUE, FALSE);
		}
		if (isset(config('auth')[uri('actor')]['force']) && isset($_SESSION[config('auth')[uri('actor')]['force']]['auth']['id']) && uri('class') !== 'auth')
		{
			redirect(config('auth')[uri('actor')]['force'].'/'.uri('uri_string'), TRUE, FALSE);
		}
	}
}
