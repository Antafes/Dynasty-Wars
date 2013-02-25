<?php
namespace dal\buildings;

/**
 * select all buildings
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return array
 */
function selectAll($x, $y)
{
	$sql = 'SELECT * FROM `dw_buildings`
		WHERE `map_x` = '.\util\mysql\sqlval($x).'
			AND `map_y` = '.\util\mysql\sqlval($y).'
			AND `kind` < 23
	';
	return \util\mysql\query($sql);
}

/**
 * can the city build an harbour?
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return int
 */
function getHarbour($x, $y)
{
	$sql = 'SELECT `harbour` FROM `dw_map`
		WHERE `map_x` = '.\util\mysql\sqlval($x).'
			AND `map_y` = '.\util\mysql\sqlval($y).'';
	return \util\mysql\query($sql);
}

/**
 * select the choosen building from the position
 * @author Neithan
 * @param int $x
 * @param int $y
 * @param int $pos
 * @return array
 */
function selectBuilding($x, $y, $pos)
{
	if ($pos)
		$buildplace = 'AND `position` = '.\util\mysql\sqlval($pos).'';
	$sql = 'SELECT * FROM `dw_buildings`
		WHERE `map_x` = '.\util\mysql\sqlval($x).'
			AND `map_y` = '.\util\mysql\sqlval($y).'
			'.$buildplace.'
	';
	return \util\mysql\query($sql);
}

/**
 * calculating the build prices
 * @author Neithan
 * @param int $kind
 * @return array
 */
function prices($kind)
{
	$sql = '
		SELECT
			food,
			wood,
			rock,
			iron,
			paper,
			koku
		FROM dw_costs_b
		WHERE kind = '.\util\mysql\sqlval($kind).'
	';
	return \util\mysql\query($sql);
}

/**
 * selecting the upgrade prices
 * @author Neithan
 * @param int $kind
 * @param int $kind_u
 * @param int $lvl
 * @param int $upgrade_lvl
 * @return array
 */
function upgradePrices($kind, $kind_u, $lvl, $upgrade_lvl)
{
	$sql = 'SELECT food, wood, rock, iron, paper, koku FROM dw_costs_b_upgr
		WHERE kind = '.\util\mysql\sqlval($kind).'
			AND `kind_u` = '.\util\mysql\sqlval($kind_u).'
	';
	return \util\mysql\query($sql);
}

/**
 * check religion
 * @author Neithan
 * @param int $uid
 * @return int
 */
function checkReligion($uid)
{
	$sql = 'SELECT `religion` FROM `dw_user` WHERE `uid` = '.\util\mysql\sqlval($uid);
	return \util\mysql\query($sql);
}

/**
 * get the specified building via kind and map position
 * @author Neithan
 * @param int $kind
 * @param int $x
 * @param int $y
 * @return array
 */
function getBuildingByKind($kind, $x, $y)
{
	$sql = '
		SELECT
			`lvl`,
			`upgrade_lvl` AS `ulvl`,
			`bid`,
			`position`
		FROM `dw_buildings`
		WHERE `kind` = '.\util\mysql\sqlval($kind).'
			AND `map_x` = '.\util\mysql\sqlval($x).'
			AND `map_y` = '.\util\mysql\sqlval($y);
	return \util\mysql\query($sql);
}

/**
 * get the build or upgrade time
 * @author Neithan
 * @param int $kind
 * @param int $upgrade
 * @param int $u_lvl
 * @return int
 */
function getTime($kind, $upgrade, $u_lvl)
{
	if ($upgrade == 0)
		$sql = 'SELECT `btime` FROM `dw_buildtimes` WHERE `kind` = '.\util\mysql\sqlval($kind);
	elseif ($upgrade == 1)
		$sql = 'SELECT `upgrtime` FROM `dw_buildtimes_upgr` WHERE `kind` = '.\util\mysql\sqlval($kind).' AND `kind_u` = '.\util\mysql\sqlval($u_lvl);
	return \util\mysql\query($sql);
}

/**
 * check for running build
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return array
 */
function checkBuild($x, $y)
{
	$sql = '
		SELECT
			`dw_buildings`.`bid`,
			`end_datetime`,
			`kind`,
			`dw_buildings`.`upgrade_lvl` AS `ulvl`,
			`dw_buildings`.`position`
		FROM `dw_buildings`
		INNER JOIN `dw_build` USING (`bid`)
		WHERE `map_x` = '.\util\mysql\sqlval($x).'
			AND `map_y` = '.\util\mysql\sqlval($y).'
		ORDER BY `end_datetime` ASC
	';

	return \util\mysql\query($sql, true);
}

/**
 * insert a new build building
 * @author Neithan
 * @param int $uid
 * @param int $x
 * @param int $y
 * @param int $kind
 * @param int $position
 * @return int
 */
function insertBuilding($uid, $x, $y, $kind, $position)
{
	$sql = '
		INSERT INTO `dw_buildings` (
			`uid`,
			`map_x`,
			`map_y`,
			`kind`,
			`position`
		) VALUES (
			'.\util\mysql\sqlval($uid).',
			'.\util\mysql\sqlval($x).',
			'.\util\mysql\sqlval($y).',
			'.\util\mysql\sqlval($kind).',
			'.\util\mysql\sqlval($position).'
		)
	';
	return \util\mysql\query($sql);
}

/**
 * insert a newly started build
 * @author Neithan
 * @param int $bid
 * @param int $upgrade
 * @param \DWDateTime $endTime
 * @return int
 */
function startBuilding($bid, $upgrade, \DWDateTime $endTime)
{
	$sql = '
		INSERT INTO `dw_build` (
			`bid`,
			`upgrade`,
			`start_datetime`,
			`end_datetime`
		) VALUES (
			'.\util\mysql\sqlval($bid).',
			'.\util\mysql\sqlval($upgrade).',
			NOW(),
			'.\util\mysql\sqlval($endTime->format()).'
		)
	';
	return \util\mysql\query($sql);
}

/**
 * insert a buildplace in an existing building
 * @author Neithan
 * @param int $bid
 * @param int $buildplace
 */
function insertBuildPlace($bid, $buildplace)
{
	$sql = '
		UPDATE `dw_buildings` SET `position` = '.\util\mysql\sqlval($buildplace).'
		WHERE `bid` = '.\util\mysql\sqlval($bid).'
	';
	\util\mysql\query($sql);
}

/**
 * get special infos for build completion
 * @author Neithan
 * @param int $bid
 * @return array
 */
function getBuildInfo($bid)
{
	$sql = '
		SELECT
			`dw_build`.`bid`,
			`upgrade`,
			`lvl`,
			`upgrade_lvl` AS `ulvl`,
			`kind`
		FROM `dw_build`
		LEFT OUTER JOIN `dw_buildings` ON `dw_build`.`bid` = `dw_buildings`.`bid`
		WHERE `dw_build`.`bid` = '.\util\mysql\sqlval($bid).'
	';
	return \util\mysql\query($sql);
}

/**
 * remove the completed building from the build list
 * @author Neithan
 * @param int $bid
 */
function removeFromBuildList($bid)
{
	$sql = 'DELETE FROM `dw_build` WHERE `bid` = '.\util\mysql\sqlval($bid).'';
	\util\mysql\query($sql);
}

/**
 * update the completed building
 * @author Neithan
 * @param array $valuelist array([lvl], [ulvl], [bid])
 */
function updateBuilding($valuelist)
{
	$sql = '
		UPDATE `dw_buildings`
		SET `lvl` = '.\util\mysql\sqlval($valuelist['lvl']).',
			`upgrade_lvl` = '.\util\mysql\sqlval($valuelist['ulvl']).'
		WHERE `bid` = '.\util\mysql\sqlval($valuelist['bid']).'
	';
	\util\mysql\query($sql);
}

/**
 * get the defense buildings
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return array
 */
function getDefense($x, $y)
{
	$sql = 'SELECT * FROM `dw_buildings`
		WHERE `map_x` = '.\util\mysql\sqlval($x).'
			AND `map_y` = '.\util\mysql\sqlval($y).'
			AND `kind` > 22
	';
	return \util\mysql\query($sql, true);
}

/**
 * get the attack and defense values of the specified building
 * @author Neithan
 * @param int $kind
 * @param int $upgrade_lvl
 * @return array
 */
function getStats($kind, $upgrade_lvl)
{
	$sql = '
		SELECT
			defense,
			attack
		FROM dw_building_stats
		WHERE kind = '.\util\mysql\sqlval($kind).'
			AND upgrade_lvl = '.\util\mysql\sqlval($upgrade_lvl).'
	';
	return \util\mysql\query($sql);
}