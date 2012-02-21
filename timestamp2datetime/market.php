<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		mid,
		timestamp
	FROM dw_market
';
$market_data = lib_util_mysqlQuery($sql, true);

foreach ($market_data as $row)
{
	$sql = '
		UPDATE dw_market
		SET create_datetime = "'.mysql_real_escape_string($row['timestamp'] != 1 ? date('Y-m-d H:i:s', $row['timestamp']) : 0).'"
		WHERE mid = '.mysql_real_escape_string($row['mid']).'
	';
	lib_util_mysqlQuery($sql);
}