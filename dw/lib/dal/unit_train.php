<?php
/**
 * get the prices per unit
 * @author Neithan
 * @param int $kind
 * @return array
 */
function lib_dal_unit_train_unitPrices($kind)
{
	$sql = 'SELECT * FROM dw_costs_u WHERE kind = '.$kind;
	return lib_util_mysqlQuery($sql);
}

/**
 * calculate the time needed to produce these units
 * @author Neithan
 * @param int $kind
 * @return int
 */
function lib_dal_unit_train_trainTime($kind)
{
	$sql = 'SELECT btime FROM dw_buildtimes_unit WHERE kind = '.$kind;
	return lib_util_mysqlQuery($sql);
}

/**
 * remove the ressources for the produced units
 * @author Neithan
 * @param array $valuelist array([food], [wood], [rock], [iron], [paper], [koku])
 * @param int $uid
 * @return int
 */
function lib_dal_unit_train_removeRes($valuelist, $uid)
{
	$sql = '
		UPDATE dw_res
		SET food = food-'.$valuelist['food'].',
			wood = wood-'.$valuelist['wood'].',
			rock = rock-'.$valuelist['rock'].',
			iron = iron-'.$valuelist['iron'].',
			paper = paper-'.$valuelist['paper'].',
			koku = koku-'.$valuelist['koku'].'
		WHERE uid = '.$uid.'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * insert the currently producing units
 * @author Neithan
 * @param int $kind
 * @param int $uid
 * @param int $count
 * @param DWDateTime $endTime
 * @param string $city
 * @return int
 */
function lib_dal_unit_train_startTrain($kind, $uid, $count, DWDateTime $endTime, $city)
{
	$sql = '
		INSERT INTO dw_build_unit (
			kind,
			uid,
			count,
			starttime,
			endtime,
			city
		) VALUES (
			'.mysql_real_escape_string($kind).',
			'.mysql_real_escape_string($uid).',
			'.mysql_real_escape_string($count).',
			NOW(),
			"'.mysql_real_escape_string($endTime->format()).'",
			"'.mysql_real_escape_string($city).'"
		)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * check for training
 * @author Neithan
 * @param int $uid
 * @param string $city
 * @return array
 */
function lib_dal_unit_train_checkTraining($uid, $city)
{
	$sql = '
		SELECT
			tid,
			count,
			endtime,
			city,
			kind
		FROM dw_build_unit
		WHERE uid = '.mysql_real_escape_string($uid).'
			AND city = "'.mysql_real_escape_string($city).'"
	';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * remove the complete units from the build list
 * @author Neithan
 * @param int $tid
 */
function lib_dal_unit_train_removeComplete($tid)
{
	$sql = 'DELETE FROM dw_build_unit WHERE tid='.$tid;
	lib_util_mysqlQuery($sql);
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
function lib_dal_unit_train_checkPos($uid, $map_x, $map_y, $kind)
{
	$sql = '
		SELECT unid FROM dw_units
		WHERE uid = '.$uid.'
			AND pos_x = '.$map_x.'
			AND pos_y = '.$map_y.'
			AND kind = '.$kind.'
			AND NOT tid
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * add the units to the existing unit
 * @author Neithan
 * @param int $count
 * @param int $unid
 * @return int
 */
function lib_dal_unit_train_addUnit($count, $unid)
{
	$sql = 'UPDATE dw_units SET count = count + '.$count.' WHERE unid = '.$unid;
	return lib_util_mysqlQuery($sql);;
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
function lib_dal_unit_train_newUnit($uid, $kind, $count, $map_x, $map_y)
{
	$sql = '
		INSERT INTO dw_units (
			uid,
			kind,
			count,
			pos_x,
			pos_y
		) VALUES (
			'.mysql_real_escape_string($uid).',
			'.$kind.',
			'.$count.',
			'.$map_x.',
			'.$map_y.'
		)
	';
	return lib_util_mysqlQuery($sql);
}

function lib_dal_unit_deleteUnit($unid)
{
	$sql = '
		DELETE FROM dw_units
		WHERE unid = '.mysql_real_escape_string($unid).'
	';
	lib_util_mysqlQuery($sql);
}