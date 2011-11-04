<?php
include_once (dirname(__FILE__).'/../dw/lib/config.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) or die("Fehler, keine Datenbank!");
$erg1 = mysql_query('SELECT uid FROM dw_user WHERE religion=1', $con);
if ($erg1)
	$user = mysql_num_rows($erg1);
$missionary = ceil($user/10);
unset($miss);
for ($n = 1; $n <= $missionary; $n++)
{
	$rand = rand(0, $user-1);
	if ($n == $missionary)
		$miss = mysql_result($erg1, $rand);
	else
		$miss = mysql_result($erg1, $rand).', ';
}
$erg2 = mysql_query('INSERT INTO dw_missionary (uid) VALUES ("'.$miss.'")', $con);
?>