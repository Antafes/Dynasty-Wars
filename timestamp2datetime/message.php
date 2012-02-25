<?php
require_once('../dw/lib/config.php');
require_once('../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

$sql = '
	SELECT
		msgid,
		date,
		date_read
	FROM dw_message
';
$messages = util\mysql\query($sql, true);

foreach ($messages as $message)
{
	$sql = '
		UPDATE dw_message
		SET create_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $message['date'])).'",
			read_datetime = "'.mysql_real_escape_string($message['date_read'] ? date('Y-m-d H:i:s', $message['date_read']) : 0).'"
		WHERE msgid = '.mysql_real_escape_string($message['msgid']).'
	';
	util\mysql\query($sql);
}