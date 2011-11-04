<?php
include_once (dirname(__FILE__)."../dw/lib/config.php");
include_once (dirname(__FILE__).'../dw/lib/util/mysql.php');
include_once (dirname(__FILE__)."../dw/lib/dal/unit.php");
include_once (dirname(__FILE__)."../dw/lib/dal/troops.php");
include_once (dirname(__FILE__)."../dw/lib/bl/unit.php");
include_once (dirname(__FILE__)."../dw/lib/bl/troops.php");

$moving_troops = lib_dal_troops_getAllMovingTroops();
$time = time();

foreach ($moving_troops as $moving_troop)
{
	if ($moving_troop['endtime'] <= $time)
	{
		lib_bl_troops_endMoving ($moving_troop['tid']);

		if ($moving_troop['type'] > 2)
		{
			lib_bl_troops_fight($moving_troop['tid'], $moving_troop['tx'].':'.$moving_troop['ty']);
		}
	}
}