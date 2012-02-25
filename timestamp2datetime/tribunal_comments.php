<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		tcoid,
		date_added,
		date_last_changed
	FROM dw_tribunal_comments
';
$data = util\mysql\query($sql, true);

foreach ($data as $row)
{
	$sql = '
		UPDATE dw_tribunal_comments
		SET create_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['date_added'])).'",
			changed_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['date_last_changed'])).'"
		WHERE tcoid = '.mysql_real_escape_string($row['tcoid']).'
	';
	util\mysql\query($sql);
}