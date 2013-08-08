<?php
namespace bl\resource;

/**
 * basic income of the area
 * @author Neithan
 * @param int $kind
 * @param string $city
 * @return int
 */
function basicIncome($kind, $city)
{
	$cityexp = explode(':', $city);
	$upgrade = \dal\resource\getUpgradeLevel(19, $cityexp[0], $cityexp[1]);

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
					$factor = getPaperPercent($x, $y);
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
function incomeUpgrades($type, $city)
{
	$cityexp = explode(':', $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$lvl = getLevel(19, $x, $y);
	$palace = getLevel(13, $x, $y);

	if ($palace)
	{
		if ($type == 1 ||  $type == 2 || $type == 3 || $type == 4)
		{
			if ($lvl <= 5)
				return (int)($lvl * 20);
			else
				return 0;

		}
		elseif ($type == 5 || $type == 6)
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
	}
	else
		return 0;
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
function buildingsIncome($type, $basic, $lvl, $city)
{
	$cityexp = explode(':', $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	$rate = 100;

	if ($type == 5)
		$rate = getPaperPercent($x, $y);

	return floor(($basic + (($lvl * 2) * ($rate / 100))) * (($lvl / 5) * ($rate / 100)));
}

/**
 * calculating the income per seconds, hour or day
 * @author Neithan
 * @param int $type
 * @param string $cycle s = seconds, h = hours, d = days
 * @param int $lvl
 * @param string $city
 * @return float|int
 */
function income($type, $cycle, $lvl, $city)
{
	require_once(__DIR__.'/unit.inc.php');

	$cityexp = explode(':', $city);
	$basic = basicIncome($type, $city);
	$upgrade = incomeUpgrades($type, $city);
	$uid = \dal\user\getUIDFromMapPosition($cityexp[0], $cityexp[1]);
	$costs['food'] = \bl\unit\calcTotalFoodCost($uid);
	$costs['koku'] = \bl\unit\calcTotalKokuCost($uid);
	$income = buildingsIncome($type, $basic, $lvl, $city)+$basic;
	//$paperprod = \bl\resource\getPaperPercent($x, $y);

	switch ($type)
	{
		case 1:
			$income -= $costs['food'];
			break;
		case 2:
			$income -= woodCosts($cityexp[0], $cityexp[1]);
			break;
		case 6:
			$income -= $costs['koku'];
			break;
	}

	$income += $income * ($upgrade/100);

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
 * @param int $paddy
 * @param int $lumberjack
 * @param int $quarry
 * @param int $ironmine
 * @param int $papermill
 * @param int $tradepost
 * @param string $city
 * @return array
 */
function newResources($city)
{
	require_once(__DIR__.'/buildings.php');

	$cityExp = explode(':', $city);
	$res = \bl\general\getResources($cityExp[0], $cityExp[1]);

	$paddy = \bl\buildings\selectBuilding($cityExp[0], $cityExp[1], 2);
	$lumberjack = \bl\buildings\selectBuilding($cityExp[0], $cityExp[1], 3);
	$quarry = \bl\buildings\selectBuilding($cityExp[0], $cityExp[1], 4);
	$ironmine = \bl\buildings\selectBuilding($cityExp[0], $cityExp[1], 5);
	$papermill = \bl\buildings\selectBuilding($cityExp[0], $cityExp[1], 6);
	$tradepost = \bl\buildings\selectBuilding($cityExp[0], $cityExp[1], 7);

	$food = (float)$res['food'];
	$wood = (float)$res['wood'];
	$rock = (float)$res['rock'];
	$iron = (float)$res['iron'];
	$paper = (float)$res['paper'];
	$koku = (float)$res['koku'];

	$lastDateTime = \DWDateTime::createFromFormat('Y-m-d H:i:s', $res['last_datetime']);
	$diff = $lastDateTime->diff(new \DWDateTime());
	$past_time = $diff->getSeconds();
	$storage = \bl\general\getMaxStorage($city);

	$newres['food'] = $food + ($past_time * income(1, 's', $paddy['lvl'], $city));
	$newres['wood'] = $wood + ($past_time * income(2, 's', $lumberjack['lvl'], $city));
	$newres['rock'] = $rock + ($past_time * income(3, 's', $quarry['lvl'], $city));
	$newres['iron'] = $iron + ($past_time * income(4, 's', $ironmine['lvl'], $city));
	$newres['paper'] = $paper + ($past_time * income(5, 's', $papermill['lvl'], $city));
	$newres['koku'] = $koku + ($past_time * income(6, 's', $tradepost['lvl'], $city));

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
	updateAll($newres, $city);
	return $newres;
}

/**
 * calculating the wood costs for producing paper
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return int
 */
function woodCosts($x, $y)
{
	$woodcutter = getLevel(2, $x, $y);
	$papermill = getLevel(5, $x, $y);
	$pbasic = basicIncome(5, $city);
	$pupgrade = incomeUpgrades(5, $city);
	$paperprod = buildingsIncome(5, $pbasic, $papermill, $x.':'.$y)+$pbasic;
	$paperprod = $paperprod+($paperprod*($pupgrade/100));
	$wbasic = basicIncome(2, $city);
	$wupgrade = incomeUpgrades(2, $city);
	$woodprod = buildingsIncome(2, $wbasic, $woodcutter, $x.':'.$y)+$wbasic;
	$woodprod = $woodprod+($woodprod*($wupgrade/100));

	if ($papermill)
	{
		if ($paperprod > $woodprod*2)
			return $woodprod;
		else
			return $paperprod/2;
	}
	else
		return 0;
}

/**
 * get the paper production rate
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return int
 */
function getPaperPercent($x, $y)
{
	return \dal\resource\getPaperPercent($x, $y);
}

/**
 * change the paper production rate
 * @author Neithan
 * @param int $percent
 * @param int $x
 * @param int $y
 * @return int
 */
function changePaperPercent($percent, $x, $y)
{
	return \dal\resource\changePaperPercent($percent, $x, $y);
}

/**
 * update the users resources
 * @author Neithan
 * @param array $res
 * @param string $city
 * @param int $time default 0
 * @return int
 */
function updateAll($res, $city)
{
	$cityexp = explode(':', $city);
	return \dal\resource\updateAll($res, $cityexp[0], $cityexp[1]);
}

/**
 * @author Neithan
 * @param string $city
 * @return array
 */
function getResourceBuildings($city)
{
	$city_exp = explode(':', $city);
	return \dal\resource\getResourceBuildings($city_exp[0], $city_exp[1]);
}

/**
 * @author Neithan
 * @param int $kind
 * @param int $x
 * @param int $y
 * @return int
 */
function getLevel($kind, $x, $y)
{
	$lvl = \dal\resource\getLevel($kind, $x, $y);
	if (!$lvl)
		$lvl = 0;
	return $lvl;
}

/**
 * Check if there is enough $resource present at $x,$y
 * @author siyb
 * @param int $x
 * @param int $y
 * @param int $resource
 * @param int $amount
 * @return type
 */
function hasEnoughOf($x, $y, $resource, $amount)
{
	return ($amount <= \dal\resource\returnResourceAmount($x, $y, $resource));
}

/**
 * Will add the amout specified as $value to $uid's resource inventory of
 * $resource. This function accepts negative values as well so that it can be
 * used to remove units from the resource stock as well.
 * @author Neithan
 * @param String $resource
 * @param int $value
 * @param int $x
 * @param int $y
 */
function addToResources($resource, $value, $city)
{
	$cityExp = explode(':', $city);
	\dal\resource\addToResources($resource, $value, $cityExp[0], $cityExp[1]);
}