<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */

/**
 * Returns a resultset containing all nicks and corresponding points
 * @author siyb
 * @return <array> containing uid and points already calculated
 */
function lib_dal_unit_calcUnitPoints() {
    return
    util\mysql\query(
        "
        SELECT dw_user.uid, sum((food + wood + rock + iron + paper + koku) * count / 1000.0) AS points
        FROM dw_units
        JOIN dw_costs_u ON dw_units.kind = dw_costs_u.kind
        JOIN dw_user ON dw_user.uid = dw_units.uid
		WHERE NOT deactivated
        GROUP BY uid
        "
    , true);
}

/**
 * Returns a list of the kind and the amount of all units of player with uid.
 * @author siyb
 * @param <type> $uid the uid of the user
 * @return <array> containing uid and points already calculated
 */
function lib_dal_getUnitCount($uid) {
    return
    util\mysql\query(
        sprintf(
            "
            SELECT kind, sum(count) as count from dw_units
            WHERE uid='%d'
            GROUP BY kind
            ",
            mysql_real_escape_string($uid)
        )
    );
}

/**
 * Tells if unitcosts should be calculated
 * @author siyb
 * @return <int> 1 if yes 0 is not
 */
function lib_dal_calculateUnitCosts() {
    $result = util\mysql\query("SELECT unitcosts FROM dw_game");
    if ($result)
    	return $result;
}

/**
 * select the units from the current user
 * @author Neithan
 * @param int $kind
 * @param int $uid
 * @return array
 */
function lib_dal_unit_getUnits($kind, $uid)
{
	$sql = '
		SELECT
			unid,
			count,
			pos_x,
			pos_y
		FROM dw_units
		WHERE kind = '.$kind.'
			AND uid = '.$uid.'
	';
	return util\mysql\query($sql, true);
}

/**
 * Returns the number of troops of a player that are located at x,y
 * @author siyb
 * @param int $kind the kind of troops to be counted
 * @param int $uid the uid of the player
 * @param int $x the x coordinate
 * @param int $y the y coordinate
 * @return array
 */
function lib_dal_unit_getUnitCountByCoordinates($kind, $uid, $x, $y) {
	return
	util\mysql\query(
		sprintf("
			SELECT
				count
			FROM dw_units
			WHERE kind='%d'
			AND uid ='%d'
			AND pos_x='%d'
			AND pos_y='%d'",
			mysql_real_escape_string($kind),
			mysql_real_escape_string($uid),
			mysql_real_escape_string($x),
			mysql_real_escape_string($y)
		)
	);
}

/**
 * check for an existing daimyo-unit for the user
 * @author Neithan
 * @param int $uid
 * @return int
 */
function lib_dal_unit_checkDaimyo($uid)
{
	$sql = 'SELECT unid FROM dw_units WHERE uid = '.$uid.' AND kind = 19';
	return util\mysql\query($sql);
}

/**
 * create a new daimyo-unit
 * @author Neithan
 * @param int $uid
 * @param int $pos_x
 * @param int $pos_y
 * @return int
 */
function lib_dal_unit_createDaimyo($uid, $pos_x, $pos_y)
{
	$sql = '
		INSERT INTO dw_units (
			uid,
			kind,
			count,
			pos_x,
			pos_y
		) VALUES (
			'.$uid.',
			19,
			1,
			'.$pos_x.',
			'.$pos_y.'
		)
	';
	return util\mysql\query($sql);
}

function lib_dal_unit_getUnit($unid)
{
	$sql = '
		SELECT * FROM dw_units
		WHERE unid = '.mysql_real_escape_string($unid).'
	';
	return util\mysql\query($sql);
}
?>