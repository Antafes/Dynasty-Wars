<?php
include_once (dirname(__FILE__)."/../dw/lib/config.php");

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db('INFORMATION_SCHEMA', $con) or die('Fehler, keine Datenbank!');
if($con)
{
	$tables = mysql_query('SELECT TABLE_NAME FROM TABLES WHERE TABLE_TYPE = "BASE TABLE"', $con);
	while($row = mysql_fetch_object($tables))
		mysql_query('OPTIMIZE TABLE '.$row->TABLE_NAME);
	mysql_free_result($tables);
}


?>