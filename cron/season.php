<?php

include_once ('/var/www/dw/htdocs/lib/config.php');
include_once ('/var/www/dw/htdocs/lib/bl/unit.php');
include_once ('/var/www/dw/htdocs/lib/dal/unit.php');
/*
include_once ('../dw/lib/config.php');
include_once ('../dw/lib/bl/unit.php');
include_once ('../dw/lib/dal/unit.php');
*/
$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) or die('Fehler, keine Datenbank!');
if (date('d.m.') == '1.10.')
	$sql = 'UPDATE `dw_game` SET `season` = 2';
elseif (date('d.m.') == '1.4.')
	$sql = 'UPDATE `dw_game` SET `season` = 1';
mysql_query($sql, $con);
?>