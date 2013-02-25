<?php
require_once(dirname(__FILE__).'/config.default.php');

$GLOBALS['db'] = array(
	'server' => 'localhost',
	'user' => 'root',
	'password' => 'd88e5c3',
	'db' => 'dwars',
	'charset' => 'utf8',
);

//enable/disable debug
$debug = true;
$firePHP_debug = false;
$smarty_debug = false;

//paths
$GLOBALS['config']['dir_ws'] = 'http://localhost/dw_git_dev/dw';
$GLOBALS['config']['dir_ws_index'] = 'http://localhost/dw_git_dev/dw/index.php';