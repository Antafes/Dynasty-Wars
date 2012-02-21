<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		bid,
		starttime,
		endtime
	FROM dw_build
';
$data = lib_util_mysqlQuery($sql, true);

foreach ($data as $row)
{
	$sql = '
		UPDATE dw_build
		SET start_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['starttime'])).'",
			end_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['endtime'])).'"
		WHERE bid = '.mysql_real_escape_string($row['bid']).'
	';
	lib_util_mysqlQuery($sql);
}