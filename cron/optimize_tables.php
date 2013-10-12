<?php
require_once(dirname(__FILE__)."/../dw/lib/config.php");
require_once(dirname(__FILE__).'/../dw/lib/util/mysql.php');

$sql = 'USE INFORMATION_SCHEMA';
util\mysql\query($sql);

$sql = 'SELECT TABLE_NAME FROM TABLES WHERE TABLE_TYPE = "BASE TABLE"';
$tables = util\mysql\query($sql, true);

foreach ($tables as $table)
	util\mysql\query ('OPTIMIZE TABLE '.$table);

$sql = 'USE '.$GLOBALS['db']['db'];
util\mysql\query($sql);