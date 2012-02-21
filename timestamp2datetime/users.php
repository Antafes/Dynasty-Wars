<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		uid,
		regdate,
		last_login
	FROM dw_user
';
$data = lib_util_mysqlQuery($sql, true);

foreach ($data as $row)
{
	$sql = '
		UPDATE dw_user
		SET registration_datetime = "'.mysql_real_escape_string($row['regdate'] ? date('Y-m-d H:i:s', $row['regdate']) : 0).'",
			last_login_datetime = "'.mysql_real_escape_string($row['last_login'] ? date('Y-m-d H:i:s', $row['last_login']) : 0).'"
		WHERE uid = '.mysql_real_escape_string($row['uid']).'
	';
	lib_util_mysqlQuery($sql);
}