<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		tmid,
		endtime
	FROM dw_troops_move
';
$logEntries = util\mysql\query($sql, true);

foreach ($logEntries as $logEntry)
{
	$sql = '
		UPDATE dw_troops_move
		SET end_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $logEntry['endtime'])).'"
		WHERE tmid = '.mysql_real_escape_string($logEntry['tmid']).'
	';
	util\mysql\query($sql);
}