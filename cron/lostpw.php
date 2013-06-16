<?php
require_once(dirname(__FILE__)."/../dw/lib/config.php");
require_once(dirname(__FILE__)."/../dw/lib/bl/unit.php");
require_once(dirname(__FILE__)."/../dw/lib/dal/unit.php");
require_once(dirname(__FILE__).'/../dw/lib/util/mysql.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die("Fehler, keine Datenbank!");
$time = time()-3600;
$sql = 'DELETE FROM dw_lostpw WHERE sent_time = '.util\mysql\sqlval($time).'';
util\mysql\query($sql);