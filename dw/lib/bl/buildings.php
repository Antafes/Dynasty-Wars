<?php
namespace bl\buildings;

/**
 * select all buildings
 * @author Neithan
 * @param int $x
 * @param int $y
 * @param int $not_built default 0
 * @return array containing all buildings
 */
function selectAll($x, $y, $not_built = 0)
{
	$res = dal\buildings\selectAll($x, $y);
	$count = count($res);

	$buildings = array();
	$n = 0;
	while ($n < $count)
	{
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
function getHarbour($x, $y)
{
	return dal\buildings\getHarbour($x, $y);
}

/**
 * get the pic
 * @author Neithan
 * @global array $$lang
 * @param int $city
 * @param int $building
 * @param int $new_building
 * @return string
 */
function getBuildPlacePicture($city, $building, $new_building = 0)
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
	$has_harbour = getHarbour($cityexp[0], $cityexp[1]);
	if ($has_harbour)
		$building_pics[6] = array(0 => 'harbour');
	else
		$building_pics[6] = array(0 => 'tradepost');
	$season = bl\general\getSeason($con);
	/*if ($season == 1)
		$path = 'pictures/city/grass/summer/';
	elseif ($season == 2)
		$path = 'pictures/city/grass/winter/';*/
	$path = 'pictures/city/grass/summer/'; //this is a temporary solution
	if (is_array($building) || !$building)
	{
		if (($building['lvl'] == 0 || !$building) && $new_building == 0)
			$html = '<img src="'.$path.'buildplace.gif" alt="'.$lang['buildplace'].'" title="'.$lang['buildplace'].'" />';
		elseif ($new_building == 1 || $building['lvl'])
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
function selectBuilding($x, $y, $pos)
{
	$res = dal\buildings\selectBuilding($x, $y, $pos);

	if ($res)
	{
		$count = count($res);
		if ($count > 0 && $pos)
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
		elseif ($count > 0 && !$pos)
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
function getUpgradeable($kind)
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
 * @param int $has_harbour
 * @param string $city
 * @return array containing the prices
 */
function prices($kind, $lvl, $has_harbour, $city)
{
	$factor = 0.220;
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$prices = dal\buildings\prices($kind);
	$mainbuilding = dal\buildings\getBuildingByKind(19, $x, $y);
	$paper = dal\buildings\getBuildingByKind(5, $x, $y);
	$koku = dal\buildings\getBuildingByKind(6, $x, $y);

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
function upgradePrices($kind, $kind_u, $lvl, $upgrade_lvl)
{
	$prices = dal\buildings\upgradePrices($kind, $kind_u, $lvl, $upgrade_lvl);

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
function getNotBuilt($x, $y, $uid, $def = 0)
{
	$main = selectBuilding($x, $y, 1);
	if ($main['lvl'] < 1)
		return array();
	$religion = checkReligion($uid);
	$buildable = array();
	if ($def == 0)
	{
		$built = selectAll($x, $y, 1);
		$i = 7;
		$maxi = 22;
	}
	else
	{
		$built = getDefense($x.':'.$y, 1);
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
		if ((!$ulvl || $ulvl == 0) && getUpgradeable($i))
			$ulvl = 1;
		if (($allready_built == 0 || ($allready_built == 1 && !$position)) && ($i != 19 || $i != 11 || $i != 15 || ($religion == 1 && $i != 21)))
			$buildable[] = array('kind' => $i, 'lvl' => $lvl, 'ulvl' => $ulvl);
	}
	return $buildable;
}

/**
 * check religion
 * @author Neithan
 * @param int $uid
 * @return int returns 1 if the religion is buddhism and 2 if the religion is christianity
 */
function checkReligion($uid)
{
	return dal\buildings\checkReligion($uid);
}

/**
 * check which buildings can be built at the defined upgrade state
 * @author Neithan
 * @param int $uid
 * @param int $kind
 * @param int $x
 * @param int $y
 * @return int returns 1 if the building can be build, otherwise returns 0
 */
function checkBuildable($uid, $kind, $x, $y)
{
	if ($kind != 19)
	{
		$main = dal\buildings\getBuildingByKind(19, $x, $y);
		switch ($main['ulvl'])
		{
			case 1:
			{
				if ($kind == 1 || $kind == 2 || $kind == 3 || $kind == 4 || $kind == 7 || $kind == 8 || $kind == 9 || $kind == 22)
					return 1;
				else
					return 0;
				break;
			}
			case 2:
			{
				if ($kind == 1 || $kind == 2 || $kind == 3 || $kind == 4 || $kind == 5 || $kind == 6 || $kind == 7 || $kind == 8
					|| $kind == 9 || $kind == 10 || $kind == 12 || $kind == 22 || $kind == 23 || $kind == 24)
					return 1;
				else
					return 0;
				break;
			}
			case 3:
			{
				if ($kind == 1 || $kind == 2 || $kind == 3 || $kind == 4 || $kind == 5 || $kind == 6 || $kind == 7 || $kind == 8
					|| $kind == 9 || $kind == 10 || $kind == 12 || $kind == 13 || $kind == 14 || $kind == 16 || $kind == 17
					|| $kind == 18 || $kind == 22 || $kind == 23 || $kind == 24 || $kind == 25)
					return 1;
				else
					return 0;
				break;
			}
			case 4:
			case 5:
			{
				if ($kind == 1 || $kind == 2 or $kind == 3 || $kind == 4 || $kind == 5 || $kind == 6 || $kind == 7 || $kind == 8
					|| $kind == 9 || $kind == 10 || $kind == 12 || $kind == 13 || $kind == 14 || $kind == 16 || $kind == 17
					|| $kind == 18 || $kind == 20 || $kind == 21 || $kind == 22 || $kind == 23 || $kind == 24 || $kind == 25)
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
 * @return int returns 1 if the building can be upgraded, otherwise 0
 */
function checkUpgradeable($kind, $city)
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$building = dal\buildings\getBuildingByKind($kind, $x, $y);
	if ($kind == 19)
	{
		if (floor($building['lvl']/10) >= $building['ulvl'])
			return 1;
		else
			return 0;
	}
	elseif ($kind != 19)
	{
		$main = dal\buildings\getBuildingByKind(19, $x, $y);
		switch ($main['ulvl'])
		{
			case 1:
			{
				return 0;
				break;
			}
			case 2:
			{
				if (($kind == 7 || $kind == 9) && floor($building['lvl']/10) >= $building['ulvl'])
					return 1;
				else
					return 0;
				break;
			}
			case 3:
			{
				if (($kind == 8 || $kind == 9 || $kind == 10 || $kind == 24) && floor($building['lvl']/10) >= $building['ulvl'])
					return 1;
				else
					return 0;
				break;
			}
			case 4:
			{
				if (($kind == 7 || $kind == 14 || $kind == 16 || $kind == 17 || $kind == 23 || $kind == 25)
					&& floor($building['lvl']/10) >= $building['ulvl'])
					return 1;
				else
					return 0;
				break;
			}
			case 5:
			{
				if (($kind == 8 || $kind == 9 || $kind == 10 || $kind == 14 || $kind == 16 || $kind == 17 || $kind == 18 || $kind == 20 || $kind == 23 || $kind == 24)
					&& floor($building['lvl']/10) >= $building['ulvl'])
					return 1;
				else
					return 0;
				break;
			}
			case 6:
			{
				if (($kind == 7 || $kind == 8 || $kind == 16 || $kind == 17 || $kind == 18 || $kind == 20 || $kind == 21 || $kind == 23 || $kind == 24 || $kind == 25)
					&& floor($building['lvl']/10) >= $building['ulvl'])
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
 * @return int returns 1 if there are enough resources to build
 */
function resourceCheck($valuelist)
{
	$food_n = $valuelist['res_food'] - $valuelist['food'];
	$wood_n = $valuelist['res_wood'] - $valuelist['wood'];
	$rock_n = $valuelist['res_rock'] - $valuelist['rock'];
	$iron_n = $valuelist['res_iron'] - $valuelist['iron'];
	$paper_n = $valuelist['res_paper'] - $valuelist['paper'];
	$koku_n = $valuelist['res_koku'] - $valuelist['koku'];
	if (($food_n >= 0) && ($wood_n >= 0) && ($rock_n >= 0) && ($iron_n >= 0) && ($paper_n >= 0) && ($koku_n >= 0))
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
function buildTime($kind, $lvl, $upgrade = 0, $u_lvl = 0)
{
	if (is_integer($kind) && $upgrade == 0)
	{
		$btime = (int)dal\buildings\getTime($kind, $upgrade, $u_lvl);
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
		$time = (int)dal\buildings\getTime($kind, $upgrade, $u_lvl);
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
 * @return int returns 1 if the build is started, otherwise 0
 */
function build($buildplace, $uid, $city, $upgrade = 0, $kind = '')
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$building = selectBuilding($x, $y, $buildplace);
	if (!$building)
	{
		$building = dal\buildings\getBuildingByKind($kind, $x, $y);

		if (!$building)
		{
			$building['bid'] = 0;
			$building['lvl'] = 0;
			$building['ulvl'] = 0;
		}
		$building['kind'] = $kind;
	}

	if (!checkFreeBuildPosition($city, $building['kind']))
		return 0;
	$has_harbour = getHarbour($x, $y);
	$prices = prices($building['kind'], $building['lvl'], $has_harbour, $city);
	$helpres = bl\general\getResources($x, $y);
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
		$prices_upgr = upgradePrices($building['kind'], $building['ulvl'], $building['lvl'], $building['ulvl']);
		$res_values += $prices_upgr;
		$check = resourceCheck($res_values);
	}
	else
	{
		$res_values += $prices;
		$check = resourceCheck($res_values);
	}
	if ($check)
	{
		if ($upgrade)
		{
			$btime = buildTime($building['kind'], $building['lvl'], 1, $building['ulvl']);
			$endTime = new \DWDateTime();
			$endTime->add(new DateInterval('PT'.$btime.'S'));
		}
		else
		{
			$btime = buildTime($building['kind'], $building['lvl']);
			$endTime = new \DWDateTime();
			$endTime->add(new DateInterval('PT'.$btime.'S'));
		}

		if ($building['bid'] == 0)
			$building['bid'] = dal\buildings\insertBuilding($uid, $x, $y, $building['kind'], $buildplace);

		$erg2 = dal\buildings\startBuilding($building['bid'], $upgrade, $endTime);

		if ($building['bid'] && !$building['position'])
			dal\buildings\insertBuildPlace($building['bid'], $buildplace);

		if (!$upgrade)
		{
			$res['food'] = $helpres['food'] - $prices['food'];
			$res['wood'] = $helpres['wood'] - $prices['wood'];
			$res['rock'] = $helpres['rock'] - $prices['rock'];
			$res['iron'] = $helpres['iron'] - $prices['iron'];
			$res['paper'] = $helpres['paper'] - $prices['paper'];
			$res['koku'] = $helpres['koku'] - $prices['koku'];
			$erg3 = bl\resource\updateAll($res, $city);
		}
		else
		{
			$res['food'] = $helpres['food'] - $prices_upgr['food'];
			$res['wood'] = $helpres['wood'] - $prices_upgr['wood'];
			$res['rock'] = $helpres['rock'] - $prices_upgr['rock'];
			$res['iron'] = $helpres['iron'] - $prices_upgr['iron'];
			$res['paper'] = $helpres['paper'] - $prices_upgr['paper'];
			$res['koku'] = $helpres['koku'] - $prices_upgr['koku'];
			$erg3 = bl\resource\updateAll($res, $city);
		}

		if ($erg2 && $erg3)
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
function checkBuild($uid, $city) {
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$buildlist = dal\buildings\checkBuild($x, $y);

	if (is_array($buildlist))
	{
		$x = 0;
		foreach ($buildlist as $parts)
		{
			$endtime = \DWDateTime::createFromFormat('Y-m-d H:i:s', $parts['end_datetime']);
			$now = new \DWDateTime();
			if ($now >= $endtime)
				buildComplete($parts['bid']);
			else
			{
				$running[$x]['endtime'] = $endtime;
				$running[$x]['kind'] = $parts['kind'];
				$running[$x]['ulvl'] = $parts['ulvl'];
				if ($running[$x]['ulvl'] == 0 && getUpgradeable($running[$x]['kind']))
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
function buildComplete($bid)
{
	$build['bid'] = $bid;
	$build = dal\buildings\getBuildInfo($build['bid']);
	$is_upgradeable = getUpgradeable($build['kind']);

	if (($build['lvl'] == 0 || !$build['lvl']) && ($build['ulvl'] == 0 || !$build['ulvl']) && $is_upgradeable)
		$build['lvl'] = $build['ulvl'] = 1;
	elseif (!$build['upgrade'])
		$build['lvl']++;
	elseif ($build['upgrade'])
		$build['ulvl']++;

	dal\buildings\removeFromBuildList($build['bid']);
	dal\buildings\updateBuilding($build);
}

/**
 * check if there is allready a building of this type in the build list
 * @author Neithan
 * @param string $city
 * @param int $kind
 * @return int returns 1 if there is no other building of this type in the build list, otherwise 0
 */
function checkFreeBuildPosition($city, $kind)
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$res = dal\buildings\checkBuild($x, $y);

	if ($res)
	{
		foreach ($res as $parts)
		{
			if ((($kind >= 1 && $kind <= 6) || $kind == 22) && (($parts['kind'] >= 1 && $parts['kind'] <= 6) || $parts['kind'] == 22))
				return 0;
			elseif (($kind >= 7 && $kind <= 21) && ($parts['kind'] >= 7 && $parts['kind'] <= 21))
				return 0;
			elseif (($kind >= 23 && $kind <= 25) && ($parts['kind'] >= 23 && $parts['kind'] <= 25))
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
function checkGeishaAndFactory($city)
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$teahouse = dal\buildings\getBuildingByKind(9, $x, $y);
	$ninja = dal\buildings\getBuildingByKind(10, $x, $y);
	$blacksmith = dal\buildings\getBuildingByKind(14, $x, $y);
	$return['factory'] = $return['geisha'] = 0;

	if ($teahouse['ulvl'] == 4 && $ninja['ulvl'] == 3)
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
function getDefense($city, $not_built = 0)
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$defense = dal\buildings\getDefense($x, $y);

	$count = count($defense);
	$defenseList = array();
	for ($n = 0; $n < $count; $n++)
	{
		if ($not_built)
			$x = $n;
		else
			$x = $defense[$n]['position'];

		$defenseList[$x]['bid'] = $defense[$n]['bid'];
		$defenseList[$x]['kind'] = $defense[$n]['kind'];
		$defenseList[$x]['lvl'] = $defense[$n]['lvl'];
		$defenseList[$x]['ulvl'] = $defense[$n]['upgrade_lvl'];
		$defenseList[$x]['position'] = $defense[$n]['position'];
	}

	return $defenseList;
}

/**
 * get the specified building via kind and map position
 * @author Neithan
 * @param int $kind
 * @param string $city
 * @return array
 */
function getBuildingByKind($kind, $city)
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$buildings = dal\buildings\getBuildingByKind($kind, $x, $y);
	$build['lvl'] = $buildings['lvl'];
	$build['ulvl'] = $buildings['ulvl'];
	$build['bid'] = $buildings['bid'];
	$build['position'] = $buildings['position'];
	return $build;
}