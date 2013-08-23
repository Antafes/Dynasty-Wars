<?php
require_once(dirname(__FILE__)."/../dw/lib/config.php");
require_once(dirname(__FILE__).'/../dw/lib/util/mysql.php');

$GLOBALS['db']['db'] = 'INFORMATION_SCHEMA';

$sql = 'SELECT TABLE_NAME FROM TABLES WHERE TABLE_TYPE = "BASE TABLE"';
$tables = util\mysql\query($sql, true);

foreach ($tables as $table)
	util\mysql\query ('OPTIMIZE TABLE '.$table);