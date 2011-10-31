<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */

/**
 * Will add the amout specified as $value to $uid's resource inventory of
 * $resource. This function accepts negative values as well so that it can be
 * used to remove units from the resource stock as well.
 * @author siyb
 * @param <int> $uid
 * @param <String> $resource
 * @param <float> $value
 */
function lib_dal_resource_addToResources($resource, $value, $x, $y)
{
	$sql = '
		UPDATE `dw_res`
		SET `'.mysql_real_escape_string($resource).'` = `'.mysql_real_escape_string($resource).'` + '.mysql_real_escape_string($value).'
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
	';
    lib_util_mysqlQuery($sql);
}

/**
 * Returns the amount of $resource that is currently in the stock of the user
 * whose id is $uid
 * @param <type> $uid
 * @param <type> $resource
 * @return float
 */
function lib_dal_resource_returnResourceAmount($x, $y, $resource)
{
	$sql = '
		SELECT `'.mysql_real_escape_string($resource).'` FROM `dw_res`
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
	';
    return lib_util_mysqlQuery($sql);
}

/**
 * get the upgradelevel of the defined building
 * @author Neithan
 * @param int $kind
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_dal_resource_getUpgrLvl($kind, $x, $y)
{
	$sql = 'SELECT `upgrade_lvl` FROM `dw_buildings`
		WHERE `kind` = "'.mysql_real_escape_string($kind).'"
			AND `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * get the level of the defined research
 * @author Neithan
 * @param int $uid
 * @param int $type
 * @return int
 */
function lib_dal_resource_getResearchLvl($uid, $type)
{
	$sql = 'SELECT `lvl` FROM `dw_research`
		WHERE `uid` = "'.mysql_real_escape_string($uid).'"
			AND `type` = "'.mysql_real_escape_string($type).'"
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * get the level of the defined building
 * @author Neithan
 * @param int $kind
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_dal_resource_getLvl($kind, $x, $y)
{
	$sql = 'SELECT `lvl` FROM `dw_buildings`
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
			AND `kind` = "'.mysql_real_escape_string($kind).'"
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * update the users resources
 * @author Neithan
 * @param array $res array([food], [wood], [rock], [iron], [paper], [koku])
 * @param int $time datetime in seconds
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_dal_resource_updateAll($res, $time, $x, $y)
{
	$sql = '
		UPDATE `dw_res`
		SET `last_time` = "'.mysql_real_escape_string($time).'",
			`food` = "'.mysql_real_escape_string($res['food']).'",
			`wood` = "'.mysql_real_escape_string($res['wood']).'",
			`rock` = "'.mysql_real_escape_string($res['rock']).'",
			`iron` = "'.mysql_real_escape_string($res['iron']).'",
			`paper` = "'.mysql_real_escape_string($res['paper']).'",
			`koku` = "'.mysql_real_escape_string($res['koku']).'"
		WHERE `map_x` = '.mysql_real_escape_string($x).'
			AND `map_y` = '.mysql_real_escape_string($y).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * get the paper production rate
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_dal_resource_getPaperPercent($x, $y)
{
	$sql = '
		SELECT paper_prod FROM dw_res
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * change the paper production rate
 * @author Neithan
 * @param int $percent
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_dal_resource_changePaperPercent($percent, $x, $y)
{
	$sql = '
		UPDATE dw_res SET paper_prod="'.mysql_real_escape_string($percent).'"
		WHERE `map_x` = "'.mysql_real_escape_string($x).'"
			AND `map_y` = "'.mysql_real_escape_string($y).'"
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * returns an array with all resource buildings
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return array
 */
function lib_dal_resource_getResourceBuildings($x, $y)
{
	$sql = '
		SELECT kind, lvl FROM dw_buildings
		WHERE map_x = '.mysql_real_escape_string($x).'
			AND map_y = '.mysql_real_escape_string($y).'
			AND kind BETWEEN 1 AND 6
	';
	return lib_util_mysqlQuery($sql, true);
}
?>
