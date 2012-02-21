<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		nid,
		date,
		last_changed
	FROM dw_news
';
$data = lib_util_mysqlQuery($sql, true);

foreach ($data as $row)
{
	$sql = '
		UPDATE dw_news
		SET create_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['date'])).'",
			changed_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['last_changed'])).'"
		WHERE nid = '.mysql_real_escape_string($row['nid']).'
	';
	lib_util_mysqlQuery($sql);
}