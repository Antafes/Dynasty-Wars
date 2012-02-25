<?php
require_once(dirname(__FILE__).'/../dw/lib/config.php');
require_once(dirname(__FILE__).'/../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die("Fehler, keine Datenbank!");
$sql = 'SELECT uid FROM dw_user WHERE religion = 1';
$users = util\mysql\query($sql, true);

$maxMissionaries = ceil(count($users)/10);
$missionary = '';
for ($n = 1; $n <= $maxMissionaries; $n++)
{
	$rand = rand(0, count($users) - 1);
	if ($n == $maxMissionaries)
		$missionary = $users[$rand];
	else
		$missionary = $users[$rand].', ';
}

$sql = '
	INSERT INTO dw_missionary
	SET uid = '.util\mysql\sqlval($missionary).'
';
util\mysql\query($sql);