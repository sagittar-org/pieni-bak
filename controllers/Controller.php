<?php
class Controller {

	public function __construct()
	{
		// 内部遷移ではなく言語がデフォルト言語なら
		if (( ! isset($_SERVER['HTTP_REFERER']) OR ! preg_match('#^'.site_url('', FALSE, FALSE).'#', $_SERVER['HTTP_REFERER'])) && uri('language') === config('uri')['language_list'][0])
		{
			$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			// 言語がデフォルト言語以外として定義されていればその言語へリダイレクト
			if (in_array($lang, array_slice(config('uri')['language_list'], 1)))
			{
				redirect($lang.'/'.uri('uri_string'), FALSE, FALSE);
			}
		}

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
