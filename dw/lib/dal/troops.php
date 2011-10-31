<?php
/**
 * get the fields around $x and $y
 * @author Neithan
 * @param int $lx
 * @param int $hx
 * @param int $ly
 * @param int $hy
 * @param int $x
 * @param int $y
 * @return array
 */
function lib_dal_troops_surrounding($lx, $hx, $ly, $hy, $x, $y)
{
	$sql = '
		SELECT map_x, map_y, terrain FROM dw_map
		WHERE (
				(
					(map_x BETWEEN '.$lx.' AND '.$hx.')
					AND map_y = '.$ly.'
				) OR (
					(map_x BETWEEN '.$lx.' AND '.$hx.')
					AND map_y = '.$hy.'
				) OR (
					(map_x = '.$lx.' OR map_x = '.$hx.')
					AND map_y = '.$y.'
				)
			)
			AND NOT (terrain = 1 OR terrain = 5)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * get the troop of the defined user
 * @author Neithan
 * @param int $tid
 * @return array
 */
function lib_dal_troops_getTroop($tid)
{
	$sql = 'SELECT * FROM dw_troops WHERE tid = '.$tid;
	$GLOBALS['firePHP']->log($sql, 'getTroop->query');
	return lib_util_mysqlQuery($sql);
}

/**
 * get the positions where troops/units are
 * @author Neithan
 * @param int $uid
 * @param int $kind
 * @return array
 */
function lib_dal_troops_getPos($uid, $kind)
{
	$sql = 'SELECT DISTINCT pos_x, pos_y FROM dw_'.$kind.' WHERE uid = '.$uid;
	if ($kind == 'units')
		$sql .= ' AND NOT tid';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * get all troops/units from the definied user at a defined position
 * @author Neithan
 * @param int $uid
 * @param int $posx
 * @param int $posy
 * @param int $kind (troops, units)
 * @return array
 */
function lib_dal_troops_getAtPos($uid, $posx, $posy, $kind, $get_all, $order_by)
{
	if ($kind == 'troops')
	{
		$sql = '
			SELECT
				tid,
				name,
				res,
				amount
			FROM dw_troops
			WHERE pos_x = '.$posx.'
				AND pos_y = '.$posy.'
				AND uid = '.mysql_real_escape_string($uid).'
			ORDER BY tid
		';
	}
	elseif ($kind == 'units')
	{
		$sql = '
			SELECT
				unid,
				kind,
				count
			FROM dw_units
			WHERE pos_x = '.$posx.'
				AND pos_y = '.$posy.'
				AND uid = '.mysql_real_escape_string($uid).'
				'.(!$get_all ? 'AND NOT tid' : '').'
			ORDER BY '.$order_by.'
		';
	}
	return lib_util_mysqlQuery($sql, true);
}

/**
 * get the units that are in this troop
 * @author Neithan
 * @param int $tid
 * @return array
 */
function lib_dal_troops_getTroopUnits($tid, $order_by)
{
	$sql = '
		SELECT * FROM dw_units
		WHERE tid = '.mysql_real_escape_string($tid).'
	';
	if($order_by)
		$sql .= 'ORDER BY '.$order_by;
	$GLOBALS['firePHP']->log($sql, 'getTroopUnits->query');
	return lib_util_mysqlQuery($sql, true);
}

/**
 * create new troop
 * @author Neithan
 * @param int $uid
 * @param int $posx
 * @param int $posy
 * @param string $name
 * @return int
 */
function lib_dal_troops_createTroop($uid, $posx, $posy, $name)
{
	$sql = '
		INSERT INTO dw_troops (
			uid,
			pos_x,
			pos_y,
			name
		) VALUES (
			'.$uid.',
			'.$posx.',
			'.$posy.',
			"'.mysql_real_escape_string($name).'"
		)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * add the units to the troop
 * @author Neithan
 * @param int $unid
 * @param int $tid
 * @return int
 */
function lib_dal_troops_addUnits($unid, $tid)
{
	$sql = '
		UPDATE dw_units
		SET tid = '.mysql_real_escape_string($tid).'
		WHERE unid = '.mysql_real_escape_string($unid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * get the unit with the defined unid
 * @author Neithan
 * @param int $unid
 * @return array
 */
function lib_dal_troops_getUnitCount($unid)
{
	$sql = 'SELECT count, kind FROM dw_units WHERE unid = '.mysql_real_escape_string($unid);
	return lib_util_mysqlQuery($sql);
}

/**
 * remove a part of the units from one unid
 * @author Neithan
 * @param int $unid
 * @param int $count
 */
function lib_dal_troops_removeFromUNID($unid, $count)
{
	$sql = 'UPDATE dw_units SET count = '.$count.' WHERE unid = '.mysql_real_escape_string($unid);
	lib_util_mysqlQuery($sql);
}

/**
 * check if attacks are possible
 * @author Neithan
 * @return int
 */
function lib_dal_troops_checkCanAttack()
{
	$sql = 'SELECT canattack FROM dw_game';
	return lib_util_mysqlQuery($sql);
}

/**
 * check if there is a user on thie position
 * @author Neithan
 * @param int $tx
 * @param int $ty
 * @return int
 */
function lib_dal_troops_checkTarget($tx, $ty)
{
	$sql = 'SELECT uid FROM dw_map WHERE map_x = '.$tx.' AND map_y = '.$ty;
	return lib_util_mysqlQuery($sql);
}

/**
 * is the target in my clan?
 * @author Neithan
 * @param int $tuid
 * @return int
 */
function lib_dal_troops_checkTargetClan($tuid)
{
	$sql = 'SELECT cid FROM dw_user WHERE uid = '.mysql_real_escape_string($tuid);
	return lib_util_mysqlQuery($sql);
}

/**
 * insert the movement of the specified troop
 * @author Neithan
 * @param int $tid
 * @param int $tx
 * @param int $ty
 * @param int $type
 * @param int $endtime
 * @return int
 */
function lib_dal_troops_sendTroop($tid, $tx, $ty, $type, $endtime)
{
	$sql = '
		INSERT INTO dw_troops_move (
			tid,
			tx,
			ty,
			type,
			endtime
		) VALUES (
			'.mysql_real_escape_string($tid).',
			'.mysql_real_escape_string($tx).',
			'.mysql_real_escape_string($ty).',
			'.mysql_real_escape_string($type).',
			'.$endtime.'
		)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * add resource to the troop
 * @author Neithan
 * @param int $tid
 * @param string $res
 * @param int $amount
 * @return int
 */
function lib_dal_troops_addResToTroop($tid, $res, $amount)
{
	$sql = '
		UPDATE dw_troops
		SET res = "'.mysql_real_escape_string($res).'",
			amount = '.mysql_real_escape_string($amount).'
		WHERE tid = '.mysql_real_escape_string($tid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * check for moving troops
 * @author Neithan
 * @param int $tuid
 * @return array
 */
function lib_dal_troops_checkTroops($tuid)
{
	$sql = '
		SELECT dw_troops_move.tid
		FROM dw_troops_move
		LEFT OUTER JOIN dw_troops ON dw_troops_move.tid = dw_troops.tid
		WHERE uid = '.mysql_real_escape_string($tuid).'
	';
	$GLOBALS['firePHP']->log($sql, 'checkTroops-SQL');
	return lib_util_mysqlQuery($sql, true);
}

/**
 * check the troop on movement
 * @author Neithan
 * @param int $tid
 * @return array
 */
function lib_dal_troops_checkTroop($tid)
{
	$sql = 'SELECT endtime, tx, ty, type FROM dw_troops_move WHERE tid = '.mysql_real_escape_string($tid);
	return lib_util_mysqlQuery($sql);
}

/**
 * delete the troop from dw_troops_move
 * @author Neithan
 * @param int $tid
 */
function lib_dal_troops_endMoving($tid)
{
	$sql = 'DELETE FROM dw_troops_move WHERE tid = '.mysql_real_escape_string($tid);
	lib_util_mysqlQuery($sql);
}

/**
 * get the highest troop id
 * @author Neithan
 * @param int $tuid
 * @return int
 */
function lib_dal_troops_getMaxTID($tuid)
{
	$sql = 'SELECT max(tid) FROM `dw_troops` WHERE uid = '.mysql_real_escape_string($tuid);
	return lib_util_mysqlQuery($sql);
}

/**
 * change the position of the troop
 * @author Neithan
 * @param int $tid
 * @param int $x
 * @param int $y
 */
function lib_dal_troops_changeTroopPosition($tid, $x, $y)
{
	$sql = '
		UPDATE dw_troops
		SET pos_x = '.$x.',
			pos_y = '.$y.'
		WHERE tid = '.mysql_real_escape_string($tid).'
	';
	lib_util_mysqlQuery($sql);
}

/**
 * change the position of the troops units
 * @author Neithan
 * @param unknown_type $tid
 * @param unknown_type $x
 * @param unknown_type $y
 */
function lib_dal_troops_changeUnitsPosition($tid, $x, $y)
{
	$sql = '
		UPDATE dw_units
		SET pos_x = '.$x.',
			pos_y = '.$y.'
		WHERE tid = '.mysql_real_escape_string($tid).'
	';
	lib_util_mysqlQuery($sql);
}

/**
 * check for units of this kind, that are allready in the troop
 * @author Neithan
 * @param int $kind
 * @param int $tid
 * @return int
 */
function lib_dal_troops_checkTroopUnits($kind, $tid)
{
	$sql = '
		SELECT unid FROM dw_units
		WHERE tid = '.mysql_real_escape_string($tid).'
			AND kind = '.mysql_real_escape_string($kind).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * delete the defined unit from dw_units
 * @author Neithan
 * @param int $unid
 */
function lib_dal_troops_deleteUnit($unid)
{
	$sql = 'DELETE FROM dw_units WHERE unid = '.mysql_real_escape_string($unid);
	lib_util_mysqlQuery($sql);
}

/**
 * change the name of this troop
 * @author Neithan
 * @param int $tid
 * @param string $name
 */
function lib_dal_troops_rename($tid, $name)
{
	$sql = '
		UPDATE dw_troops
		SET name = "'.mysql_real_escape_string($name).'"
		WHERE tid = '.mysql_real_escape_string($tid).'
	';
	lib_util_mysqlQuery($sql);
}

/**
 * delete the troop
 * @author Neithan
 * @param int $tid
 */
function lib_dal_troops_deleteTroop($tid)
{
	$sql = 'DELETE FROM dw_troops WHERE tid = '.mysql_real_escape_string($tid);
	lib_util_mysqlQuery($sql);
}

/**
 * reset the tid
 * @author Neithan
 * @param int $tid
 */
function lib_dal_troops_resetTID($tid)
{
	$sql = '
		UPDATE dw_units
		SET tid = 0
		WHERE tid = '.mysql_real_escape_string($tid).'
	';
	lib_util_mysqlQuery($sql);
}

/**
 * get the isle at this coordinates
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_dal_troops_getIsle($x, $y)
{
	$sql = '
		SELECT isle FROM dw_map
		WHERE map_x = '.mysql_real_escape_string($x).'
			AND map_y = '.mysql_real_escape_string($y).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * get the loaded resource
 * @author Neithan
 * @param int $tid
 * @return array
 */
function lib_dal_troops_loaded($tid)
{
	$sql = 'SELECT res, amount FROM dw_troops WHERE tid = '.mysql_real_escape_string($tid);
	return lib_util_mysqlQuery($sql);
}

/**
 * get the unit stats
 * @author Neithan
 * @param <type> $kind
 * @return <type>
 */
function lib_dal_troops_getUnitStats($kind)
{
	$sql = '
		SELECT * FROM dw_unit_stats
		WHERE kind = '.mysql_real_escape_string($kind).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * update the units count
 * @param int $unid
 * @param int $count
 * @return int
 */
function lib_dal_troops_updateUnitCount($unid, $count)
{
	$sql = '
		UPDATE dw_units
		SET count = '.mysql_real_escape_string($count).'
		WHERE unid = '.mysql_real_escape_string($unid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * get all moving troops
 * @author Neithan
 * @return array
 */
function lib_dal_troops_getAllMovingTroops()
{
	$sql = 'SELECT * FROM dw_troops_move';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * remove a unit from the troop
 * @author Neithan
 * @param int $unid
 * @return int
 */
function lib_dal_troops_removeFromTroop($unid)
{
	$sql = '
		UPDATE dw_units
		SET tid = 0
		WHERE unid = '.mysql_real_escape_string($unid).'
	';
	return lib_util_mysqlQuery($sql);
}
?>