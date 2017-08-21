<?php
// パッケージを指定
$config['package_list'] = [
	getcwd(),
	getcwd().'/vendor/sagittar-org/pieni',
];

// 全てのパッケージの設定ファイルを読み込む
foreach (array_reverse($config['package_list']) as $package)
{
	@include_once "{$package}/config.php";
}

// 全てのパッケージのcommonヘルパーを読み込む
foreach ($config['package_list'] as $package)
{
	@include_once "{$package}/helpers/common.php";
}

// リクエストを実行
exec_request($config);
