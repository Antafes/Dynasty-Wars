<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		uid,
		map_x,
		map_y,
		last_time
	FROM dw_res
';
$logEntries = util\mysql\query($sql, true);

foreach ($logEntries as $logEntry)
{
	$sql = '
		UPDATE dw_res
		SET last_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $logEntry['last_time'])).'"
		WHERE uid = '.mysql_real_escape_string($logEntry['uid']).'
			AND map_x = '.mysql_real_escape_string($logEntry['map_x']).'
			AND map_y = '.mysql_real_escape_string($logEntry['map_y']).'
	';
	util\mysql\query($sql);
}