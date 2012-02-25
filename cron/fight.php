<?php
require_once(dirname(__FILE__).'/../dw/lib/config.php');
require_once(dirname(__FILE__).'/../dw/lib/util/mysql.php');
require_once(dirname(__FILE__).'/../dw/lib/dal/unit.php');
require_once(dirname(__FILE__).'/../dw/lib/dal/troops.php');
require_once(dirname(__FILE__).'/../dw/lib/bl/unit.php');
require_once(dirname(__FILE__).'/../dw/lib/bl/troops.php');
require_once(dirname(__FILE__).'/../dw/lib/util/dateTime.php');
require_once(dirname(__FILE__).'/../dw/lib/util/dateInterval.php');

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die("Fehler, keine Datenbank!");

$moving_troops = dal\troops\getAllMovingTroops();

if ($moving_troops)
{
	foreach ($moving_troops as $moving_troop)
	{
		$endtime = \DWDateTime::createFromFormat('Y-m-d H:i:s', $moving_troop['endtime']);
		$now = new \DWDateTime();
		if ($endtime <= $now)
		{
			bl\troops\endMoving ($moving_troop['tid']);

			if ($moving_troop['type'] > 2)
			{
				bl\troops\fight($moving_troop['tid'], $moving_troop['tx'].':'.$moving_troop['ty']);
			}
		}
	}
}