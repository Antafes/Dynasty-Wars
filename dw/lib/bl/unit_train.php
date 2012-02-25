<?php
namespace bl\unit\train;

/**
 * check if an unit producing building is build
 * @author Neithan
 * @param string $city
 * @return array
 */
function checkBuildings($city)
{
	$kinds = array(7,8,9,10,11,12,15,17,18,20,21);

	$buildings = array();
	foreach($kinds as $kind)
		$buildings[$kind] = \bl\buildings\getBuildingByKind($kind, $city);

	return $buildings;
}

/**
 * get the prices per unit
 * @author Neithan
 * @param int $kind
 * @return array
 */
function unitPrices($kind)
{
	$prices = \dal\unit\train\unitPrices($kind);

	$mainCity = $_SESSION['user']->getMainCity();
	$mainbuilding = \dal\buildings\getBuildingByKind(19, $mainCity['map_x'], $mainCity['map_y']);
	$paper = \dal\buildings\getBuildingByKind(5, $mainCity['map_x'], $mainCity['map_y']);
	$koku = \dal\buildings\getBuildingByKind(6, $mainCity['map_x'], $mainCity['map_y']);

	if ($paper['lvl'] == 0 && $mainbuilding['ulvl'] <= 1)
		$prices['paper'] = 0;

	if ($koku['lvl'] == 0 && $mainbuilding['ulvl'] <= 1)
		$prices['koku'] = 0;

	foreach ($prices as $key => $value)
		$prices[$key.'_formatted'] = \util\math\numberFormat($value, 0);

	return $prices;
}

/**
 * calculate how many units of the specified kind can be produced with the current amount of ressources
 * @author Neithan
 * @param int $kind
 * @param string $city
 * @return int
 */
function maxUnits($kind, $city)
{
	$cityexp = explode(":", $city);
	$x = intval($cityexp[0]);
	$y = intval($cityexp[1]);
	$costs = unitPrices($kind);
	$res = \bl\general\getResources($x, $y);
	if ($costs["food"])
		$units["food"] = floor($res["food"]/$costs["food"]);
	if ($costs["wood"])
		$units["wood"] = floor($res["wood"]/$costs["wood"]);
	if ($costs["rock"])
		$units["rock"] = floor($res["rock"]/$costs["rock"]);
	if ($costs["iron"])
		$units["iron"] = floor($res["iron"]/$costs["iron"]);
	if ($costs["paper"])
		$units["paper"] = floor($res["paper"]/$costs["paper"]);
	if ($costs["koku"])
		$units["koku"] = floor($res["koku"]/$costs["koku"]);
	array_walk($units, '\bl\unit\train\maxUnitsHelper');
	return min($units);
}

/**
 * additional function for the above function
 * @author Neithan
 * @param int $value
 * @return void
 */
function maxUnitsHelper(&$value)
{
	if ($value < 0)
        $value = 0;
}

/**
 * calculate the time needed to produce these units
 * @author Neithan
 * @param int $kind
 * @return string
 */
function trainTime($kind)
{
	return \dal\unit\train\trainTime($kind);
}

/**
 * start the training of an unit
 * @author Neithan
 * @param int $kind
 * @param int $count
 * @param int $uid
 * @param string $city
 * @return int returns 1 on success, otherwise 0
 */
function train($kind, $count, $uid, $city)
{
	if ($count)
	{
		$max_units = maxUnits($kind, $city);
		if ($count > $max_units)
			$count = $max_units;
		$prices = unitPrices($kind);
		$sum_prices["food"] = $prices["food"]*$count;
		$sum_prices["wood"] = $prices["wood"]*$count;
		$sum_prices["rock"] = $prices["rock"]*$count;
		$sum_prices["iron"] = $prices["iron"]*$count;
		$sum_prices["paper"] = $prices["paper"]*$count;
		$sum_prices["koku"] = $prices["koku"]*$count;
		$endTime = new \DWDateTime();
		$endTime->add(new DateInterval('PT'.(trainTime($kind) * $count)));
		$erg1 = \dal\unit\train\removeResources($sum_prices, $uid);
		$erg2 = \dal\unit\train\startTrain($kind, $uid, $count, $endTime, $city);

		if ($erg1 && $erg2)
			return 1;
		else
			return 0;
	}
	else
		return 0;
}

/**
 * check for training
 * @author Neithan
 * @param int $uid
 * @param string $city
 * @return array
 */
function checkTraining($uid, $city)
{
	$units = \dal\unit\train\checkTraining($uid, $city);

	if ($units)
	{
		foreach ($units as $unit)
		{
			$endtime = \DWDateTime::createFromFormat('Y-m-d H:i:s', $unit['end_datetime']);
			$now = new \DWDateTime();
			if ($now >= $endtime)
			{
				$unit['uid'] = $uid;
				trainComplete($unit);
			}
			else
			{
				if ($city == $unit['city'])
				{
					$running = array(
						'endtime' => $endtime,
						'kind' => $unit['kind'],
						'count' => $unit['count'],
					);
				}
			}
		}

		if ($running["kind"])
			$running["ok"] = 1;
		else
			$running["ok"] = 0;

		return $running;
	}
	else
		return false;
}

/**
 * complete the training of units
 * @author Neithan
 * @param array $valuelist
 * @return int returns 1 on success, otherwise 0
 */
function trainComplete($valuelist)
{
	$cityexp = explode(":", $valuelist['city']);
	$map_x = intval($cityexp[0]);
	$map_y = intval($cityexp[1]);
	\dal\unit\train\removeFromTrainList($valuelist['tid']);
	$unid = \dal\unit\train\checkPosition($valuelist['uid'], $map_x, $map_y, $valuelist['kind']);
	if ($unid)
		$erg3 = \dal\unit\train\addUnit($valuelist['count'], $unid);
	else
		$erg3 = \dal\unit\train\newUnit($valuelist['uid'], $valuelist['kind'], $valuelist['count'], $map_x, $map_y);
	if ($erg1 && $erg3)
		return 1;
	else
		return 0;
}

/**
 * get the name of the units picture
 * @author Neithan
 * @param int $kind
 * @param int $ulvl
 * @return string
 */
function getUnitPicture($kind)
{
	$picturesArray = array(
		1 => 'ashigaru_archer',
		2 => 'yari_ashigaru',
		3 => 'shinobi',
		4 => 'samurai_archer',
		5 => 'ninja',
		6 => 'mediator',
		7 => 'yari_samurai',
		8 => 'riding_archer',
		9 => 'samurai_crossbow',
		10 => 'yari_cavalry',
		11 => 'no-dachi_samurai',
		12 => 'warmonk',
		13 => 'priest',
		14 => 'naginata_samurai',
		15 => 'heavy_cavalry',
		16 => 'arquebus',
		17 => 'musket',
		18 => 'geisha',
	);

	if (file_exists('pictures/units/'.$picturesArray[$kind].'.png'))
		return $picturesArray[$kind];
	else
		return 'no_picture';
}