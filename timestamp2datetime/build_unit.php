<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		tid,
		starttime,
		endtime
	FROM dw_build_unit
';
$data = util\mysql\query($sql, true);

foreach ($data as $row)
{
	$sql = '
		UPDATE dw_build_unit
		SET start_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['starttime'])).'",
			end_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['endtime'])).'"
		WHERE tid = '.mysql_real_escape_string($row['tid']).'
	';
	util\mysql\query($sql);
}