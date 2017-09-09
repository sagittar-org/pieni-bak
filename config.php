<?php
$config['uri']['language_list'] = [
	'en',
	'ja',
];
$config['uri']['actor_hash'] = [
	'g' => 'guest',
	'm' => 'member',
	'a' => 'admin',
];
$config['uri']['class_list'] = [
	'welcome',
	'inquiry',
	'auth',
	'spec',
];
$config['uri']['table_list'] = [
//	'file',
	'member',
	'admin',
	'directive',
];
$config['uri']['action_hash'] = [
	'index'    => 'index',
//	'download' => 'row',
	'proxy'    => 'row',
	'view'     => 'view',
	'add'      => 'add',
	'edit'     => 'edit',
	'delete'   => 'delete',
];
$config['uri']['alias_list'] = [
];

$config['auth']['m']['join']  = 'INSERT INTO `member` (`member_name`, `member_email`, `member_password`) values ("$name", "$email", "$password")';
$config['auth']['m']['login'] = 'SELECT `member_id` AS `id`, `member_name` AS `name`, `member_email` AS `email`, `member_password` AS `password` FROM `member` WHERE `member_email` = "$1"';
$config['auth']['m']['proxy'] = 'SELECT `member_id` AS `id`, `member_name` AS `name`, `member_email` AS `email` FROM `member` WHERE `member_id` = "$1"';
$config['auth']['a']['login'] = 'SELECT `admin_id` AS `id`, `admin_name` AS `name`, `admin_email` AS `email`, `admin_password` AS `password` FROM `admin` WHERE `admin_email` = "$1"';

$config['db']['debug'] = FALSE;
$config['db']['host'] = 'localhost';
$config['db']['username'] = 'root';
$config['db']['passwd'] = '';
$config['db']['dbname'] = 'pieni';

$config['mail']['from'] = 'noreply@localhost';
$config['mail']['admin'] = 'root@localhost';

$config['session']['name'] = 'pieni';

$config['password']['hash'] = TRUE;

$config['spec']['actor_list'] = [
	'a',
];
