<?php
require_once(dirname(__FILE__).'/../dw/lib/config.php');
require_once(dirname(__FILE__).'/../dw/lib/util/mysql.php');

$sql = '
	SELECT
		uid,
		regdate,
		last_login
	FROM dw_user
';
$users = lib_util_mysqlQuery($sql, true);

foreach ($users as $user)
{
	$sql = '
		UPDATE dw_user
		SET registration_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $user['regdate'])).'",
			last_login_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $user['last_login'])).'"
		WHERE uid = '.mysql_real_escape_string($user['uid']).'
	';
	lib_util_mysqlQuery($sql);
}