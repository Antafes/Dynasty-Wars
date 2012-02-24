<?php
/**
 * get all data of this user
 * @author Neithan
 * @param int $uid
 * @return array
 */
function lib_dal_login_getAllData($uid)
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
		WHERE uid = '.$uid.'
	';
	$r = util\mysql\query($sql);
	return $r;
}
/**
 * check for closed login
 * @author Neithan
 * @return int
 */
function lib_dal_login_checkLogin()
{
	$sql = 'SELECT login_closed FROM dw_game';
	return util\mysql\query($sql);
}
/**
 * set last login time
 * @author Neithan
 * @param int $uid
 * @param int $lastlogin datetime in seconds
 * @return int
 */
function lib_dal_login_setLastLogin($uid)
{
	$sql = '
		UPDATE dw_user
		SET last_login_datetime = NOW()
		WHERE uid='.mysql_real_escape_string($uid).'
	';
	return util\mysql\query($sql);
}
/**
 * get the maincity of this user
 * @author Neithan
 * @param string default
 * @param int $uid
 * @return array
 */
function lib_dal_login_getMainCity($uid, $add_where = ' AND maincity = 1')
{
	$sql = 'SELECT map_x, map_y FROM dw_map WHERE uid = '.$uid.$add_where.'';
	return util\mysql\query($sql);
}
?>