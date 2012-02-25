#!/usr/bin/php5
<?php
	include('lib/config.php');
	include('lib/bl/general.inc.php');
	include('lib/bl/map.inc.php');

	$con = @mysql_connect($server, $seruser, $serpw);
	mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

	bl\map\drawMapImage('pictures/dynamic_map.png', 2);
?>
