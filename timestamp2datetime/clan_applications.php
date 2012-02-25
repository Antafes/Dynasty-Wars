<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		appid,
		apptime
	FROM dw_clan_applications
';
$data = util\mysql\query($sql, true);

foreach ($data as $row)
{
	$sql = '
		UPDATE dw_clan_applications
		SET create_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['apptime'])).'"
		WHERE appid = '.mysql_real_escape_string($row['appid']).'
	';
	util\mysql\query($sql);
}