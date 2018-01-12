<?php
if ( ! function_exists('load_language'))
{
	// 言語ファイルを読み込む
	function load_language($path)
	{
		if (file_exists($path))
		{
			$book = PHPExcel_IOFactory::load($path);
			$book->setActiveSheetIndex(0);
			$sheet = $book->getActiveSheet();
			for ($c = 1; ($language = $sheet->getCellByColumnAndRow($c, 1)->getValue()) !== null; $c++) {
				$language_list[] = $language;
			}
			for ($s = 0; $s < $book->getSheetCount(); $s++) {
				$book->setActiveSheetIndex($s);
				$sheet = $book->getActiveSheet();
				for ($r = 2; $sheet->getCell('A'.$r)->getValue() !== null; $r++) {
					$row = [];
					foreach ($language_list as $i => $language) {
						for ($c = $i + 1; ($value = $sheet->getCellByColumnAndRow($c, $r)->getValue()) === NULL; $c--) ;
						if ( ! in_array($language, config('uri')['language_list'])) continue;
						$row[$language] = $value;
					}
					$language_hash[$sheet->getCellByColumnAndRow(0, $r)->getValue()] = $row;
				}
			}
			$GLOBALS['language_hash'] = array_merge($language_hash, $GLOBALS['language_hash']);
		}
	}
}

if ( ! function_exists('exec_request'))
{
	// リクエストを実行
	function exec_request($config)
	{
		global $uri;
		global $controler;

		// URI連想配列を取得
		$uri['uri_string'] = trim(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/', '/');
		$uri['param_arr']  = $uri['uri_string'] !== '' ? explode('/', $uri['uri_string']) : [];
		$uri['language']   = isset($uri['param_arr'][0]) && in_array($uri['param_arr'][0], array_slice($config['uri']['language_list'], 1)) ? array_shift($uri['param_arr']) : $config['uri']['language_list'][0];
		$uri['actor']      = isset($uri['param_arr'][0]) && in_array($uri['param_arr'][0], array_slice(array_keys($config['uri']['actor_hash']), 1)) ? array_shift($uri['param_arr']) : array_keys($config['uri']['actor_hash'])[0];
		$uri['class']      = isset($uri['param_arr'][0]) && in_array($uri['param_arr'][0], array_slice(array_merge($config['uri']['class_list'], $config['uri']['table_list']), 1)) ? array_shift($uri['param_arr']) : $config['uri']['class_list'][0];
		$uri['method']     = isset($uri['param_arr'][0]) ? array_shift($uri['param_arr']) : 'index';
		if (in_array($uri['class'], $config['uri']['table_list']))
		{
			$uri['id']        = isset($uri['param_arr'][0]) ? array_shift($uri['param_arr']) : NULL;
			$uri['alias']     = isset($uri['param_arr'][0]) ? array_shift($uri['param_arr']) : $uri['class'];
			$uri['parent_id'] = isset($uri['param_arr'][0]) ? array_shift($uri['param_arr']) : NULL;
		}

		// composer
		require_once __DIR__.'/../../../autoload.php';

		// 言語ファイルを読み込み
		$GLOBALS['language_hash'] = [];
		$paths = [
			[uri('language'), ''],
			[uri('actor'), ''],
		];
		foreach (config('package_list') as $package)
		{
			foreach (cartesian($paths) as $array)
			{
				$path = "{$package}/language/".implode('/', array_filter($array, 'strlen'))."/common.xlsx";
				load_language($path);
			}
		}
		$path = config('package_list')[0].'/models/schema.xlsx';
		load_language($path);

		// コントローラインスタンスを生成・メソッドを実行
		fallback_ro('Controller.php', 'controllers');
		fallback_ro('Crud.php', 'controllers');
		prepare_request();
		$classname = ucfirst($uri['class']);
		if (in_array($uri['class'], $config['uri']['table_list']) && fallback("{$classname}.php", 'controllers') === NULL)
		{
			$classname = 'Crud';
		}
		else
		{
			fallback_ro("{$classname}.php", 'controllers');
		}
		$controller = new $classname();
		if ( ! method_exists($controller, $uri['method']))
		{
			show_404();
		}
		call_user_func_array([$controller, $uri['method']], $uri['param_arr']);
	}
}

if ( ! function_exists('prepare_request'))
{
	function prepare_request()
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

if ( ! function_exists('cartesian'))
{
	// 直積を返す
	function cartesian($set)
	{
		if ( ! $set)
		{
			return [[]];
		}
		$subset = array_shift($set);
		$cartesian_subset = cartesian($set);
		$result = array();
		foreach ($subset as $value)
		{
			foreach ($cartesian_subset as $p)
			{
				array_unshift($p, $value);
				$result[] = $p;
			}
		}
	 	return $result;
	}
}

if ( ! function_exists('fallback'))
{
	// 優先度の高いファイル名を返す
	function fallback($filename, $dir, $paths = [])
	{
		foreach (config('package_list') as $package)
		{
			foreach (cartesian($paths) as $array)
			{
				$path = implode('/', array_filter($array, 'strlen'));
				if (file_exists("{$package}/{$dir}/{$path}/{$filename}"))
				{
					return "{$package}/{$dir}/{$path}/{$filename}";
				}
			}
		}
		return NULL;
	}
}

if ( ! function_exists('fallback_ro'))
{
	// 優先度の高いファイルを読み込む
	function fallback_ro($filename, $dir)
	{
		if (($fallback = fallback($filename, $dir)) === NULL)
		{
			show_500("'{$dir}/{$filename}' not found");
		}
		require_once $fallback;
	}
}

if ( ! function_exists('load_model'))
{
	// モデルを読み込む
	function load_model($name, $params, $key = NULL)
	{
		$params['alias'] = isset($params['alias']) ? $params['alias'] : $params['class'];
		$key = $key !== NULL ? $key : $params['alias'];
		if (isset($GLOBALS['models'][$key]))
		{
			return $GLOBALS['models'][$key];
		}
		$classname = ucfirst($name).'_model';
		if (($fallback = fallback("{$classname}.php", 'models')) === NULL)
		{
			show_500("'{$classname}' not found");
		}
		if (in_array($name, config('uri')['table_list']))
		{
			fallback_ro('Crud_model.php', 'models');
		}
		require_once $fallback;
		return $GLOBALS['models'][$key] = new $classname($params);
	}
}

if ( ! function_exists('load_library'))
{
	// ライブラリを読み込む
	function load_library($name)
	{
		if (isset($GLOBALS['libraries'][$name]))
		{
			return $GLOBALS['libraries'][$name];
		}
		$classname = ucfirst($name).'_library';
		if (($fallback = fallback("{$classname}.php", 'libraries')) === NULL)
		{
			show_500("'{$classname}' not found");
		}
		require_once $fallback;
		return $GLOBALS['libraries'][$name] = new $classname();
	}
}

if ( ! function_exists('load_view'))
{
	// ビューを読み込む
	function load_view($name_list, $vars = [], $class = NULL, $return = FALSE)
	{
		if ($class === NULL)
		{
			$class = uri('class');
		}
		$paths = [
			[uri('language'), ''],
			[uri('actor'), ''],
			in_array($class, config('uri')['table_list']) ? [$class, 'crud', ''] : [$class, ''],
		];
		if ( ! is_array($name_list))
		{
			$name_list = [$name_list];
		}
		foreach ($name_list as $name)
		{
			if (($fallback = fallback("{$name}.php", 'views', $paths)) !== NULL)
			{
				if ($return === TRUE)
				{
					ob_start();
					require $fallback;
					$buffer = ob_get_clean();
					return $buffer;
				}
				require $fallback;
				return;
			}
		}
		show_500("View '{$name}' not found (class='{$class}')");
	}
}

if ( ! function_exists('load_template'))
{
	// テンプレートを読み込む
	function load_template($view, $vars = [], $class = NULL, $return = FALSE)
	{
		if ($class === NULL)
		{
			$class = uri('class');
		}
		$paths = [
			[uri('language'), ''],
			[uri('actor'), ''],
			in_array($class, config('uri')['table_list']) ? [$class, 'crud', ''] : [$class, ''],
		];
		if (($fallback = fallback("template.php", 'views', $paths)) === NULL)
		{
			show_500("View 'template' not found (class='{$class}')");
		}
		if ($return === TRUE)
		{
			ob_start();
			require $fallback;
			$buffer = ob_get_clean();
			return $buffer;
		}
		require $fallback;
	}
}

if ( ! function_exists('h'))
{
	// 特殊文字をHTMLエンティティに変換し出力
	function h($string, $return = FALSE)
	{
		$value = htmlspecialchars($string);
		if ($return === FALSE)
		{
			echo $value;
		}
		return $value;
	}
}

if ( ! function_exists('r'))
{
	// 変数を解りやすく出力
	function r($expression, $return = FALSE)
	{
		if ($return === TRUE)
		{
			ob_start();
		}
		echo "<pre>\n";
		h(print_r($expression, TRUE));
		echo "</pre>\n";
		if ($return === TRUE)
		{
			return ob_get_clean();
		}
	}
}

if ( ! function_exists('l'))
{
	// 言語エントリーを出力
	function l($key, $params = [], $return = FALSE)
	{
		$value = isset($GLOBALS['language_hash'][$key][uri('language')]) ? $GLOBALS['language_hash'][$key][uri('language')] : $key;
		foreach ($params as $i => $param)
		{
			$value = str_replace('$'.($i + 1), $param, $value);
		}
		if ($return === FALSE)
		{
			echo $value;
		}
		return $value;
	}
}

if ( ! function_exists('site_url'))
{
	// サイトのURLを返す
	function site_url($uri = '', $language = TRUE, $actor = TRUE)
	{
		$prefix = '';
		if ($language === TRUE && uri('language') !== config('uri')['language_list'][0])
		{
			$prefix .= uri('language').'/';
		}
		if ($actor === TRUE && uri('actor') !== array_keys(config('uri')['actor_hash'])[0])
		{
			$prefix .= uri('actor').'/';
		}
		$uri = "{$prefix}{$uri}";
		$site_url = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
		$site_url .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		$site_url .= preg_replace('#/index\.php$#', "/{$uri}", $_SERVER['SCRIPT_NAME']);
		return $site_url;
	}
}

if ( ! function_exists('href'))
{
	// リンクを出力
	function href($uri = '', $language = TRUE, $actor = TRUE, $return = FALSE)
	{
		return h(site_url($uri, $language, $actor), $return);
	}
}

if ( ! function_exists('direct'))
{
	// ダイレクトリンクを出力
	function direct($name, $class = NULL)
	{
		if ($class === NULL)
		{
			$class = uri('class');
		}
		$paths = [
			['standalone', ''],
			[uri('language'), ''],
			[uri('actor'), ''],
			in_array($class, config('uri')['table_list']) ? [$class, 'crud', ''] : [$class, ''],
		];
		if (($fallback = fallback($name, 'direct', $paths)) === NULL)
		{
			show_500("'{$name}' not found (class='{$class}')");
		}
		h(site_url(preg_replace('#'.getcwd().'#', '', $fallback), FALSE, FALSE));
	}
}

if ( ! function_exists('redirect'))
{
	// リダイレクト
	function redirect($uri, $language = TRUE, $actor = TRUE)
	{
		header('Location: '.site_url($uri, $language, $actor));
		exit;
	}
}

if ( ! function_exists('flash'))
{
	// フラッシュメッセージを追加
	function flash($message, $class = 'info')
	{
		if ( ! isset($_SESSION['flash']))
		{
			$_SESSION['flash'] = [];
		}
		if (is_array($message) OR is_object($message))
		{
			$message = "<pre>\n".print_r($message, TRUE)."</pre>\n";
		}
		$_SESSION['flash'][] = [
			'message' => $message,
			'class' => $class,
		];
	}
}

if ( ! function_exists('is_ajax'))
{
	// Ajaxかどうかを返す
	function is_ajax()  
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}
}

if ( ! function_exists('is_cli'))
{
	// CLIかどうかを返す
	function is_cli()
	{
		return php_sapi_name() === 'cli';
	}
}

if ( ! function_exists('show_404'))
{
	// 404エラーを出力
	function show_404()
	{
		static $occurred = FALSE;
		ob_get_clean();
		if ($occurred === FALSE)
		{
			$occurred = TRUE;
		}
		else
		{
			die('Recursive Error Occurred.');
		}
		header('HTTP', TRUE, 404);
		load_template('404', [], 'errors');
		exit;
	}
}

if ( ! function_exists('show_500'))
{
	// 500エラーを出力
	function show_500($message)
	{
		static $occurred = FALSE;
		ob_get_clean();
		header('HTTP', TRUE, 500);
		if ($occurred === FALSE)
		{
			$occurred = TRUE;
		}
		else
		{
			die("Recursive Error Occurred. ({$message})");
		}
		load_template('500', ['message' => $message], 'errors');
		exit;
	}
}

if ( ! function_exists('send_mail'))
{
	// メールを送信
	function send_mail($params)
	{
		$pm = new PHPMailer();
		$pm->CharSet = 'UTF-8';
		$pm->Encoding = 'base64';
		$pm->setFrom(isset($params['from']) ? $params['from'] : config('mail')['from']);
		$pm->addAddress($params['address']);
		if (isset($params['cc']))
		{
			$pm->addCC($params['cc']);
		}
		if (isset($params['bcc']))
		{
			$pm->addBCC($params['bcc']);
		}
		$pm->Subject = $params['subject'];
		$pm->Body = $params['body'];
		$result = $pm->send();
		if ($result !== TRUE)
		{
			flash($pm->ErrorInfo, 'danger');
		}
		return $result;
	}
}

if ( ! function_exists('send_mail_admin'))
{
	// 管理者へメールを送信
	function send_mail_admin($params)
	{
		$params['address'] = config('mail')['admin'];
		return send_mail($params);
	}
}

if ( ! function_exists('uri'))
{
	// uriを返す
	function uri($key = NULL)
	{
		return $key === NULL ? $GLOBALS['uri'] : (isset($GLOBALS['uri'][$key]) ? $GLOBALS['uri'][$key] : NULL);
	}
}

if ( ! function_exists('config'))
{
	// 設定を返す
	function config($key = NULL)
	{
		return $key === NULL ? $GLOBALS['config'] : (isset($GLOBALS['config'][$key]) ? $GLOBALS['config'][$key] : NULL);
	}
}

if ( ! function_exists('model'))
{
	// モデルを返す
	function model($alias)
	{
		return isset($GLOBALS['models'][$alias]) ? $GLOBALS['models'][$alias] : NULL;
	}
}

if ( ! function_exists('library'))
{
	// モデルを返す
	function library($key = NULL)
	{
		return $key === NULL ? $GLOBALS['libraries'] : (isset($GLOBALS['libraries'][$key]) ? $GLOBALS['libraries'][$key] : NULL);
	}
}

if ( ! function_exists('thumbnail'))
{
	// サムネイルを出力
	function thumbnail($data, $size = 96, $return = FALSE)
	{
		$string = base64_decode(preg_replace('/^.*base64,/', '', $data));
		$src = imagecreatefromstring($string);
		$dst = imagecreatetruecolor($size, $size);
		$arr = getimagesizefromstring($string);
		$min = min($arr[0], $arr[1]);
		$src_x = $arr[0] >= $arr[1] ? ($arr[0] - $arr[1]) / 2 : 0;
		$src_y = $arr[1] >= $arr[0] ? ($arr[1] - $arr[0]) / 2 : 0;
		imagecopyresampled($dst, $src, 0, 0, $src_x, $src_y, $size, $size, $min, $min);
		ob_start();
		imagepng($dst);
		$output = 'data:image/png;base64,'.base64_encode(ob_get_clean());
		if ($return === FALSE)
		{
			echo $output;
		}
		return $output;
	}
}

if ( ! function_exists('log_message'))
{
	// ログを出力
	function log_message($message)
	{
		@mkdir('logs');
		if (is_object($message) OR is_array($message))
		{
			$message = print_r($message, TRUE);
		}
		file_put_contents('logs/'.date('Y-m-d').'.log', date('Y-m-d H:i:s').' '.uri('uri_string')."\n".$message."\n\n", FILE_APPEND);
	}
}
