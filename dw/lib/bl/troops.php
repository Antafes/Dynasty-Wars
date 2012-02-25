<?php
namespace bl\troops;

/**
 * get the troop of the defined user
 * @author Neithan
 * @param int $tid
 * @return array
 */
function getTroop($tid)
{
	return \dal\troops\getTroop($tid);
}

/**
 * get the positions where troops/units are
 * @author Neithan
 * @param int $uid
 * @param int $kind
 * @return array
 */
function getPosition($uid, $kind="troops")
{
	$pos = \dal\troops\getPosition($uid, $kind);

	if ($pos)
	{
		foreach ($pos as &$part)
		{
			$part['x'] = $part['pos_x'];
			$part['y'] = $part['pos_y'];
			unset($part['pos_x'], $part['pos_y']);
		}
	}

	return $pos;
}

/**
 * get all troops/units from the definied user at a defined position
 * @author Neithan
 * @param int $uid
 * @param int $posx
 * @param int $posy
 * @param String $kind default 'troops'
 * @param bool $getAll default false
 * @param String $orderBy default 'unid'
 * @return array
 */
function getAtPosition($uid, $posx, $posy, $kind="troops", $getAll = false, $orderBy = 'unid')
{
	return \dal\troops\getAtPosition($uid, $posx, $posy, $kind, $getAll, $orderBy);
}

/**
 * get the units that are in this troop
 * @author Neithan
 * @param int $tid
 * @param String $orderBy default null
 * @return array
 */
function getTroopUnits($tid, $orderBy = null)
{
	return \dal\troops\getTroopUnits($tid, $orderBy);
}

/**
 * count the units that are in the troop
 * @author Neithan
 * @param int $tid
 * @return int
 */
function countTroopUnits($tid)
{
	$lines = getTroopUnits($tid);

	$count = 0;
	foreach ($lines as $line)
		$count += $line['count'];

	return \util\math\numberFormat($count, 0);
}

/**
 * add units to a troop
 * @author Neithan
 * @param int $unid
 * @param int $tid
 * @return int
 */
function addUnits($unid, $tid)
{
	return \dal\troops\addUnits($unid, $tid);
}

/**
 * add new units to an existing troop
 * @author Neithan
 * @param array $unids
 * @param int $tid
 * @return int returns 1 on succes, otherwise 0
 */
function addNewUnits($unids, $tid)
{
	$check = 0;

	foreach ($unids as $unid)
	{
		$troop = \dal\troops\getUnitCount($unid);
		$in_troop = \dal\troops\checkTroopUnits($troop['kind'], $tid);

		if ($in_troop)
		{
			$added = \dal\unit\train\addUnit($troop['count'], $in_troop);
			\dal\unit\deleteUnit($unid);
		}
		else
		{
			$added = addUnits($unid, $tid);
		}

		if ($added)
			$check++;
	}

	if ($check == count($unids))
		return 1;
	else
		return 0;
}

/**
 * create a new troop
 * @author Neithan
 * @param int $uid
 * @param int $posx
 * @param int $posy
 * @param array $unids
 * @param string $name
 * @return int return 1 on success, otherwise 0
 */
function createTroop($uid, $posx, $posy, $unids, $name)
{
	$tid = \dal\troops\createTroop($uid, $posx, $posy, $name);
	$newname = $name." ".$tid;
	\dal\troops\rename($tid, $newname);

	foreach ($unids as $unid)
		$added = addUnits($unid, $tid);

	if ($tid && $added)
		return 1;
	else
		return 0;
}

/**
 * check whether the units from one unid are all added to one troop or not
 * @author Neithan
 * @param int $unid
 * @param int $posx
 * @param int $posy
 * @param int $count
 * @param int $uid
 * @return int
 */
function checkComplete($unid, $posx, $posy, $count, $uid)
{
	$troop = \dal\troops\getUnitCount($unid);

	if ($count != $troop['count'])
	{
		$newunid = \dal\unit\train\checkPosition($uid, $posx, $posy, $troop['kind']);

		if (!$newunid)
			$newunid = \dal\unit\train\newUnit($uid, $troop['kind'], $count, $posx, $posy);
		else
			\dal\unit\train\addUnit($count, $newunid);

		\dal\troops\removeFromUNID($unid, $troop['count'] - $count);
		return $newunid;
	}
	else
		return $unid;
}

/**
 * check if attacks are possible
 * @author Neithan
 * @return int
 */
function checkCanAttack()
{
	return \dal\troops\checkCanAttack();
}

/**
 * check if there is a user on this position
 * @author Neithan
 * @param int $tx
 * @param int $ty
 * @return int
 */
function checkTarget($tx, $ty)
{
	return \dal\troops\checkTarget($tx, $ty);
}

/**
 * is the target in my clan?
 * @author Neithan
 * @param int $tuid
 * @param int $cid
 * @param int $type
 * @return int returns on 1 if the target is in the users clan, otherwise 0
 */
function checkTargetClan($tuid, $cid, $type)
{
	if ($type > 2)
	{
		$tcid = \dal\troops\checkTargetClan($tuid);

		if ($tcid == $cid)
			return 1;
		else
			return 0;
	}
	else
		return 0;
}

/**
 * insert the movement of the specified troop
 * @author Neithan
 * @param int $tid
 * @param int $tx
 * @param int $ty
 * @param int $type
 * @param string $res default ''
 * @param int $count default 0
 * @return int returns 1 on success, otherwise 0
 */
function sendTroop($tid, $tx, $ty, $type, $res='', $count=0)
{
	$troop = getTroop($tid);
//	$checkisle = checkIsle($posx, $posy, $tx, $ty);
//	if ($checkisle)
		$sendtime = \bl\unit\move\aStar($troop["pos_x"], $troop["pos_y"], $tx, $ty);
/*
	else
//not yet implemented
*/
	$endtime = new \DWDateTime();
	$endtime->add(new DateInterval('PT'.$sendtime.'S'));
	$erg1 = \dal\troops\sendTroop(intval($tid), intval($tx), intval($ty), intval($type), $endtime);

	if ($res && $count)
		\dal\troops\addResourceToTroop(intval($tid), $res, intval($count));

	if ($erg1)
		return 1;
	else
		return 0;
}

/**
 * check whether the troop wants to leave this island
 * @author Neithan
 * @param int $posx
 * @param int $posy
 * @param int $tx
 * @param int $ty
 * @return int returns 1 if the unit will leave the current isle, otherwise 0
 */
function checkIsle($posx, $posy, $tx, $ty)
{
	$targetIsle = \dal\troops\getIsle($tx, $ty);
	$currentIsle = \dal\troops\getIsle($posx, $posy);

	if ($targetIsle != $currentIsle)
		return 1;
	else
		return 0;
}

/**
 * check for moving troops
 * @author Neithan
 * @param int $tuid
 * @return array
 */
function checkTroops($tuid)
{
	$tids = \dal\troops\checkTroops($tuid);
	$GLOBALS['firePHP']->log($tids, 'checkTroops-TIDS');

	$return = array();
	if ($tids)
		foreach ($tids as $tid)
			$return[] = $tid['tid'];

	return $return;
}

/**
 * check the troop on movement
 * @author Neithan
 * @param int $tid
 * @return array
 */
function checkTroop($tid)
{
	$troop = \dal\troops\checkTroop($tid);

	if ($troop)
		$troop['end_datetime'] = \DWDateTime::createFromFormat('Y-m-d H:i:s', $troop['end_datetime']);

	return $troop;
}

/**
 * has the troop reached the target?
 * @author Neithan
 * @param int $tuid
 * @return int returns 1 if the target is reached, otherwise 0
 */
function checkMoving($tuid)
{
	$tids = checkTroops($tuid);
	$now = new \DWDateTime();

	if (count($tids) > 0)
	{
		foreach ($tids as $tid)
		{
			$moveinfo = checkTroop($tid);

			if ($moveinfo["end_datetime"] < $now && count($moveinfo) > 0)
			{
				\dal\troops\changeTroopPosition($tid, $moveinfo["tx"], $moveinfo["ty"]);
				\dal\troops\changeUnitsPosition($tid, $moveinfo["tx"], $moveinfo["tx"]);
				endMoving($tid);
			}
		}

		$tids2 = checkTroops($tuid);

		if (count($tids2) > 0)
			return 1;
		else
			return 0;
	}
	else
		return 0;
}

/**
 * remove the troop from dw_troops_move
 * @author Neithan
 * @param int $tid
 * @return int returns 1 on success, otherwise 0
 */
function endMoving($tid)
{
	$erg1 = \dal\troops\endMoving($tid);

	if ($erg1)
		return 1;
	else
		return 0;
}

/**
 * calculate the maximum transport capacity of this troop
 * @author Neithan
 * @param int $tid
 * @param bool $formatted default true
 * @return int
 */
function maxCapacity($tid, $formatted = true)
{
	$troop = getTroopUnits($tid);
	$cap = getCapacities();

	$maxCapacity = 0;
	foreach ($troop as $part)
		$maxCapacity += $cap[$part['kind']] * $part['count'];

	if ($formatted)
		return \util\math\numberFormat($maxCapacity, 0);
	else
		return $maxCapacity;
}

/**
 * get the capacities
 * @author Neithan
 * @return array
 */
function getCapacities()
{
	$cap0 = 10;
	$cap1 = 20;
	$cap2 = 30;
	$cap3 = 40;
	$cap4 = 50;
	$cap5 = 60;
	return array(
		1 => $cap4, 2 => $cap4, 3 => $cap0, 4 => $cap3, 5 => $cap0, 6 => $cap5, 7 => $cap3, 8 => $cap3, 9 => $cap1, 10 => $cap2,
		11 => $cap1, 12 => $cap3, 13 => $cap1, 14 => $cap2, 15 => $cap0, 16 => $cap1, 17 => $cap1, 18 => $cap0, 19 => $cap0
	);
}

/**
 * get the loaded resources
 * @author Neithan
 * @param int $tid
 * @return array
 */
function loaded($tid)
{
	return \dal\troops\loaded($tid);
}

/**
 * check if the maximum capacity of this troop is not enough
 * @author Neithan
 * @param int $capacity
 * @param int $tid
 * @return int returns 1 if there is less or equal load of the capacity, otherwise 0
 */
function checkCapacity($capacity, $tid)
{
	$load = loaded($tid);
	$maxCapacity = maxCapacity($tid);

	if ($capacity > $maxCapacity - $load['amount'])
		return 0;
	else
		return 1;
}

/**
 * change the name of this troop
 * @author Neithan
 * @param int $tid
 * @param string $name
 * @return void
 */
function rename($tid, $name)
{
	\dal\troops\rename(intval($tid), $name);
}

/**
 * delete the troop
 * @author Neithan
 * @param int $tid
 * @param int $uid
 * @return int returns 1 on success, otherwise 0
 */
function deleteTroop($tid, $uid)
{
	$troop = getTroop($tid);
	\dal\troops\deleteTroop($tid);
	$troop_units = getTroopUnits($tid);

	if (count($troop_units) > 0)
	{
		foreach ($troop_units as $unit)
		{
			$unid = \dal\unit\train\checkPosition($uid, $troop["pos_x"], $troop["pos_y"], $unit['kind']);

			if ($unid)
			{
				\dal\unit\train\addUnit($unit['count'], $unid);
				\dal\troops\deleteUnit($unit['unid']);
			}
		}

		\dal\troops\resetTID($tid);
		return 1;
	}
	else
		return 0;
}

/**
 * unload the troop
 * @author Neithan
 * @param int $tid the troops id
 * @param int $uid the users id
 * @param string $lng
 * @return int returns 1 on success, otherwise 0
 */
function unload($tid, $uid, $lng)
{
	$lang["lang"] = $lng;
	include ("language/".$lang["lang"]."/ingame/units.php");
	include ("language/".$lang["lang"]."/ingame/main.php");
	$troop = getTroop($tid);
	$tuid = \dal\user\getUIDFromMapPosition($troop["pos_x"], $troop["pos_y"]);
	\dal\resource\addToResources($troop["res"], $troop["amount"], $troop["pos_x"], $troop["pos_y"]);
	$erg1 = \dal\troops\addResourceToTroop($tid, "", 0);

	if ($erg1)
	{
		\bl\general\sendMessage(
			$uid,
			$tuid,
			$lang["unloadtitle"],
			sprintf(
				$lang["unloadmsg"],
				\dal\user\uid2nick($uid),
				$troop["amount"],
				$lang[$troop["res"]]
			),
			3
		);
		return 1;
	}
	else
		return 0;
}

/**
 * the fight script
 * @author Neithan
 * @param int $tid
 * @param string $target
 * @return array
 */
function fight($tid, $target, $type)
{
	global $lang;
	$target_exp = explode(':', $target);
	$troop = getTroop($tid);

	if ($troop['pos_x'].':'.$troop['pos_y'] != $target)
		return false;

	$target_uid = \dal\user\getUIDFromMapPosition($target_exp[0], $target_exp[1]);
	$target_units = getAtPosition($target_uid, $target_exp[0], $target_exp[1], 'units', true, 'kind');
	$groupedUnits = array(
		'target' => new GroupedUnits(),
		'attacker' => new GroupedUnits(),
	);

	$groupedUnits['target']->sortUnits($target_units);
	$groupedUnits['target']->setCityDefense($target_exp[0], $target_exp[1]);
	$groupedUnits['target']->setCityAttack($target_exp[0], $target_exp[1]);

	$attacker_units = getTroopUnits($tid, 'kind');
	$groupedUnits['attacker']->sortUnits($attacker_units);

	$attacker_speed = $groupedUnits['attacker']->getSpeed();
	$target_speed = $groupedUnits['target']->getSpeed();

	//calculate, which player hits first
	$init = rand(1, $attacker_speed + $target_speed);

	if ($init <= $attacker_speed)
		$init = 1;
	else
		$init = 0;

	//the order of unit types, which the units are attacking
	$attackOrder = array(
		'range' => array('near', 'range', 'rider'),
		'near' => array('rider', 'near', 'range'),
		'rider' => array('range', 'rider', 'near'),
	);

	$all_escaping_attacker = $all_escaping_target = true;

	//first hit
	$key = 'range';
	if ($init)
	{
		$city = array(
			'attack' => 0,
			'defense' => $groupedUnits['target']->getCityDefense(),
		);
		$all_escaping_attacker = false;

//		$r = attack($grouped_units['attacker']->$key, $key, $grouped_units['target']->$attack_order[$key][0], $attack_order[$key][0], $city);
//		$grouped_units['attacker']->$key = $r['attacker'];
//		$grouped_units['target']->$attack_order[$key][0] = $r['target'];

		for($i = 0; $i <= 2 && !$r['out']; $i++)
		{
			$r = attack($groupedUnits['attacker']->getUnitAmountByType($key), $key,
				$groupedUnits['target']->getUnitAmountByType($attackOrder[$key][$i]), $attackOrder[$key][$i], $city);
			$groupedUnits['attacker']->setUnitAmountByType($key, $r['attacker']);
			$groupedUnits['target']->setUnitAmountByType($attackOrder[$key][$i], $r['target']);
		}

//		if (!$r['out'])
//		{
//			$r = attack($grouped_units['attacker']->$key, $key, $grouped_units['target']->$attack_order[$key][1], $attack_order[$key][1], $city);
//			$grouped_units['attacker']->$key = $r['attacker'];
//			$grouped_units['target']->$attack_order[$key][1] = $r['target'];
//		}
//
//		if (!$r['out'])
//		{
//			$r = attack($grouped_units['attacker']->$key, $key, $grouped_units['target']->$attack_order[$key][2], $attack_order[$key][2], $city);
//			$grouped_units['attacker']->$key = $r['attacker'];
//			$grouped_units['target']->$attack_order[$key][2] = $r['target'];
//		}

		foreach ($r['lost_units'] as $lost_units_history)
			$groupedUnits['target']->addLostUnits(0, $lost_units_history);

		$all_escaping_target = $groupedUnits['target']->checkForAllEscaping();
	}
	else
	{
		$city = array(
			'attack' => $groupedUnits['target']->getCityAttack(),
			'defense' => 0,
		);
		$all_escaping_target = false;

		for($i = 0; $i <= 2 && !$r['out']; $i++)
		{
			$r = attack($groupedUnits['target']->getUnitAmountByType($key), $key,
				$groupedUnits['attacker']->getUnitAmountByType($attackOrder[$key][$i]), $attackOrder[$key][$i], $city);
			$groupedUnits['target']->setUnitAmountByType($key, $r['attacker']);
			$groupedUnits['attacker']->setUnitAmountByType($attackOrder[$key][$i], $r['target']);
		}

//		$r = attack($grouped_units['target']->$key, $key, $grouped_units['attacker']->$attack_order[$key][0], $attack_order[$key][0], $city);
//		$grouped_units['target']->$key = $r['attacker'];
//		$grouped_units['attacker']->$attack_order[$key][0] = $r['target'];
//		if (!$r['out'])
//		{
//			$r = attack($grouped_units['target']->$key, $key, $grouped_units['attacker']->$attack_order[$key][1], $attack_order[$key][1], $city);
//			$grouped_units['target']->$key = $r['attacker'];
//			$grouped_units['attacker']->$attack_order[$key][1] = $r['target'];
//		}
//		if (!$r['out'])
//		{
//			$r = attack($grouped_units['target']->$key, $key, $grouped_units['attacker']->$attack_order[$key][2], $attack_order[$key][2], $city);
//			$grouped_units['target']->$key = $r['attacker'];
//			$grouped_units['attacker']->$attack_order[$key][2] = $r['target'];
//		}

		foreach ($r['lost_units'] as $lost_units_history)
			$groupedUnits['attacker']->addLostUnits(0, $lost_units_history);

		$all_escaping_attacker = $groupedUnits['attacker']->checkForAllEscaping();
	}

	if (!$all_escaping_target && !$all_escaping_attacker)
	{
		//round based attacking
		$old_init = $init;
		for ($i = 1; $i <= 100 ;)
		{
			$all_escaping_attacker = $all_escaping_target = true;

			if ($init)
			{
				foreach ($groupedUnits['attacker'] as $key => &$attacker)
				{
					$city = array(
						'attack' => 0,
						'defense' => $groupedUnits['target']->getCityDefense(),
					);

					for($i = 0; $i <= 2 && !$r['out']; $i++)
					{
						$r = attack($groupedUnits['attacker']->getUnitAmountByType($key), $key,
							$groupedUnits['target']->getUnitAmountByType($attackOrder[$key][$i]), $attackOrder[$key][$i], $city);
						$groupedUnits['attacker']->setUnitAmountByType($key, $r['attacker']);
						$groupedUnits['target']->setUnitAmountByType($attackOrder[$key][$i], $r['target']);
					}

//					$r = attack($attacker, $key, $grouped_units['target']->$attack_order[$key][0], $attack_order[$key][0], $city);
//					$attacker = $r['attacker'];
//					$grouped_units['target']->$attack_order[$key][0] = $r['target'];
//					if (!$r['out'])
//					{
//						$r = attack($attacker, $key, $grouped_units['target']->$attack_order[$key][1], $attack_order[$key][1], $city);
//						$attacker = $r['attacker'];
//						$grouped_units['target']->$attack_order[$key][1] = $r['target'];
//					}
//					if (!$r['out'])
//					{
//						$r = attack($attacker, $key, $grouped_units['target']->$attack_order[$key][2], $attack_order[$key][2], $city);
//						$attacker = $r['attacker'];
//						$grouped_units['target']->$attack_order[$key][2] = $r['target'];
//					}

					foreach ($r['lost_units'] as $lost_units_history)
						$groupedUnits['target']->addLostUnits($i, $lost_units_history);

					$all_escaping_target = $groupedUnits['target']->checkForAllEscaping();

					if ($all_escaping_target)
						break 2;
				}
				unset($attacker);
				$init--;
			}
			else
			{
				foreach ($groupedUnits['target'] as $key => &$target)
				{
					$city = array(
						'attack' => $groupedUnits['target']->getCityAttack(),
						'defense' => 0,
					);

					for($i = 0; $i <= 2 && !$r['out']; $i++)
					{
						$r = attack($groupedUnits['target']->getUnitAmountByType($key), $key,
							$groupedUnits['attacker']->getUnitAmountByType($attackOrder[$key][$i]), $attackOrder[$key][$i], $city);
						$groupedUnits['target']->setUnitAmountByType($key, $r['attacker']);
						$groupedUnits['attacker']->setUnitAmountByType($attackOrder[$key][$i], $r['target']);
					}

//					$r = attack($target, $key, $grouped_units['attacker']->$attack_order[$key][0], $attack_order[$key][0], $city);
//					$target = $r['attacker'];
//					$grouped_units['attacker']->$attack_order[$key][0] = $r['target'];
//					if (!$r['out'])
//					{
//						$r = attack($target, $key, $grouped_units['attacker']->$attack_order[$key][1], $attack_order[$key][1], $city);
//						$target = $r['attacker'];
//						$grouped_units['attacker']->$attack_order[$key][1] = $r['target'];
//					}
//					if (!$r['out'])
//					{
//						$r = attack($target, $key, $grouped_units['attacker']->$attack_order[$key][2], $attack_order[$key][2], $city);
//						$target = $r['attacker'];
//						$grouped_units['attacker']->$attack_order[$key][2] = $r['target'];
//					}

					foreach ($r['lost_units'] as $lost_units_history)
						$groupedUnits['attacker']->addLostUnits($i, $lost_units_history);

					$all_escaping_attacker = $groupedUnits['attacker']->checkForAllEscaping();

					if ($all_escaping_attacker)
						break 2;
				}
				unset($target);
				$init++;
				$i++;
			}
		}
	}
	$all_escaping_attacker = $groupedUnits['attacker']->checkForAllEscaping();

	$all_escaping_target = $groupedUnits['target']->checkForAllEscaping();

	//creating the fight result message
	$attacker_lost_units = $groupedUnits['attacker']->getLostUnits();
	$target_lost_units = $groupedUnits['target']->getLostUnits();

/* yet not used
	if ($old_init)
		$count = count($attacker_lost_units);
	else
		$count = count($target_lost_units);
*/
	if ($type == 4)
	{
		/**
		 * @TODO: calculate the robbed ressources
		 */
	}

	$msg_html = '[html]';
	$msg_html .= '<div class="fight_result">';
	$msg_html .= drawFightMessageRow($lang['unit'], true);
	$msg_html .= drawFightMessageRow($attacker_units);
	$msg_html .= drawFightMessageRow($attacker_lost_units);
	$msg_html .= '</div>';
	$msg_html .= '[/html]';

	if ($groupedUnits['target']->getAllUnitsDead())
		$result = $lang['fight']['glorious_win'];
	elseif ($all_escaping_target)
		$result = $lang['fight']['win'];
	elseif ($groupedUnits['attacker']->getAllUnitsDead())
		$result = $lang['fight']['smash'];
	elseif ($all_escaping_attacker)
		$result = $lang['fight']['lose'];

	\bl\general\sendMessage(-1, $troop['uid'], $lang['fight']['topic'], sprintf(
		$lang['fight']['message'],
		\dal\user\uid2nick($troop['uid']),
		\dal\user\uid2nick($target_uid),
		$result,
		$msg_html
	), 4);

	$msg_html = '[html]';
	$msg_html .= '<div class="fight_result">';
	$msg_html .= drawFightMessageRow($lang['unit'], true);
	$msg_html .= drawFightMessageRow($target_units);
	$msg_html .= drawFightMessageRow($target_lost_units);
	$msg_html .= '</div>';
	$msg_html .= '[/html]';

	if ($groupedUnits['attacker']->getAllUnitsDead())
		$result = $lang['fight']['glorious_win'];
	elseif ($all_escaping_attacker)
		$result = $lang['fight']['win'];
	elseif ($groupedUnits['target']->getAllUnitsDead())
		$result = $lang['fight']['smash'];
	elseif ($all_escaping_target)
		$result = $lang['fight']['lose'];

	\bl\general\sendMessage(-1, $target_uid, $lang['fight']['topic'], sprintf(
		$lang['fight']['message'],
		\dal\user\uid2nick($target_uid),
		\dal\user\uid2nick($troop['uid']),
		$result,
		$msg_html
	), 4);

	$groupedUnits['attacker']->reduceUnits();
	$groupedUnits['target']->reduceUnits();

	return array('attacker' => ($all_escaping_attacker ? false : true), 'target' => ($all_escaping_target ? false : true));
}

/**
 * @author Neithan
 * @param object $attacker
 * @param string $attacker_type
 * @param object $target
 * @param string $target_type
 * @param array $city
 * @return array
 */
function attack($attacker, $attacker_type, $target, $target_type, $city)
{
	$out = false;

	switch ($attacker_type)
	{
		case 'range':
		{
			$fight_result = unitFight($attacker, $target, $city);
			break;
		}
		case 'near':
		{
			$fight_result = unitFight($attacker, $target, $city);
			break;
		}
		case 'rider':
		{
			$fight_result = unitFight($attacker, $target, $city);
			break;
		}
	}

	return $fight_result;
}

/**
 * calculating the fight, unit against unit
 * @author Neithan
 * @param object $attacker
 * @param object $target
 * @return array
 */
function unitFight($attacker, $target, $city)
{
	$out = false;
	$lost_units_history = array();

	if (is_array($attacker) && count($attacker) > 0)
	{
		if (is_array($target) && count($target) > 0)
		{
			foreach ($attacker as $attacker_key => &$attacker_unit)
			{
				if  ($attacker_key !== 'total_count')
				{
					$hit = 0;
					foreach ($target as &$target_unit)
					{
						if ($target_unit['count'] > 0)
						{
							$defense = (int) round($target_unit['stats']['armor'] + $target_unit['stats']['agility'] * 0.35, 0);
							$defense += $city['defense'];

							if ($target_unit['stats']['moral'] <= $target_unit['min_moral'] * 3)
								$defense = (int) round($defense / 1.5, 0);
							elseif ($target_unit['stats']['moral'] <= $target_unit['min_moral'] * 2)
								$defense = (int) round($defense / 2, 0);

							$attack = (int) round($attacker_unit['stats']['strength'] + ($attacker_unit['stats']['agility'] * 0.35), 0);
							$attack += $city['attack'];

							if ($attacker_unit['stats']['moral'] <= $attacker_unit['min_moral'] * 3)
								$attack = (int) round($attack / 1.5, 0);
							elseif ($attacker_unit['stats']['moral'] <= $attacker_unit['min_moral'] * 2)
								$attack = (int) round($attack / 2, 0);

							$hit_percentage = round($attack / ($attack + $defense), 2);

							$hit += (int) round($attack * $hit_percentage, 0);
						}
					}
					unset($target_unit);

					if ($hit > 0)
					{
						$hit = (int) ($hit * (rand(0, 100) / 100));
						$splitter = count($target);

						for ($i = 0, $h = 100, $split = array(); $i < $splitter; $i++)
						{
							$split[$i] = rand(1, $h);
							$h -= $split[$i];
						}

						$i = 0;
						$lost_units = 0;
						foreach ($target as $key => &$target_unit)
						{
							if ($key !== 'total_count' && $target_unit['count'] > 0)
							{
								$stats = \dal\troops\getUnitStats($target_unit['kind']);

								if (!$target_unit['escaping'])
								{
									$min_moral = $target_unit['min_moral'];
									$single_hit = (int) round($hit * ($split[$i] / 100));

									$moral_loss = (int) round($single_hit * ($stats['moral'] * (rand(25, 75) / 100)), 0);
									$unit_loss = (int) round($moral_loss / $stats['moral'], 0);

									if ($unit_loss > $target_unit['count'])
									{
										$unit_loss = $target_unit['count'];
										$moral_loss = $target_unit['stats']['moral'];
									}

									$lost_units_history[] = array(
										'unid' => $target_unit['unid'],
										'kind' => $target_unit['kind'],
										'lost_units' => $unit_loss,
									);

									$target_unit['count'] -= $unit_loss;
									$lost_units += $unit_loss;
									$target_unit['stats']['moral'] -= $moral_loss;

									if ($target_unit['stats']['moral'] < $min_moral)
										$target_unit['escaping'] = true;
								}
								$i++;
								if (!$out)
									$out = true;
							}
						}
						unset($target_unit);
					}
				}
			}
			unset($attacker_unit);
		}
	}

	return array(
		'out'  => $out,
		'target' => $target,
		'attacker' => $attacker,
		'lost_units' => $lost_units_history,
		'total_lost_units' => $lost_units,
	);
}

/**
 *
 * @author Neithan
 * @param array $valuelist
 * @param boolean $is_lang
 * @return string
 */
function drawFightMessageRow($valuelist, $is_lang = false)
{
	$html = '<div class="row">';
	for ($i = 0; $i < 19; $i++)
	{
		$html .= '<div class="unit left">';

		if (!$is_lang)
		{
			if ($valuelist[$i]['count'] > 0)
				$html .= $valuelist[$i]['count'];
			else
				$html .= 0;
		}
		else
		{
			if ($valuelist[$i + 1])
				$html .= $valuelist[$i + 1];
			else
				$html .= '';
		}

		$html .= '</div>';
	}

	$html .= '</div>';

	return $html;
}

/**
 * grouping of the units
 * @author Neithan
 */
class GroupedUnits
{
	private $near = array();
	private $range = array();
	private $rider = array();
	private $unit_groups = array(
		'near' => array(2, 3, 5, 6, 7, 11, 12, 13, 14, 18),
		'range' => array(1, 4, 9, 16, 17),
		'rider' =>array(8, 10, 15, 19),
	);
	private $lost_units = array();
	private $city_defense = 0;
	private $city_attack = 0;
	private $factor = 0.02;

	/**
	 * add a unit to the specified group
	 * @author Neithan
	 * @param int $kind group of the unit
	 * @param array $unit the unit with all stats
	 */
	function add($kind, $unit)
	{
		$unit['min_moral'] = (int) round($unit['stats']['moral'] * 0.25, 0);
		if ($kind == 'near')
			$this->near[] = $unit;
		elseif ($kind == 'range')
			$this->range[] = $unit;
		elseif ($kind == 'rider')
			$this->rider[] = $unit;
	}

	/**
	 * sort the units in this list
	 * @author Neithan
	 * @param array $valuelist list of units
	 */
	function sortUnits($valuelist)
	{
		if (is_array($valuelist))
		{
			foreach ($valuelist as $unit)
			{
				$type = '';
				if (in_array($unit['kind'], $this->unit_groups['near']))
					$type = 'near';
				elseif (in_array($unit['kind'], $this->unit_groups['range']))
					$type = 'range';
				elseif (in_array($unit['kind'], $this->unit_groups['rider']))
					$type = 'rider';

				$unit['stats'] = \dal\troops\getUnitStats($unit['kind']);

				foreach ($unit['stats'] as $key => &$stat)
					if ($key != 'usid')
						$stat = $stat * $unit['count'];

				$unit['escaping'] = false;

				$this->add($type, $unit);
			}
		}
	}

	/**
	 * calculate the single speed of this units
	 * @author Neithan
	 * @return int
	 */
	function getSpeed()
	{
		$speed = 0;

		foreach ($this as $key => $value)
		{
			if ($key !== 'unit_groups' && $key !== 'lost_units' && $key !== 'city_defense' && $key !== 'city_attack' && $key !== 'factor')
			{
				foreach ($value as $key2 => $part)
				{
					$stats = \dal\troops\getUnitStats($part['kind']);
					$speed += (int) $stats['speed'];
				}
			}
		}

		return $speed;
	}

	/**
	 * @author Neithan
	 * @return array
	 */
	function checkForEscaping()
	{
		$near = $range = $rider = 0;
		foreach ($this as $key => $value)
		{
			if ($key == 'near' && $key == 'range' && $key == 'rider' && count($value) > 0)
			{
				foreach ($value as $key2 => $part)
				{
					if ($part['escaping'] || $part['count'] == 0)
						$$key++;
				}
			}
			elseif ($key !== 'unit_groups' && count($value) === 0 && $$key === 0)
				$$key--;
		}

		return array(
			'near' => ($near == count($this->near) - 1 ? true : false),
			'range' => ($range == count($this->range) - 1 ? true : false),
			'rider' => ($rider == count($this->rider) - 1 ? true : false),
		);
	}

	/**
	 * @author Neithan
	 * @return boolean
	 */
	function checkForAllEscaping()
	{
		$all_escaping = true;
		$escaping = $this->checkForEscaping();

		foreach ($escaping as $part)
			if (!$part)
				$all_escaping = false;

		return $all_escaping;
	}

	/**
	 * @author Neithan
	 * @param array $lost
	 */
	function addLostUnits($r, $lost)
	{
		$this->lost_units[$r] = $lost;
	}

	/**
	 * @author Neithan
	 * @return array
	 */
	function getLostUnits()
	{
		return $this->lost_units;
	}

	/**
	 * @author Neithan
	 * @param int $x
	 * @param int $y
	 */
	function setCityDefense($x, $y)
	{
		$defense_buildings = \dal\buildings\getDefense($x, $y);

		$this->city_defense = (int) 75;

		foreach ($defense_buildings as $defense)
		{
			$values = \dal\buildings\getStats($defense['kind'], $defense['upgrade_lvl']);
			$this->city_defense += (int) $values['defense'] + ($defense['lvl'] * $values['defense'] * $this->factor);
		}
	}

	/**
	 * @author Neithan
	 * @return int
	 */
	function getCityDefense()
	{
		return $this->city_defense;
	}

	/**
	 * @author Neithan
	 * @param int $x
	 * @param int $y
	 */
	function setCityAttack($x, $y)
	{
		$defense_buildings = \dal\buildings\getDefense($x, $y);

		foreach ($defense_buildings as $defense)
		{
			$values = \dal\buildings\getStats($defense['kind'], $defense['upgrade_lvl']);
			$this->city_attack += (int) $values['attack'] + ($defense['lvl'] * $values['attack'] * $this->factor);
		}
	}

	/**
	 * @author Neithan
	 * @return int
	 */
	function getCityAttack()
	{
		return $this->city_attack;
	}

	/**
	 * sets all unit counts to the result of the fight
	 * @author Neithan
	 */
	function reduceUnits()
	{
		$units = array_merge($this->near, $this->range, $this->rider);

		foreach ($units as $unit)
		{
			if ($unit['count'] > 0)
				\dal\troops\updateUnitCount($unit['unid'], $unit['count']);
			else
				\dal\troops\deleteUnit($unit['unid']);
		}
	}

	/**
	 * @author Neithan
	 * @return boolean
	 */
	function getAllUnitsDead()
	{
		$units = array_merge($this->near,  $this->range, $this->rider);

		$all_dead = true;
		foreach ($units as $unit)
			if ($unit['count'] > 0)
				$all_dead = false;

		return $all_dead;
	}

	/**
	 * get the current amount of units of the specified type
	 * @author Neithan
	 * @param String $type
	 * @return int
	 */
	function getUnitAmountByType($type)
	{
		return $this->$type;
	}

	/**
	 * set the amount of units of the specified type
	 * @author Neithan
	 * @param String $type
	 * @param int $amount
	 */
	function setUnitAmountByType($type, $amount)
	{
		$this->$type = $amount;
	}
}

/**
 * remove a unit from the troop
 * @author Neithan
 * @param int $unid
 * @return int
 */
function removeFromTroop($unid)
{
	return \dal\troops\removeFromTroop($unid);
}