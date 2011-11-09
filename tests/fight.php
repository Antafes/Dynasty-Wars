<?php
$path = '../dw/';
include($path.'lib/config.php');
include($path.'lib/util/mysql.php');
include($path.'lib/dal/troops.php');
include($path.'lib/dal/user.php');
include($path.'lib/dal/buildings.php');
include($path.'lib/bl/troops.php');
include($path.'language/de/ingame/messages.php');
include($path.'language/de/ingame/units.php');
//echo '<pre>'; var_dump($lang); echo '</pre>';

$con = mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb) || die('bäh kein bock');

for ($x = 0; $x < 20; $x++)
{
	$winner = lib_bl_troops_fight(32, '378:35');
	echo 'Gewinner '.$x.': ';
	if ($winner['attacker'])
		echo 'Angreifer';
	elseif ($winner['target'])
		echo 'Verteidiger';
	else
	{
		echo '<pre>'; var_dump($winner); echo '</pre>';
	}
	echo '<br>';
}

function lib_bl_troops_fight($tid, $target)
{
	global $lang;
	$target_exp = explode(':', $target);
	$troop = lib_bl_troops_getTroop($tid);
/*
	if ($troop['pos_x'].':'.$troop['pos_y'] != $target)
		return false;
*/
	$target_uid = lib_dal_user_getUIDFromMapPosition($target_exp[0], $target_exp[1]);
	$target_units = lib_bl_troops_getAtPos($target_uid, $target_exp[0], $target_exp[1], 'units', true, 'kind');
	$grouped_units = array(
		'target' => new grouped_units(),
		'attacker' => new grouped_units(),
	);

	$grouped_units['target']->sortUnits($target_units);
	$grouped_units['target']->setCityDefense($target_exp[0], $target_exp[1]);
	$grouped_units['target']->setCityAttack($target_exp[0], $target_exp[1]);

	$attacker_units = lib_bl_troops_getTroopUnits($tid, 'kind');
	$grouped_units['attacker']->sortUnits($attacker_units);

	$attacker_speed = $grouped_units['attacker']->getSpeed();
	$target_speed = $grouped_units['target']->getSpeed();

	//calculate, which player hits first
	$init = rand(1, $attacker_speed + $target_speed);

	if ($init <= $attacker_speed)
		$init = 1;
	else
		$init = 0;

	//the order of unit types, which the units are attacking
	$attack_order = array(
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
			'defense' => $grouped_units['target']->getCityDefense(),
		);
		$all_escaping_attacker = false;
/*
		$r = lib_bl_troops_attack($grouped_units['attacker']->$key, $key, $grouped_units['target']->$attack_order[$key][0], $attack_order[$key][0], $city);
		$grouped_units['attacker']->$key = $r['attacker'];
		$grouped_units['target']->$attack_order[$key][0] = $r['target'];
*/
		for($i = 0; $i <= 2 && !$r['out']; $i++)
		{
			$r = lib_bl_troops_attack($grouped_units['attacker']->$key, $key, $grouped_units['target']->$attack_order[$key][$i], $attack_order[$key][$i], $city);
			$grouped_units['attacker']->$key = $r['attacker'];
			$grouped_units['target']->$attack_order[$key][$i] = $r['target'];
		}
/*
		if (!$r['out'])
		{
			$r = lib_bl_troops_attack($grouped_units['attacker']->$key, $key, $grouped_units['target']->$attack_order[$key][1], $attack_order[$key][1], $city);
			$grouped_units['attacker']->$key = $r['attacker'];
			$grouped_units['target']->$attack_order[$key][1] = $r['target'];
		}

		if (!$r['out'])
		{
			$r = lib_bl_troops_attack($grouped_units['attacker']->$key, $key, $grouped_units['target']->$attack_order[$key][2], $attack_order[$key][2], $city);
			$grouped_units['attacker']->$key = $r['attacker'];
			$grouped_units['target']->$attack_order[$key][2] = $r['target'];
		}
*/
		foreach ($r['lost_units'] as $lost_units_history)
			$grouped_units['target']->addLostUnits(0, $lost_units_history);

		$all_escaping_target = $grouped_units['target']->checkForAllEscaping();
	}
	else
	{
		$city = array(
			'attack' => $grouped_units['target']->getCityAttack(),
			'defense' => 0,
		);
		$all_escaping_target = false;

		for($i = 0; $i <= 2 && !$r['out']; $i++)
		{
			$r = lib_bl_troops_attack($grouped_units['target']->$key, $key, $grouped_units['attacker']->$attack_order[$key][$i], $attack_order[$key][$i], $city);
			$grouped_units['target']->$key = $r['target'];
			$grouped_units['attacker']->$attack_order[$key][$i] = $r['attacker'];
		}
/*
		$r = lib_bl_troops_attack($grouped_units['target']->$key, $key, $grouped_units['attacker']->$attack_order[$key][0], $attack_order[$key][0], $city);
		$grouped_units['target']->$key = $r['attacker'];
		$grouped_units['attacker']->$attack_order[$key][0] = $r['target'];
		if (!$r['out'])
		{
			$r = lib_bl_troops_attack($grouped_units['target']->$key, $key, $grouped_units['attacker']->$attack_order[$key][1], $attack_order[$key][1], $city);
			$grouped_units['target']->$key = $r['attacker'];
			$grouped_units['attacker']->$attack_order[$key][1] = $r['target'];
		}
		if (!$r['out'])
		{
			$r = lib_bl_troops_attack($grouped_units['target']->$key, $key, $grouped_units['attacker']->$attack_order[$key][2], $attack_order[$key][2], $city);
			$grouped_units['target']->$key = $r['attacker'];
			$grouped_units['attacker']->$attack_order[$key][2] = $r['target'];
		}
*/
		foreach ($r['lost_units'] as $lost_units_history)
			$grouped_units['attacker']->addLostUnits(0, $lost_units_history);

		$all_escaping_attacker = $grouped_units['attacker']->checkForAllEscaping();
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
				foreach ($grouped_units['attacker'] as $key => &$attacker)
				{
					$city = array(
						'attack' => 0,
						'defense' => $grouped_units['target']->getCityDefense(),
					);

					for($i = 0; $i <= 2 && !$r['out']; $i++)
					{
						$r = lib_bl_troops_attack($grouped_units['attacker']->$key, $key, $grouped_units['target']->$attack_order[$key][$i], $attack_order[$key][$i], $city);
						$grouped_units['attacker']->$key = $r['attacker'];
						$grouped_units['target']->$attack_order[$key][$i] = $r['target'];
					}
/*
					$r = lib_bl_troops_attack($attacker, $key, $grouped_units['target']->$attack_order[$key][0], $attack_order[$key][0], $city);
					$attacker = $r['attacker'];
					$grouped_units['target']->$attack_order[$key][0] = $r['target'];
					if (!$r['out'])
					{
						$r = lib_bl_troops_attack($attacker, $key, $grouped_units['target']->$attack_order[$key][1], $attack_order[$key][1], $city);
						$attacker = $r['attacker'];
						$grouped_units['target']->$attack_order[$key][1] = $r['target'];
					}
					if (!$r['out'])
					{
						$r = lib_bl_troops_attack($attacker, $key, $grouped_units['target']->$attack_order[$key][2], $attack_order[$key][2], $city);
						$attacker = $r['attacker'];
						$grouped_units['target']->$attack_order[$key][2] = $r['target'];
					}
*/
					foreach ($r['lost_units'] as $lost_units_history)
						$grouped_units['target']->addLostUnits($i, $lost_units_history);

					$all_escaping_target = $grouped_units['target']->checkForAllEscaping();

					if ($all_escaping_target)
						break 2;
				}
				unset($attacker);
				$init--;
			}
			else
			{
				foreach ($grouped_units['target'] as $key => &$target)
				{
					$city = array(
						'attack' => $grouped_units['target']->getCityAttack(),
						'defense' => 0,
					);

					for($i = 0; $i <= 2 && !$r['out']; $i++)
					{
						$r = lib_bl_troops_attack($grouped_units['target']->$key, $key, $grouped_units['attacker']->$attack_order[$key][$i], $attack_order[$key][$i], $city);
						$grouped_units['target']->$key = $r['target'];
						$grouped_units['attacker']->$attack_order[$key][$i] = $r['attacker'];
					}
/*
					$r = lib_bl_troops_attack($target, $key, $grouped_units['attacker']->$attack_order[$key][0], $attack_order[$key][0], $city);
					$target = $r['attacker'];
					$grouped_units['attacker']->$attack_order[$key][0] = $r['target'];
					if (!$r['out'])
					{
						$r = lib_bl_troops_attack($target, $key, $grouped_units['attacker']->$attack_order[$key][1], $attack_order[$key][1], $city);
						$target = $r['attacker'];
						$grouped_units['attacker']->$attack_order[$key][1] = $r['target'];
					}
					if (!$r['out'])
					{
						$r = lib_bl_troops_attack($target, $key, $grouped_units['attacker']->$attack_order[$key][2], $attack_order[$key][2], $city);
						$target = $r['attacker'];
						$grouped_units['attacker']->$attack_order[$key][2] = $r['target'];
					}
*/
					foreach ($r['lost_units'] as $lost_units_history)
						$grouped_units['attacker']->addLostUnits($i, $lost_units_history);

					$all_escaping_attacker = $grouped_units['attacker']->checkForAllEscaping();

					if ($all_escaping_attacker)
						break 2;
				}
				unset($target);
				$init++;
				$i++;
			}
		}
	}
	$all_escaping_attacker = $grouped_units['attacker']->checkForAllEscaping();

	$all_escaping_target = $grouped_units['target']->checkForAllEscaping();

	//creating the fight result message
	$attacker_lost_units = $grouped_units['attacker']->getLostUnits();
	$target_lost_units = $grouped_units['target']->getLostUnits();

/* yet not used
	if ($old_init)
		$count = count($attacker_lost_units);
	else
		$count = count($target_lost_units);
*/

	$msg_html = '[html]';
	$msg_html .= '<div class="fight_result">';
	$msg_html .= lib_bl_troops_drawFightMessageRow($lang['unit'], true);
	$msg_html .= lib_bl_troops_drawFightMessageRow($attacker_units);
	$msg_html .= lib_bl_troops_drawFightMessageRow($attacker_lost_units);
	$msg_html .= '</div>';
	$msg_html .= '[/html]';

	if ($grouped_units['target']->getAllUnitsDead())
		$result = $lang['fight']['glorious_win'];
	elseif ($all_escaping_target)
		$result = $lang['fight']['win'];
	elseif ($grouped_units['attacker']->getAllUnitsDead())
		$result = $lang['fight']['smash'];
	elseif ($all_escaping_attacker)
		$result = $lang['fight']['lose'];

	/*lib_bl_general_sendMessage(-1, $troop['uid'], $lang['fight']['topic'], sprintf(
		$lang['fight']['message'],
		lib_dal_user_uid2nick($troop['uid']),
		lib_dal_user_uid2nick($target_uid),
		$result,
		$msg_html
	), 4);*/

	$msg_html = '[html]';
	$msg_html .= '<div class="fight_result">';
	$msg_html .= lib_bl_troops_drawFightMessageRow($lang['unit'], true);
	$msg_html .= lib_bl_troops_drawFightMessageRow($target_units);
	$msg_html .= lib_bl_troops_drawFightMessageRow($target_lost_units);
	$msg_html .= '</div>';
	$msg_html .= '[/html]';

	if ($grouped_units['attacker']->getAllUnitsDead())
		$result = $lang['fight']['glorious_win'];
	elseif ($all_escaping_attacker)
		$result = $lang['fight']['win'];
	elseif ($grouped_units['target']->getAllUnitsDead())
		$result = $lang['fight']['smash'];
	elseif ($all_escaping_target)
		$result = $lang['fight']['lose'];

	/*lib_bl_general_sendMessage(-1, $target_uid, $lang['fight']['topic'], sprintf(
		$lang['fight']['message'],
		lib_dal_user_uid2nick($target_uid),
		lib_dal_user_uid2nick($troop['uid']),
		$result,
		$msg_html
	), 4);*/

//	$grouped_units['attacker']->reduceUnits();
//	$grouped_units['target']->reduceUnits();

	return array('attacker' => ($all_escaping_attacker ? false : true), 'target' => ($all_escaping_target ? false : true));
}

function lib_bl_troops_attack($attacker, $attacker_type, $target, $target_type, $city)
{
	$out = false;

	switch ($attacker_type)
	{
		case 'range':
		{
			$fight_result = lib_bl_troops_unitFight($attacker, $target, $city);
			break;
		}
		case 'near':
		{
			$fight_result = lib_bl_troops_unitFight($attacker, $target, $city);
			break;
		}
		case 'rider':
		{
			$fight_result = lib_bl_troops_unitFight($attacker, $target, $city);
			break;
		}
	}

	return $fight_result;
}

function lib_bl_troops_unitFight($attacker, $target, $city)
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
								$stats = lib_dal_troops_getUnitStats($target_unit['kind']);

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

function lib_bl_troops_drawFightMessageRow($valuelist, $is_lang = false)
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
?>