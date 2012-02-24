<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */

namespace dal\resource;

/**
 * Will add the amout specified as $value to the user at position $x:$y resource
 * inventory of $resource. This function accepts negative values as well so that
 * it can be used to remove units from the resource stock as well.
 * @author siyb
 * @param <String> $resource
 * @param <float> $value
 * @param <int> $x
 * @param <int> $y
 */
function addToResources($resource, $value, $x, $y)
{
	$sql = '
		UPDATE `dw_res`
		SET `'.mysql_real_escape_string($resource).'` = `'.mysql_real_escape_string($resource).'` + '.mysql_real_escape_string($value).'
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
	';
    util\mysql\query($sql);
}

/**
 * Returns the amount of $resource that is currently in the stock of the user
 * whose position is $x:$y
 * @param <int> $x
 * @param <int> $y
 * @param <int> $resource
 * @return float
 */
function returnResourceAmount($x, $y, $resource)
{
	$sql = '
		SELECT `'.mysql_real_escape_string($resource).'` FROM `dw_res`
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
	';
    return util\mysql\query($sql);
}

/**
 * get the upgradelevel of the defined building
 * @author Neithan
 * @param int $kind
 * @param int $x
 * @param int $y
 * @return int
 */
function getUpgradeLevel($kind, $x, $y)
{
	$sql = 'SELECT `upgrade_lvl` FROM `dw_buildings`
		WHERE `kind` = "'.mysql_real_escape_string($kind).'"
			AND `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
	';
	return util\mysql\query($sql);
}

/**
 * get the level of the defined research
 * @author Neithan
 * @param int $uid
 * @param int $type
 * @return int
 */
function getResearchLevel($uid, $type)
{
	$sql = 'SELECT `lvl` FROM `dw_research`
		WHERE `uid` = "'.mysql_real_escape_string($uid).'"
			AND `type` = "'.mysql_real_escape_string($type).'"
	';
	return util\mysql\query($sql);
}

/**
 * get the level of the defined building
 * @author Neithan
 * @param int $kind
 * @param int $x
 * @param int $y
 * @return int
 */
function getLevel($kind, $x, $y)
{
	$sql = 'SELECT `lvl` FROM `dw_buildings`
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
			AND `kind` = "'.mysql_real_escape_string($kind).'"
	';
	return util\mysql\query($sql);
}

/**
 * update the users resources
 * @author Neithan
 * @param array $res array([food], [wood], [rock], [iron], [paper], [koku])
 * @param int $x
 * @param int $y
 * @return int
 */
function updateAll($res, $x, $y)
{
	$sql = '
		UPDATE `dw_res`
		SET `last_datetime` = NOW(),
			`food` = "'.mysql_real_escape_string($res['food']).'",
			`wood` = "'.mysql_real_escape_string($res['wood']).'",
			`rock` = "'.mysql_real_escape_string($res['rock']).'",
			`iron` = "'.mysql_real_escape_string($res['iron']).'",
			`paper` = "'.mysql_real_escape_string($res['paper']).'",
			`koku` = "'.mysql_real_escape_string($res['koku']).'"
		WHERE `map_x` = '.mysql_real_escape_string($x).'
			AND `map_y` = '.mysql_real_escape_string($y).'
	';
	return util\mysql\query($sql);
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
	$sql = '
		SELECT paper_prod FROM dw_res
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
	';
	return util\mysql\query($sql);
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
	$sql = '
		UPDATE dw_res
		SET paper_prod="'.mysql_real_escape_string($percent).'"
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
	';
	return util\mysql\query($sql);
}

/**
 * returns an array with all resource buildings
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return array
 */
function getResourceBuildings($x, $y)
{
	$sql = '
		SELECT kind, lvl FROM dw_buildings
		WHERE map_x = '.mysql_real_escape_string($x).'
			AND map_y = '.mysql_real_escape_string($y).'
			AND kind BETWEEN 1 AND 6
	';
	return util\mysql\query($sql, true);
}