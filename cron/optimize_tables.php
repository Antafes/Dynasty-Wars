<?php
require_once(dirname(__FILE__)."/../dw/lib/config.php");
require_once(dirname(__FILE__).'/../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db('INFORMATION_SCHEMA', $con) || die('Fehler, keine Datenbank!');

if($con)
{
	$sql = 'SELECT TABLE_NAME FROM TABLES WHERE TABLE_TYPE = "BASE TABLE"';
	$tables = util\mysql\query($sql, true);

	foreach ($tables as $table)
		util\mysql\query ('OPTIMIZE TABLE '.$table);
}