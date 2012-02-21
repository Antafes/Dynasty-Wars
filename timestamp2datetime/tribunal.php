<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		tid,
		date,
		decision_date
	FROM dw_tribunal
';
$data = lib_util_mysqlQuery($sql, true);

foreach ($data as $row)
{
	$sql = '
		UPDATE dw_tribunal
		SET create_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['date'])).'",
			decision_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['decision_date'])).'"
		WHERE tid = '.mysql_real_escape_string($row['tid']).'
	';
	lib_util_mysqlQuery($sql);
}