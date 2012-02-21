<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		aid,
		date_added
	FROM dw_tribunal_arguments
';
$logEntries = lib_util_mysqlQuery($sql, true);

foreach ($logEntries as $logEntry)
{
	$sql = '
		UPDATE dw_tribunal_arguments
		SET added_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $logEntry['date_added'])).'"
		WHERE aid = '.mysql_real_escape_string($logEntry['aid']).'
	';
	lib_util_mysqlQuery($sql);
}