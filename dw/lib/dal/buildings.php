<?php
/**
 * select all buildings
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return array
 */
function lib_dal_buildings_selectAll($x, $y)
{
	$sql = 'SELECT * FROM `dw_buildings`
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
			AND `kind` < 23
	';
	return util\mysql\query($sql);
}
/**
 * can the city build an harbour?
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_dal_buildings_getHarbour($x, $y)
{
	$sql = 'SELECT `harbour` FROM `dw_map`
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"';
	return util\mysql\query($sql);
}
/**
 * select the choosen building from the position
 * @author Neithan
 * @param int $x
 * @param int $y
 * @param int $pos
 * @return array
 */
function lib_dal_buildings_selectBuilding($x, $y, $pos)
{
	if ($pos)
		$buildplace = 'AND `position` = "'.mysql_real_escape_string($pos).'"';
	$sql = 'SELECT * FROM `dw_buildings`
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
			'.$buildplace.'
	';
	return util\mysql\query($sql, true);
}
/**
 * calculating the build prices
 * @author Neithan
 * @param int $kind
 * @return array
 */
function lib_dal_buildings_prices($kind)
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
		WHERE kind = "'.mysql_real_escape_string($kind).'"
	';
	return util\mysql\query($sql);
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
function lib_dal_buildings_upgradePrices($kind, $kind_u, $lvl, $upgrade_lvl)
{
	$sql = 'SELECT food, wood, rock, iron, paper, koku FROM dw_costs_b_upgr
		WHERE kind="'.mysql_real_escape_string($kind).'"
			AND `kind_u` = "'.mysql_real_escape_string($kind_u).'"
	';
$GLOBALS['firePHP']->log($sql, 'upgradePrices sql');
	return util\mysql\query($sql);
}
/**
 * check religion
 * @author Neithan
 * @param int $uid
 * @return int
 */
function lib_dal_buildings_checkReligion($uid)
{
	$sql = 'SELECT `religion` FROM `dw_user` WHERE `uid` = '.mysql_real_escape_string($uid);
	return util\mysql\query($sql);
}
/**
 * get the specified building via kind and map position
 * @author Neithan
 * @param int $kind
 * @param int $x
 * @param int $y
 * @return array
 */
function lib_dal_buildings_getBuildingByKind($kind, $x, $y)
{
	$sql = '
		SELECT
			`lvl`,
			`upgrade_lvl` AS `ulvl`,
			`bid`,
			`position`
		FROM `dw_buildings`
		WHERE `kind` = '.mysql_real_escape_string($kind).'
			AND `map_x` = '.mysql_real_escape_string($x).'
			AND `map_y` = '.mysql_real_escape_string($y);
	return util\mysql\query($sql);
}
/**
 * get the build or upgrade time
 * @author Neithan
 * @param int $kind
 * @param int $upgrade
 * @param int $u_lvl
 * @return int
 */
function lib_dal_buildings_getTime($kind, $upgrade, $u_lvl)
{
	if ($upgrade == 0)
		$sql = 'SELECT `btime` FROM `dw_buildtimes` WHERE `kind` = '.mysql_real_escape_string($kind);
	elseif ($upgrade == 1)
		$sql = 'SELECT `upgrtime` FROM `dw_buildtimes_upgr` WHERE `kind` = '.mysql_real_escape_string($kind).' AND `kind_u` = '.mysql_real_escape_string($u_lvl);
	return util\mysql\query($sql);
}
/**
 * check for running build
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return array
 */
function lib_dal_buildings_checkBuild($x, $y)
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
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
		ORDER BY `end_datetime` ASC
	';
$GLOBALS['firePHP']->log($sql, 'checkBuild sql');
	return util\mysql\query($sql, true);
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
function lib_dal_buildings_insertBuilding($uid, $x, $y, $kind, $position)
{
	$sql = '
		INSERT INTO `dw_buildings` (
			`uid`,
			`map_x`,
			`map_y`,
			`kind`,
			`position`
		) VALUES (
			"'.mysql_real_escape_string($uid).'",
			"'.mysql_real_escape_string($x).'",
			"'.mysql_real_escape_string($y).'",
			"'.mysql_real_escape_string($kind).'",
			"'.mysql_real_escape_string($position).'"
		)
	';
	return util\mysql\query($sql);
}
/**
 * insert a newly started build
 * @author Neithan
 * @param int $bid
 * @param int $upgrade
 * @param DWDateTime $endTime
 * @return int
 */
function lib_dal_buildings_startBuilding($bid, $upgrade, DWDateTime $endTime)
{
	$sql = '
		INSERT INTO `dw_build` (
			`bid`,
			`upgrade`,
			`start_datetime`,
			`end_datetime`
		) VALUES (
			"'.mysql_real_escape_string($bid).'",
			"'.mysql_real_escape_string($upgrade).'",
			NOW(),
			"'.mysql_real_escape_string($endTime->format('Y-m-d H:i:s')).'"
		)
	';
	return util\mysql\query($sql);
}

/**
 * insert a buildplace in an existing building
 * @author Neithan
 * @param int $bid
 * @param int $buildplace
 */
function lib_dal_buildings_insertBuildPlace($bid, $buildplace)
{
	$sql = '
		UPDATE `dw_buildings` SET `position` = "'.mysql_real_escape_string($buildplace).'"
		WHERE `bid` = "'.mysql_real_escape_string($bid).'"
	';
	util\mysql\query($sql);
}
/**
 * get special infos for build completion
 * @author Neithan
 * @param int $bid
 * @return array
 */
function lib_dal_buildings_getBuildInfo($bid)
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
		WHERE `dw_build`.`bid` = "'.mysql_real_escape_string($bid).'"
	';
	return util\mysql\query($sql);
}
/**
 * remove the completed building from the build list
 * @author Neithan
 * @param <int> $bid
 */
function lib_dal_buildings_removeFromBuildList($bid)
{
	$sql = 'DELETE FROM `dw_build` WHERE `bid` = "'.mysql_real_escape_string($bid).'"';
	util\mysql\query($sql);
}
/**
 * update the completed building
 * @author Neithan
 * @param array $valuelist array([lvl], [ulvl], [bid])
 */
function lib_dal_buildings_updateBuilding($valuelist)
{
	$sql = '
		UPDATE `dw_buildings`
		SET `lvl` = "'.mysql_real_escape_string($valuelist['lvl']).'",
			`upgrade_lvl` = "'.mysql_real_escape_string($valuelist['ulvl']).'"
		WHERE `bid` = "'.mysql_real_escape_string($valuelist['bid']).'"
	';
	util\mysql\query($sql);
}
/**
 * get the defense buildings
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return array
 */
function lib_dal_buildings_getDefense($x, $y)
{
	$sql = 'SELECT * FROM `dw_buildings`
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
			AND `kind` > 22
	';
	return util\mysql\query($sql, true);
}

/**
 * get the attack and defense values of the specified building
 * @author Neithan
 * @param int $kind
 * @param int $upgrade_lvl
 * @return array
 */
function lib_dal_buildings_getStats($kind, $upgrade_lvl)
{
	$sql = '
		SELECT
			defense,
			attack
		FROM dw_building_stats
		WHERE kind = '.mysql_real_escape_string($kind).'
			AND upgrade_lvl = '.mysql_real_escape_string($upgrade_lvl).'
	';
	return util\mysql\query($sql);
}
?>