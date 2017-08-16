<?php
class Docs extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	// トップページ
	public function index()
	{
		$this->page(__FUNCTION__);
	}

	// ページ表示
	public function page($view)
	{
		$buffer = load_template($view, [], uri('class'), TRUE);
		$buffer = preg_replace('#<code>./([^<]+)</code>#', '<code><a href="'.href('docs/file', TRUE, TRUE, TRUE).'/${1}">${1}</a></code>', $buffer);
		echo $buffer;
	}

	// ファイルビューア
	public function file()
	{
		$filename = implode('/', uri('param_arr'));
		$path = "vendor/sagittar-org/pieni2/{$filename}";
		if ( ! preg_match('#^'.getcwd().'/vendor/sagittar-org/pieni2#', realpath($path)))
		{
			show_404();
		}
		if (is_dir($path))
		{
			$vars['dir'] = $filename.($filename !== '' ? '/' : '');
			$vars['dir_list'] = [];
			$vars['file_list'] = [];
			foreach (scandir("vendor/sagittar-org/pieni2/{$vars['dir']}") as $filename)
			{
				if ( ! is_file("vendor/sagittar-org/pieni2/{$vars['dir']}{$filename}"))
				{
					if (in_array($filename, ['.', '..']))
					{
						continue;
					}
					$vars['dir_list'][] = $filename;
				}
				else
				{
					$vars['file_list'][] = $filename;
				}
			}
		}
		else
		{
			$vars['dir'] = dirname($filename).(dirname($filename) !== '.' ? '/' : '');
			$vars['contents'] = file_get_contents("vendor/sagittar-org/pieni2/{$filename}");
		}
		load_template(__FUNCTION__, $vars);
	}
}
