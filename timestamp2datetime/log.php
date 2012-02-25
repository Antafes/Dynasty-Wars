<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		actid,
		date
	FROM dw_log
';
$logEntries = util\mysql\query($sql, true);

foreach ($logEntries as $logEntry)
{
	$sql = '
		UPDATE dw_log
		SET log_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $logEntry['date'])).'"
		WHERE actid = '.mysql_real_escape_string($logEntry['actid']).'
	';
	util\mysql\query($sql);
}