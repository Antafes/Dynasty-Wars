<?php
namespace dal\unit\train;

/**
 * get the prices per unit
 * @author Neithan
 * @param int $kind
 * @return array
 */
function unitPrices($kind)
{
	$sql = '
		SELECT * FROM dw_costs_u
		WHERE kind = '.\util\mysql\sqlval($kind).'
	';
	return \util\mysql\query($sql);
}

/**
 * calculate the time needed to produce these units
 * @author Neithan
 * @param int $kind
 * @return int
 */
function trainTime($kind)
{
	$sql = '
		SELECT btime FROM dw_buildtimes_unit
		WHERE kind = '.\util\mysql\sqlval($kind).'
	';
	return \util\mysql\query($sql);
}

/**
 * remove the ressources for the produced units
 * @author Neithan
 * @param array $valuelist array([food], [wood], [rock], [iron], [paper], [koku])
 * @param int $uid
 * @return int
 */
function removeResources($valuelist, $uid)
{
	$sql = '
		UPDATE dw_res
		SET food = food - '.\util\mysql\sqlval($valuelist['food']).',
			wood = wood - '.\util\mysql\sqlval($valuelist['wood']).',
			rock = rock - '.\util\mysql\sqlval($valuelist['rock']).',
			iron = iron - '.\util\mysql\sqlval($valuelist['iron']).',
			paper = paper - '.\util\mysql\sqlval($valuelist['paper']).',
			koku = koku - '.\util\mysql\sqlval($valuelist['koku']).'
		WHERE uid = '.\util\mysql\sqlval($uid).'
	';
	return \util\mysql\query($sql);
}

/**
 * insert the currently producing units
 * @author Neithan
 * @param int $kind
 * @param int $uid
 * @param int $count
 * @param \DWDateTime $endTime
 * @param string $city
 * @return int
 */
function startTrain($kind, $uid, $count, \DWDateTime $endTime, $city)
{
	$now = new \DWDateTime();
	$sql = '
		INSERT INTO dw_build_unit (
		SET kind = '.\util\mysql\sqlval($kind).',
			uid = '.\util\mysql\sqlval($uid).',
			count = '.\util\mysql\sqlval($count).',
			start_datetime = '.\util\mysql\sqlval($now->format()).',
			end_datetime = '.\util\mysql\sqlval($endTime->format()).',
			city = '.\util\mysql\sqlval($city).'
	';
	return \util\mysql\query($sql);
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
	$sql = '
		SELECT
			tid,
			count,
			end_datetime,
			city,
			kind
		FROM dw_build_unit
		WHERE uid = '.\util\mysql\sqlval($uid).'
			AND city = '.\util\mysql\sqlval($city).'
	';
	return \util\mysql\query($sql, true);
}

/**
 * remove the complete units from the build list
 * @author Neithan
 * @param int $tid
 */
function removeFromTrainList($tid)
{
	$sql = 'DELETE FROM dw_build_unit WHERE tid = '.\util\mysql\sqlval($tid).'';
	\util\mysql\query($sql);
}

/**
 * check if the kind of unit is currently on this position
 * @author Neithan
 * @param int $uid
 * @param int $map_x
 * @param int $map_y
 * @param int $kind
 * @return int
 */
function checkPosition($uid, $map_x, $map_y, $kind)
{
	$sql = '
		SELECT unid FROM dw_units
		WHERE uid = '.\util\mysql\sqlval($uid).'
			AND pos_x = '.\util\mysql\sqlval($map_x).'
			AND pos_y = '.\util\mysql\sqlval($map_y).'
			AND kind = '.\util\mysql\sqlval($kind).'
			AND NOT tid
	';
	return \util\mysql\query($sql);
}

/**
 * add the units to the existing unit
 * @author Neithan
 * @param int $count
 * @param int $unid
 * @return int
 */
function addUnit($count, $unid)
{
	$sql = '
		UPDATE dw_units
		SET count = count + '.\util\mysql\sqlval($count).'
		WHERE unid = '.\util\mysql\sqlval($unid).'
	';
	return \util\mysql\query($sql);;
}

/**
 * create a new unit
 * @author Neithan
 * @param int $uid
 * @param int $kind
 * @param int $count
 * @param int $map_x
 * @param int $map_y
 * @return int
 */
function newUnit($uid, $kind, $count, $map_x, $map_y)
{
	$sql = '
		INSERT INTO dw_units (
			uid,
			kind,
			count,
			pos_x,
			pos_y
		) VALUES (
			'.\util\mysql\sqlval($uid).',
			'.\util\mysql\sqlval($kind).',
			'.\util\mysql\sqlval($count).',
			'.\util\mysql\sqlval($map_x).',
			'.\util\mysql\sqlval($map_y).'
		)
	';
	return \util\mysql\query($sql);
}

/**
 * get the amount of currently trained units
 * @param int $uid
 * @param String $city
 * @param int $kind
 * @return int
 */
function getTrainingUnits($uid, $city, $kind)
{
	$sql = '
		SELECT count FROM dw_build_unit
		WHERE uid = '.\util\mysql\sqlval($uid).'
			AND city = '.\util\mysql\sqlval($city).'
			AND kind = '.\util\mysql\sqlval($kind).'
	';
	return \util\mysql\query($sql);
}