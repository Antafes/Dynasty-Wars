<?php
/**
 * select all buildings
 * @author Neithan
 * @param int $x
 * @param int $y
 * @param int $not_built default 0
 * @return array containing all buildings
 */
function lib_bl_buildings_selectAll($x, $y, $not_built = 0)
{
	$res = lib_dal_buildings_selectAll($x, $y);
	$count = count($res);
	unset($buildings);
	$n = 0;
	while ($n < $count) {
		if ($not_built)
			$pos = $n;
		else
			$pos = $res[$n]['position'];
		$buildings[$pos]['bid'] = $res[$n]['bid'];
		$buildings[$pos]['kind'] = $res[$n]['kind'];
		$buildings[$pos]['lvl'] = $res[$n]['lvl'];
		$buildings[$pos]['ulvl'] = $res[$n]['upgrade_lvl'];
		$buildings[$pos]['position'] = $res[$n]['position'];
		$n++;
	}
	return $buildings;
}

/**
 * can the city build an harbour?
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return int returns 1 if the harbour can be built, otherwise 0
 */
function lib_bl_buildings_getHarbour($x, $y)
{
	return lib_dal_buildings_getHarbour($x, $y);
}

/**
 * get the pic
 * @author Neithan
 * @param int $city
 * @param int $building
 * @param array $lang contains the strings of the current language
 * @param int $new_building
 * @return string
 */
function lib_bl_buildings_getPic($city, $building, $new_building = 0)
{
	global $lang;
	$cityexp = explode(':', $city);
	$building_pics = array(
		1 => array(0 => 'ricefield'),
		2 => array(0 => 'woodcutter'),
		3 => array(0 => 'quarry'),
		4 => array(0 => 'ironmine'),
		5 => array(0 => 'papermill'),
		7 => array(1 => 'archer', 2 => 'archer', 3 => 'archer', 4 => 'archer'),
		8 => array(1 => 'spear', 2 => 'spear', 3 => 'spear', 4 => 'spear'),
		9 => array(1 => 'teahouse', 2 => 'teahouse', 3 => 'teahouse', 4 => 'teahouse'),
		10 => array(1 => 'ninja', 2 => 'ninja', 3 => 'ninja'),
		11 => array(0 => 'geisha'),
		12 => array(0 => 'garden'),
		13 => array(0 => 'palace'),
		14 => array(1 => 'blacksmith', 2 => 'blacksmith', 3 => 'blacksmith'),
		15 => array(0 => 'factory'),
		16 => array(1 => 'arsenal', 2 => 'arsenal', 3 => 'arsenal', 4 => 'arsenal'),
		17 => array(1 => 'rider', 2 => 'rider', 3 => 'rider', 4 => 'rider'),
		18 => array(1 => 'temple', 2 => 'temple', 3 => 'temple'),
		19 => array(0 => 'mainbuilding', 1 => 'mainbuilding', 2 => 'castle', 3 => 'small_wooden_castle', 4 => 'large_wooden_castle', 5 => 'large_stone_castle', 6 => 'citadel'),
		20 => array(1 => 'sword', 2 => 'sword', 3 => 'sword'),
		21 => array(1 => 'church', 2 => 'church'),
		22 => array(0 => 'storage'),
		23 => array(1 => 'wall', 2 => 'wall', 3 => 'wall', 4 => 'wall'),
		24 => array(1 => 'tower', 2 => 'tower', 3 => 'tower', 4 => 'tower'),
		25 => array(1 => 'camp', 2 => 'camp', 3 => 'camp')
	);
	$has_harbour = lib_bl_buildings_getHarbour($cityexp[0], $cityexp[1]);
	if ($has_harbour)
		$building_pics[6] = array(0 => 'harbour');
	else
		$building_pics[6] = array(0 => 'tradepost');
	$season = lib_bl_general_getSeason($con);
	/*if ($season == 1)
		$path = 'pictures/city/grass/summer/';
	elseif ($season == 2)
		$path = 'pictures/city/grass/winter/';*/
	$path = 'pictures/city/grass/summer/'; //this is a temporary solution
	if (is_array($building) or !$building)
	{
		if (($building['lvl'] == 0 or !$building) and $new_building == 0)
			$html = '<img src="'.$path.'buildplace.gif" alt="'.$lang['buildplace'].'" title="'.$lang['buildplace'].'" />';
		elseif ($new_building == 1 or $building['lvl'])
		{
			if ($building['kind'] != 6)
				$name = htmlentities($lang['building_names'][$building['kind']][$building['ulvl']]);
			elseif ($building['kind'] == 6)
				$name = htmlentities($lang['building_names'][$building['kind']][$has_harbour]);

			$html = '<img src="'.$path.'buildings/';
			$html .= $building_pics[$building['kind']][$building['ulvl']];
			$html .= '.gif" alt="'.$name.'"';
			$html .= ' title="'.$name.' ('.$building['lvl'].')"/>';
		}
	}
	elseif (is_string($building))
		$html = '<img src="'.$path.'way_part.gif" alt="" />';
	$html .= "\n";
	return $html;
}

/**
 * select the choosen building
 * @author Neithan
 * @param int $x
 * @param int $y
 * @param int $pos
 * @return array containing the building
 */
function lib_bl_buildings_selectBuilding($x, $y, $pos)
{
	$res = lib_dal_buildings_selectBuilding($x, $y, $pos);

	if ($res)
	{
		$count = count($res);
		if ($count > 0 and $pos)
		{
			unset($building);
			$n = 0;
			while ($n < $count) {
				$building['bid'] = (int)$res[$n]['bid'];
				$building['kind'] = (int)$res[$n]['kind'];
				$building['lvl'] = (int)$res[$n]['lvl'];
				$building['ulvl'] = (int)$res[$n]['upgrade_lvl'];
				$building['position'] = (int)$res[$n]['position'];
				$n++;
			}
		}
		elseif ($count > 0 and !$pos)
		{
			unset($building);
			$n = 0;
			while ($n < $count) {
				$building[$n]['bid'] = $res[$n]['bid'];
				$building[$n]['kind'] = $res[$n]['kind'];
				$building[$n]['lvl'] = $res[$n]['lvl'];
				$building[$n]['ulvl'] = $res[$n]['upgrade_lvl'];
				$building[$n]['position'] = $res[$n]['position'];
				$n++;
			}
		}
	}
	else
	{
		if ($pos < 8)
		{
			$new_building = array(
				1 => array('bid' => 0, 'kind' => 19, 'lvl' => 0, 'ulvl' => 0, 'position' => 1),
				2 => array('bid' => 0, 'kind' => 1, 'lvl' => 0, 'ulvl' => 0, 'position' => 2),
				3 => array('bid' => 0, 'kind' => 2, 'lvl' => 0, 'ulvl' => 0, 'position' => 3),
				4 => array('bid' => 0, 'kind' => 3, 'lvl' => 0, 'ulvl' => 0, 'position' => 4),
				5 => array('bid' => 0, 'kind' => 4, 'lvl' => 0, 'ulvl' => 0, 'position' => 5),
				6 => array('bid' => 0, 'kind' => 5, 'lvl' => 0, 'ulvl' => 0, 'position' => 6),
				7 => array('bid' => 0, 'kind' => 6, 'lvl' => 0, 'ulvl' => 0, 'position' => 7),
			);
			$building = $new_building[$pos];
		}
		elseif ($pos > 7)
		{
		}
	}
	return $building;
}

/**
 * is the building upgradeable?
 * @author Neithan
 * @param int $kind
 * @return <int> returns 1 if the building is upgradable, otherwise returns 0
 */
function lib_bl_buildings_getUpgradeable($kind)
{
	$is_upgradeable = array(
		1 => false,
		2 => false,
		3 => false,
		4 => false,
		5 => false,
		6 => false,
		7 => true,
		8 => true,
		9 => true,
		10 => true,
		11 => false,
		12 => false,
		13 => false,
		14 => true,
		15 => false,
		16 => true,
		17 => true,
		18 => true,
		19 => true,
		20 => true,
		21 => true,
		22 => false,
		23 => true,
		24 => true,
		25 => true
	);
	return $is_upgradeable[$kind];
}

/**
 * calculating the build prices
 * @author Neithan
 * @param int $kind
 * @param int $lvl
 * @param int $upgrade_lvl
 * @param int $has_harbour
 * @param string $city
 * @return array containing the prices
 */
function lib_bl_buildings_prices($kind, $lvl, $upgrade_lvl, $has_harbour, $city)
{
	$factor = 0.220;
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$prices = lib_dal_buildings_prices($kind);
	$mainbuilding = lib_dal_buildings_getBuildingByKind(19, $x, $y);
	$paper = lib_dal_buildings_getBuildingByKind(5, $x, $y);
	$koku = lib_dal_buildings_getBuildingByKind(6, $x, $y);
	if ($paper['lvl'] == 0 && $mainbuilding['ulvl'] <= 1)
		$prices['paper'] = 0;
	if ($koku['lvl'] == 0 && $mainbuilding['ulvl'] <= 1)
		$prices['koku'] = 0;
	if (!$lvl)
	{
		if ($kind == 6 && $has_harbour)
			$prices['paper'] = 0;
		return $prices;
	}
	else
	{
		if ($kind == 6 && $has_harbour)
			$prices['paper'] = 0;
		$lvl = $lvl+1;
		$oldprice = $prices;
		for ($n = 0; $n < $lvl; $n++)
		{
			$oldprice['food'] = $oldprice['food']+$oldprice['food']*$factor;
			$oldprice['wood'] = $oldprice['wood']+$oldprice['wood']*$factor;
			$oldprice['rock'] = $oldprice['rock']+$oldprice['rock']*$factor;
			$oldprice['iron'] = $oldprice['iron']+$oldprice['iron']*$factor;
			$oldprice['paper'] = $oldprice['paper']+$oldprice['paper']*$factor;
			$oldprice['koku'] = $oldprice['koku']+$oldprice['koku']*$factor;
		}
		return $oldprice;
	}
}

/**
 * selecting the upgrade prices
 * @author Neithan
 * @param int $kind
 * @param int $kind_u
 * @param int $lvl
 * @param int $upgrade_lvl
 * @return array containing the prices
 */
function lib_bl_buildings_upgradePrices($kind, $kind_u, $lvl, $upgrade_lvl)
{
	$prices = lib_dal_buildings_upgradePrices($kind, $kind_u, $lvl, $upgrade_lvl);
$GLOBALS['firePHP']->log($prices, 'upgradePrices');
	if (is_array($prices))
	{
		foreach ($prices as $key => $res)
		{
			if (!is_numeric($key))
				$price[$key] = $res;
		}
		$prices = $price;
	}
	else
		$prices = false;

	return $prices;
}

/**
 * get the buildings that are not built
 * @author Neithan
 * @param int $x
 * @param int $y
 * @param int $uid
 * @param int $def
 * @return array containing the not built buildings
 */
function lib_bl_buildings_getNotBuilt($x, $y, $uid, $def=0)
{
	$main = lib_bl_buildings_selectBuilding($x, $y, 1);
	if ($main['lvl'] < 1)
		return array();
	$religion = lib_bl_buildings_checkReligion($uid);
	$buildable = array();
	if ($def == 0)
	{
		$built = lib_bl_buildings_selectAll($x, $y, 1);
		$i = 7;
		$maxi = 22;
	}
	else
	{
		$built = lib_bl_buildings_getDefense($x.':'.$y, 1);
		$i = 23;
		$maxi = 25;
	}
	for (; $i <= $maxi; $i++)
	{
		$lvl = 0;
		$ulvl = 0;
		$allready_built = 0;
		if ($built)
		{
			foreach ($built as $built_part)
			{
				if ($i == $built_part['kind'])
				{
					$allready_built = 1;
					$lvl = $built_part['lvl'];
					$ulvl = $built_part['ulvl'];
					$position = $built_part['position'];
				}
			}
		}
		if ((!$ulvl or $ulvl == 0) and lib_bl_buildings_getUpgradeable($i))
			$ulvl = 1;
		if (($allready_built == 0 or ($allready_built == 1 and !$position)) and ($i != 19 or $i != 11 or $i != 15 or ($religion == 1 and $i != 21)))
			$buildable[] = array('kind' => $i, 'lvl' => $lvl, 'ulvl' => $ulvl);
	}
	return $buildable;
}

/**
 * check religion
 * @author Neithan
 * @param int $uid
 * @return <int> returns 1 if the religion is buddhism and 2 if the religion is christianity
 */
function lib_bl_buildings_checkReligion($uid)
{
	return lib_dal_buildings_checkReligion($uid);
}

/**
 * check which buildings can be built at the defined upgrade state
 * @author Neithan
 * @param int $uid
 * @param int $kind
 * @param int $x
 * @param int $y
 * @return <int> returns 1 if the building can be build, otherwise returns 0
 */
function lib_bl_buildings_checkBuildable($uid, $kind, $x, $y)
{
	if ($kind != 19)
	{
		$main = lib_dal_buildings_getBuildingByKind(19, $x, $y);
		switch ($main['ulvl'])
		{
			case 1:
			{
				if ($kind == 1 or $kind == 2 or $kind == 3 or $kind == 4 or $kind == 7 or $kind == 8 or $kind == 9 or $kind == 22)
					return 1;
				else
					return 0;
				break;
			}
			case 2:
			{
				if ($kind == 1 or $kind == 2 or $kind == 3 or $kind == 4 or $kind == 5 or $kind == 6 or $kind == 7 or $kind == 8
					or $kind == 9 or $kind == 10 or $kind == 12 or $kind == 22 or $kind == 23 or $kind == 24)
					return 1;
				else
					return 0;
				break;
			}
			case 3:
			{
				if ($kind == 1 or $kind == 2 or $kind == 3 or $kind == 4 or $kind == 5 or $kind == 6 or $kind == 7 or $kind == 8
					or $kind == 9 or $kind == 10 or $kind == 12 or $kind == 13 or $kind == 14 or $kind == 16 or $kind == 17
					or $kind == 18 or $kind == 22 or $kind == 23 or $kind == 24 or $kind == 25)
					return 1;
				else
					return 0;
				break;
			}
			case 4:
			case 5:
			{
				if ($kind == 1 or $kind == 2 or $kind == 3 or $kind == 4 or $kind == 5 or $kind == 6 or $kind == 7 or $kind == 8
					or $kind == 9 or $kind == 10 or $kind == 12 or $kind == 13 or $kind == 14 or $kind == 16 or $kind == 17
					or $kind == 18 or $kind == 20 or $kind == 21 or $kind == 22 or $kind == 23 or $kind == 24 or $kind == 25)
					return 1;
				else
					return 0;
				break;
			}
			case 6:
			{
				return 1;
				break;
			}
		}
	}
	elseif ($kind == 19)
		return 1;
}

/**
 * check which buildings can be upgraded at the defined upgrade state
 * @author Neithan
 * @param int $kind
 * @param string $city
 * @return <int> returns 1 if the building can be upgraded, otherwise 0
 */
function lib_bl_buildings_checkUpgradeable($kind, $city)
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$building = lib_dal_buildings_getBuildingByKind($kind, $x, $y);
	if ($kind == 19)
	{
		if (floor($building['lvl']/10) >= $building['ulvl'])
			return 1;
		else
			return 0;
	}
	elseif ($kind != 19)
	{
		$main = lib_dal_buildings_getBuildingByKind(19, $x, $y);
		switch ($main['ulvl'])
		{
			case 1:
			{
				return 0;
				break;
			}
			case 2:
			{
				if (($kind == 7 or $kind == 9) and floor($building['lvl']/10) >= $building['ulvl'])
					return 1;
				else
					return 0;
				break;
			}
			case 3:
			{
				if (($kind == 8 or $kind == 9 or $kind == 10 or $kind == 24) and floor($building['lvl']/10) >= $building['ulvl'])
					return 1;
				else
					return 0;
				break;
			}
			case 4:
			{
				if (($kind == 7 or $kind == 14 or $kind == 16 or $kind == 17 or $kind == 23 or $kind == 25)
					and floor($building['lvl']/10) >= $building['ulvl'])
					return 1;
				else
					return 0;
				break;
			}
			case 5:
			{
				if (($kind == 8 or $kind == 9 or $kind == 10 or $kind == 14 or $kind == 16 or $kind == 17 or $kind == 18 or $kind == 20 or $kind == 23 or $kind == 24)
					and floor($building['lvl']/10) >= $building['ulvl'])
					return 1;
				else
					return 0;
				break;
			}
			case 6:
			{
				if (($kind == 7 or $kind == 8 or $kind == 16 or $kind == 17 or $kind == 18 or $kind == 20 or $kind == 21 or $kind == 23 or $kind == 24 or $kind == 25)
					and floor($building['lvl']/10) >= $building['ulvl'])
					return 1;
				else
					return 0;
				break;
			}
		}
	}
}

/**
 * check whether there are enough ressources for building or not
 * @author Neithan
 * @param array $valuelist array([food], [wood], [rock], [iron], [paper], [koku],
 * 		[price_food], [price_wood], [price_rock], [price_iron], [price_paper], [price_koku])
 * @return <int> returns 1 if there are enough resources to build
 */
function lib_bl_buildings_resCheck($valuelist)
{
	$food_n = $valuelist['res_food'] - $valuelist['food'];
	$wood_n = $valuelist['res_wood'] - $valuelist['wood'];
	$rock_n = $valuelist['res_rock'] - $valuelist['rock'];
	$iron_n = $valuelist['res_iron'] - $valuelist['iron'];
	$paper_n = $valuelist['res_paper'] - $valuelist['paper'];
	$koku_n = $valuelist['res_koku'] - $valuelist['koku'];
	if (($food_n >= 0) and ($wood_n >= 0) and ($rock_n >= 0) and ($iron_n >= 0) and ($paper_n >= 0) and ($koku_n >= 0))
		return 1;
	else
		return 0;
}

/**
 * calculating the build times
 * @author Neithan
 * @param int $kind
 * @param int $lvl
 * @param int $upgrade default = 0
 * @param int $u_lvl default = 0
 * @return int
 */
function lib_bl_buildings_buildTime($kind, $lvl, $upgrade = 0, $u_lvl = 0)
{
	if (is_integer($kind) and $upgrade == 0)
	{
		$btime = (int)lib_dal_buildings_getTime($kind, $upgrade, $u_lvl);
		if ($lvl)
		{
			unset($time);
			$n = 0;
			while ($n < $lvl)
			{
				if (!$time)
					$time = $btime*1.10;
				else
					$time = $time*1.10;
				$n++;
			}
		}
		else
			$time = $btime;
	}
	else
		$time = (int)lib_dal_buildings_getTime($kind, $upgrade, $u_lvl);
	return $time;
}

/**
 * start building
 * @author Neithan
 * @param int $buildplace
 * @param int $uid
 * @param string $city
 * @param int $upgrade default = 0
 * @param int $kind default = ''
 * @return <int> returns 1 if the build is started, otherwise 0
 */
function lib_bl_buildings_build($buildplace, $uid, $city, $upgrade = 0, $kind = '')
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$building = lib_bl_buildings_selectBuilding($x, $y, $buildplace);
	if (!$building)
	{
		$building = lib_dal_buildings_getBuildingByKind($kind, $x, $y);

		if (!$building)
		{
			$building['bid'] = 0;
			$building['lvl'] = 0;
			$building['ulvl'] = 0;
		}
		$building['kind'] = $kind;
	}
$GLOBALS['firePHP']->log($building, 'building array');
	if (!lib_bl_buildings_checkFreeBuildPosition($city, $building['kind']))
		return 0;
	$has_harbour = lib_bl_buildings_getHarbour($x, $y);
	$prices = lib_bl_buildings_prices($building['kind'], $building['lvl'], $building['ulvl'], $has_harbour, $city);
	$helpres = lib_bl_general_getRes($x, $y);
	$res_values = array(
		'res_food' => $helpres["food"],
		'res_wood' => $helpres["wood"],
		'res_rock' => $helpres["rock"],
		'res_iron' => $helpres["iron"],
		'res_paper' => $helpres["paper"],
		'res_koku' => $helpres["koku"],
	);
	if ($upgrade)
	{
		$prices_upgr = lib_bl_buildings_upgradePrices($building['kind'], $building['ulvl'], $building['lvl'], $building['ulvl']);
		$res_values += $prices_upgr;
		$check = lib_bl_buildings_resCheck($res_values);
	}
	else
	{
		$res_values += $prices;
		$check = lib_bl_buildings_resCheck($res_values);
	}
	if ($check)
	{
		if ($upgrade)
		{
			$btime = lib_bl_buildings_buildTime($building['kind'], $building['lvl'], 1, $building['ulvl']);
			$endtime = time()+$btime;
		}
		else
		{
			$btime = lib_bl_buildings_buildTime($building['kind'], $building['lvl']);
			$endtime = time()+$btime;
		}
		if ($building['bid'] == 0)
			$building['bid'] = lib_dal_buildings_insertBuilding($uid, $x, $y, $building['kind'], $buildplace);
		$erg2 = lib_dal_buildings_startBuilding($building['bid'], $upgrade, $endtime);
		if ($building['bid'] and !$building['position'])
			lib_dal_buildings_insertBuildPlace($building['bid'], $buildplace);
		if (!$upgrade)
		{
			$res['food'] = $helpres['food'] - $prices['food'];
			$res['wood'] = $helpres['wood'] - $prices['wood'];
			$res['rock'] = $helpres['rock'] - $prices['rock'];
			$res['iron'] = $helpres['iron'] - $prices['iron'];
			$res['paper'] = $helpres['paper'] - $prices['paper'];
			$res['koku'] = $helpres['koku'] - $prices['koku'];
			$erg3 = lib_bl_resource_updateAll($res, $city);
		}
		else
		{
			$res['food'] = $helpres['food'] - $prices_upgr['food'];
			$res['wood'] = $helpres['wood'] - $prices_upgr['wood'];
			$res['rock'] = $helpres['rock'] - $prices_upgr['rock'];
			$res['iron'] = $helpres['iron'] - $prices_upgr['iron'];
			$res['paper'] = $helpres['paper'] - $prices_upgr['paper'];
			$res['koku'] = $helpres['koku'] - $prices_upgr['koku'];
			$erg3 = lib_bl_resource_updateAll($res, $city);
		}
		if ($erg2 and $erg3)
			return 1;
		else
			return 0;
	}
	else
		return 0;
}

/**
 * check for running build
 * @author Neithan
 * @param int $uid
 * @param string $city
 * @return array|int returns an array with the informations about the build.
 * 		if nothing is in the build list, 0 is returned
 */
function lib_bl_buildings_checkBuild($uid, $city) {
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$buildlist = lib_dal_buildings_checkBuild($x, $y);
$GLOBALS['firePHP']->log($buildlist, 'list of buildings');
	if (is_array($buildlist))
	{
		$x = 0;
		foreach ($buildlist as $parts)
		{
			if (time() >= $parts['endtime'])
				lib_bl_buildings_buildComplete($parts['bid']);
			else
			{
				$running[$x]['endtime'] = $parts['endtime'];
				$running[$x]['kind'] = $parts['kind'];
				$running[$x]['ulvl'] = $parts['ulvl'];
				if ($running[$x]['ulvl'] == 0 and lib_bl_buildings_getUpgradeable($running[$x]['kind']))
					$running[$x]['ulvl'] = 1;
				$running[$x]['bid'] = $parts['bid'];
				$running[$x]['position'] = $parts['position'];
				$x++;
			}
		}
	}
	if (count($buildlist) <= 0)
		$running = 0;
	return $running;
}

/**
 * complete building
 * @author Neithan
 * @param int $bid
 */
function lib_bl_buildings_buildComplete($bid)
{
	$build['bid'] = $bid;
	$build = lib_dal_buildings_getBuildInfo($build['bid']);
	$is_upgradeable = lib_bl_buildings_getUpgradeable($build['kind']);
	if (($build['lvl'] == 0 or !$build['lvl']) and ($build['ulvl'] == 0 or !$build['ulvl']) and $is_upgradeable)
		$build['lvl'] = $build['ulvl'] = 1;
	elseif (!$build['upgrade'])
		$build['lvl']++;
	elseif ($build['upgrade'])
		$build['ulvl']++;
	lib_dal_buildings_removeFromBuildList($build['bid']);
	lib_dal_buildings_updateBuilding($build);
}

/**
 * check if there is allready a building of this type in the build list
 * @author Neithan
 * @param string $city
 * @param int $kind
 * @return <int> returns 1 if there is no other building of this type in the build list, otherwise 0
 */
function lib_bl_buildings_checkFreeBuildPosition($city, $kind)
{
$GLOBALS['firePHP']->log($city, 'city');
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$res = lib_dal_buildings_checkBuild($x, $y);
$GLOBALS['firePHP']->log($res, 'checkBuild result');
$GLOBALS['firePHP']->log(count($res), 'checkBuild result rows');
	if ($res)
	{
$GLOBALS['firePHP']->log('greater 0', 'checkBuild result rows');
		foreach ($res as $parts)
		{
			if ((($kind >= 1 and $kind <= 6) or $kind == 22) and (($parts['kind'] >= 1 and $parts['kind'] <= 6) or $parts['kind'] == 22))
				return 0;
			elseif (($kind >= 7 and $kind <= 21) and ($parts['kind'] >= 7 and $parts['kind'] <= 21))
				return 0;
			elseif (($kind >= 23 and $kind <= 25) and ($parts['kind'] >= 23 and $parts['kind'] <= 25))
				return 0;
		}
	}
	return 1;
}

/**
 * get the upgrade level of the ninja house, the teahouse and the blacksmith
 * @author Neithan
 * @param string $city
 * @return array
 */
function lib_bl_buildings_checkGeishaFactory($city)
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$teahouse = lib_dal_buildings_getBuildingByKind(9, $x, $y);
	$ninja = lib_dal_buildings_getBuildingByKind(10, $x, $y);
	$blacksmith = lib_dal_buildings_getBuildingByKind(14, $x, $y);
	$return['factory'] = $return['geisha'] = 0;
	if ($teahouse['ulvl'] == 4 and $ninja['ulvl'] == 3)
		$return['geisha'] = 1;
	if ($blacksmith['ulvl'] == 3)
		$return['factory'] = 1;
	return $return;
}

/**
 * get the defense buildings
 * @author Neithan
 * @param string $city
 * @param int $not_built default = 0
 * @return array
 */
function lib_bl_buildings_getDefense($city, $not_built = 0)
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$def_res = lib_dal_buildings_getDefense($x, $y);
	$count = count($def_res);
	for ($n = 0; $n < $count; $n++)
	{
		if ($not_built)
			$x = $n;
		else
			$x = $def_res[$n]['position'];
		$defense[$x]['bid'] = $def_res[$n]['bid'];
		$defense[$x]['kind'] = $def_res[$n]['kind'];
		$defense[$x]['lvl'] = $def_res[$n]['lvl'];
		$defense[$x]['ulvl'] = $def_res[$n]['upgrade_lvl'];
		$defense[$x]['position'] = $def_res[$n]['position'];
	}
	return $defense;
}

/**
 * get the specified building via kind and map position
 * @author Neithan
 * @param int $kind
 * @param string $city
 * @return array
 */
function lib_bl_buildings_getBuildingByKind($kind, $city)
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$buildings = lib_dal_buildings_getBuildingByKind($kind, $x, $y);
	$build['lvl'] = $buildings['lvl'];
	$build['ulvl'] = $buildings['ulvl'];
	$build['bid'] = $buildings['bid'];
	$build['position'] = $buildings['position'];
	return $build;
}
?>