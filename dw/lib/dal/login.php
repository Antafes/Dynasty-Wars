<?php
namespace dal\login;

/**
 * get all data of this user
 * @author Neithan
 * @param int $uid
 * @return array
 */
function getAllData($uid)
{
	$sql = '
		SELECT
			uid,
			nick,
			password,
			blocked,
			status,
			game_rank,
			language,
			deactivated
		FROM dw_user
		WHERE uid = '.\util\mysql\sqlval($uid).'
	';
	$r = \util\mysql\query($sql);
	return $r;
}

/**
 * check for closed login
 * @author Neithan
 * @return int
 */
function checkLogin()
{
	$sql = 'SELECT login_closed FROM dw_game';
	return \util\mysql\query($sql);
}

/**
 * set last login time
 * @author Neithan
 * @param int $uid
 * @param int $lastlogin datetime in seconds
 * @return int
 */
function setLastLogin($uid)
{
	$now = new \DWDateTime();
	$sql = '
		UPDATE dw_user
		SET last_login_datetime = '.\util\mysql\sqlval($now->format()).'
		WHERE uid='.\util\mysql\sqlval($uid).'
	';
	return \util\mysql\query($sql);
}

/**
 * get the maincity of this user
 * @author Neithan
 * @param string default
 * @param int $uid
 * @return array
 */
function getMainCity($uid, $add_where = ' AND maincity = 1')
{
	$sql = 'SELECT map_x, map_y FROM dw_map WHERE uid = '.\util\mysql\sqlval($uid).\util\mysql\sqlval($add_where, false).'';
	return \util\mysql\query($sql);
}