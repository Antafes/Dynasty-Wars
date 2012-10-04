<?php
//access data for the database
$GLOBALS['db'] = array(
	'server' => 'localhost',
	'user' => '',
	'password' => '',
	'db' => 'dwars',
	'charset' => 'utf8',
);

$GLOBALS['config']['charset'] = 'UTF-8';

//enable/disable debug
$debug = true;
$firePHP_debug = false;
$smarty_debug = false;

//paths
define('DIR_WS', 'http://dynasty-wars.de');
define('DIR_WS_INDEX', 'http://dynasty-wars.de/index.php');