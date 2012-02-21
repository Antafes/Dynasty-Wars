<?php
/**
 * basic income of the area
 * @author Neithan
 * @param int $kind
 * @param string $city
 * @return int
 */
function lib_bl_resource_basicIncome($kind, $city) {
	$cityexp = explode(':', $city);
	$upgrade = lib_dal_resource_getUpgrLvl(19, $cityexp[0], $cityexp[1]);
	if ($upgrade)
	{
		switch ($kind)
		{
			case 1:
				return 250;
				break;
			case 2:
				return 200;
				break;
			case 3:
			case 4:
				return 100;
				break;
			case 5:
				if ($upgrade >= 2)
				{
					$factor = lib_bl_resource_getPaperPercent($x, $y);
					return 100*($factor/100);
				}
				break;
			case 6:
				if ($upgrade >= 2)
					return 200;
				break;
			default:
				return 0;
		}
	}
	else
	{
		switch($kind)
		{
			case 1:
				return 125;
				break;
			case 2:
				return 100;
				break;
			case 3:
			case 4:
				return 50;
				break;
			case 5:
			case 6:
			default:
				return 0;
		}
	}
}

/**
 * income upgrades
 * @author Neithan
 * @param int $type
 * @param string $city
 * @return int
 */
function lib_bl_resource_incomeUpgrades($type, $city) {
	$cityexp = explode(':', $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$lvl = lib_bl_resource_getLvl(19, $x, $y);
	$palace = lib_bl_resource_getLvl(13, $x, $y);
	if ($palace)
	{
		if ($type == 1 ||  $type == 2 || $type == 3 || $type == 4)
		{
			if ($lvl <= 5)
				return (int)($lvl * 20);
			else
				return 0;

		} elseif ($type == 5 || $type == 6)
		{
			if ($lvl < 3)
				return (int)($lvl * 20);
			elseif($lvl <= 4)
				return (int)(($lvl + 1) * 20);
			else
				return 0;


		}
		elseif ($type == 7)
		{
			if ($lvl <= 3)
				return (int)(($lvl - 1) * 40 + 20);
			else
				return 0;
		}
	} else {
		return 0;
	}
}

/**
 * calculating the income of the building
 * @author Neithan
 * @param int $type the type of ressource
 * @param int $basic the basicincome of this ressource
 * @param int $lvl the level of the ressourcegenerating building
 * @param string $city the current active city (coordinates)
 * @return int
 */
function lib_bl_resource_bIncome($type, $basic, $lvl, $city)
{
	$cityexp = explode(':', $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$upgrade = lib_dal_resource_getUpgrLvl(19, $x, $y);
	if ($type == 5)
		$rate = lib_bl_resource_getPaperPercent($x, $y);
	else
		$rate = 100;
	return floor(($basic+(($lvl*2)*($rate/100)))*(($lvl/5)*($rate/100)));
}

/**
 * calculating the income per hour or per day
 * @author Neithan
 * @param int $type
 * @param string $cycle s = seconds, h = hours, d = days
 * @param int $lvl
 * @param string $city
 * @return float|int
 */
function lib_bl_resource_income($type, $cycle, $lvl, $city) {
	include_once('lib/bl/unit.inc.php');
	$cityexp = explode(':', $city);
	$basic = lib_bl_resource_basicIncome($type, $city);
	$upgrade = lib_bl_resource_incomeUpgrades($type, $city);
	$costs['food'] = lib_bl_unit_calcTotalFoodCost($uid);
	$costs['koku'] = lib_bl_unit_calcTotalKokuCost($uid);
	$income = lib_bl_resource_bIncome($type, $basic, $lvl, $city)+$basic;
	//$paperprod = lib_bl_resource_getpaperpercent($x, $y);

	switch ($type)
	{
		case 1:
			$income -= $costs['food'];
			break;
		case 2:
			$income -= lib_bl_resource_woodCosts($cityexp[0], $cityexp[1]);
			break;
		case 6:
			$income -= $costs['koku'];
			break;
	}

	$income = $income+($income*($upgrade/100));
	if ($income < 0)
		$income = 0;

	switch($cycle)
	{
		case 's':
			return $income/(60*60);
			break;
		case 'h':
			return round($income, 0);
			break;
		case 'd':
			return round($income*24, 0);
			break;
	}
}

/**
 * calculating the new ressources
 * @author Neithan
 * @param int $range
 * @param int $lumberjack
 * @param int $quarry
 * @param int $ironmine
 * @param int $papermill
 * @param int $tradepost
 * @param string $city
 * @return array
 */
function lib_bl_resource_newRes($range, $lumberjack, $quarry, $ironmine, $papermill, $tradepost, $city)
{
	$cityexp = explode(':', $city);
	$res = lib_bl_general_getRes($cityexp[0], $cityexp[1]);

	$food = (float)$res['food'];
	$wood = (float)$res['wood'];
	$rock = (float)$res['rock'];
	$iron = (float)$res['iron'];
	$paper = (float)$res['paper'];
	$koku = (float)$res['koku'];
	$lastDateTime = DWDateTime::createFromFormat('Y-m-d H:i:s', $res['last_datetime']);
	$diff = $lastDateTime->diff(new DWDateTime());
	$past_time = $diff->getSeconds();
	$newres['food'] = $food + ($past_time * lib_bl_resource_income(1, 's', $range, $city));
	$newres['wood'] = $wood + ($past_time * lib_bl_resource_income(2, 's', $lumberjack, $city));
	$newres['rock'] = $rock + ($past_time * lib_bl_resource_income(3, 's', $quarry, $city));
	$newres['iron'] = $iron + ($past_time * lib_bl_resource_income(4, 's', $ironmine, $city));
	$newres['paper'] = $paper + ($past_time * lib_bl_resource_income(5, 's', $papermill, $city));
	$newres['koku'] = $koku + ($past_time * lib_bl_resource_income(6, 's', $tradepost, $city));
	$storage = lib_bl_general_getMaxStorage($city);
	if ($food >= $storage)
		$newres['food'] = $food;
	if ($wood >= $storage)
		$newres['wood'] = $wood;
	if ($rock >= $storage)
		$newres['rock'] = $rock;
	if ($iron >= $storage)
		$newres['iron'] = $iron;
	if ($paper >= $storage)
		$newres['paper'] = $paper;
	if ($koku >= $storage)
		$newres['koku'] = $koku;
	array_map(floor, $newres);
	lib_bl_resource_updateAll($newres, $city);
	return $newres;
}

/**
 * calculating the wood costs for producing paper
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_bl_resource_woodCosts($x, $y) {
	$woodcutter = lib_bl_resource_getLvl(2, $x, $y);
	$papermill = lib_bl_resource_getLvl(5, $x, $y);
	$pbasic = lib_bl_resource_basicIncome(5, $city);
	$pupgrade = lib_bl_resource_incomeUpgrades(5, $city);
	$paperprod = lib_bl_resource_bIncome(5, $pbasic, $papermill, $x.':'.$y)+$pbasic;
	$paperprod = $paperprod+($paperprod*($pupgrade/100));
	$wbasic = lib_bl_resource_basicIncome(2, $city);
	$wupgrade = lib_bl_resource_incomeUpgrades(2, $city);
	$woodprod = lib_bl_resource_bIncome(2, $wbasic, $woodcutter, $x.':'.$y)+$wbasic;
	$woodprod = $woodprod+($woodprod*($wupgrade/100));
	if ($papermill) {
		if ($paperprod > $woodprod*2) {
			return $woodprod;
		} else {
			return $paperprod/2;
		}
	} else {
		return 0;
	}
}

/**
 * get the paper production rate
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_bl_resource_getPaperPercent($x, $y)
{
	return lib_dal_resource_getPaperPercent($x, $y);
}

/**
 * change the paper production rate
 * @author Neithan
 * @param int $percent
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_bl_resource_changePaperPercent($percent, $x, $y) {
	return lib_dal_resource_changePaperPercent($percent, $x, $y);
}

/**
 * update the users resources
 * @author Neithan
 * @param array $res
 * @param string $city
 * @param int $time default 0
 * @return int
 */
function lib_bl_resource_updateAll($res, $city, $time=0)
{
	$cityexp = explode(':', $city);
	if (!$time)
		$time = time();
	return lib_dal_resource_updateAll($res, $time, $cityexp[0], $cityexp[1]);
}

/**
 * @author Neithan
 * @param string $city
 * @return array
 */
function lib_bl_resource_getResourceBuildings($city)
{
	$city_exp = explode(':', $city);
	return lib_dal_resource_getResourceBuildings($city_exp[0], $city_exp[1]);
}

/**
 * @author Neithan
 * @param int $kind
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_bl_resource_getLvl($kind, $x, $y)
{
	$lvl = lib_dal_resource_getLvl(19, $x, $y);
	if (!$lvl)
		$lvl = 0;
	return $lvl;
}

/**
 * Check if there is enough $resource present at $x,$y
 * @author siyb
 */
function lib_bl_resource_hasEnoughOf($x, $y, $resource, $amount) {
	return ($amount <= lib_dal_resource_returnResourceAmount($x, $y, $sellResource));
}
?>