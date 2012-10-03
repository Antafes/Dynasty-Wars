<?php
//access data for the database
$GLOBALS['db'] = array(
	'server' => 'localhost',
	'user' => '',
	'password' => '',
	'db' => 'dwars',
);

//enable/disable debug
$debug = true;
$firePHP_debug = false;
$smarty_debug = false;

//paths
define(DIR_WS, 'http://localhost/dwars/dw');
define(DIR_WS_INDEX, 'http://localhost/dwars/dw/index.php');