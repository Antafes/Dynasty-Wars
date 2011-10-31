<?php

include_once ("/var/www/dw/htdocs/lib/config.php");
include_once ("/var/www/dw/htdocs/lib/bl/unit.php");
include_once ("/var/www/dw/htdocs/lib/dal/unit.php");
/*
include_once ("../dw/lib/config.php");
include_once ("../dw/lib/bl/unit.php");
include_once ("../dw/lib/dal/unit.php");
*/
$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) or die("Fehler, keine Datenbank!");
$time = time()-3600;
$sql = 'DELETE FROM dw_lostpw WHERE sent_time = '.$time.'';
mysql_query($sql, $con);
?>