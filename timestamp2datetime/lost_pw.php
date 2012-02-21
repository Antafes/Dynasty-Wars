<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		lpid,
		sent_time
	FROM dw_lostpw
';
$logEntries = lib_util_mysqlQuery($sql, true);

foreach ($logEntries as $logEntry)
{
	$sql = '
		UPDATE dw_lost_pw
		SET sent_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $logEntry['sent_time'])).'"
		WHERE lpid = '.mysql_real_escape_string($logEntry['lpid']).'
	';
	lib_util_mysqlQuery($sql);
}