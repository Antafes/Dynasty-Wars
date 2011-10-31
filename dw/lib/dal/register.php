<?php
/**
 * get all coordinates where the terrain is not water or walkable and where no uid is set
 * @author Neithan
 * @return array
 */
function lib_dal_register_getFreeCoords()
{
	$sql = '
		SELECT `map_x`, `map_y`
		FROM `dw_map`
		WHERE (
				`terrain` != 1
				AND `terrain` != 5
			)
			AND NOT `uid`
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * update the coordinates
 * @author Neithan
 * @param int $uid
 * @param string $city
 * @param int $x
 * @param int $y
 * @return int
 */
function lib_dal_register_updateCoords($uid, $city, $x, $y)
{
	$sql = '
		UPDATE `dw_map`
		SET `uid` = "'.mysql_real_escape_string($uid).'",
			`city` = "'.mysql_real_escape_string($city).'",
			`maincity` = 1
		WHERE `map_x` = '.$x.'
			AND `map_y` = '.$y.'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * insert a new user into the database
 * @author Neithan
 * @param string $nick
 * @param string $pws
 * @param string $email
 * @param string $random
 * @param string $language
 * @return int
 */
function lib_dal_register_insertUser($nick, $pws, $email, $random, $language)
{
	$sql = '
		INSERT INTO dw_user (
			nick,
			password,
			email,
			regdate,
			status,
			language
		) VALUES (
			"'.mysql_real_escape_string($nick).'",
			"'.mysql_real_escape_string($pws).'",
			"'.mysql_real_escape_string($email).'",
			'.time().',
			"'.mysql_real_escape_string($random).'",
			"'.mysql_real_escape_string($language).'"
		)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * insert the resources of the new inserted user
 * @author Neithan
 * @param int $uid
 * @return int
 */
function lib_dal_register_insertRes($uid, $x, $y)
{
	$sql = 'INSERT INTO dw_res (uid, last_time, map_x, map_y) VALUES ('.$uid.', '.time().', '.$x.', '.$y.')';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * insert the needed buildings into the database
 * @author Neithan
 * @param int $uid
 * @param int $map_x
 * @param int $map_y
 * @return int
 */
function lib_dal_register_insertBuildings($uid, $map_x, $map_y)
{
	$sql = '
		INSERT INTO dw_buildings (
			uid,
			map_x,
			map_y,
			kind,
			lvl,
			upgrade_lvl,
			position
		) VALUES ('.$uid.', '.$map_x.', '.$map_y.', 19, 0, 0, 1),
			('.$uid.', '.$map_x.', '.$map_y.', 1, 0, 0, 2),
			('.$uid.', '.$map_x.', '.$map_y.', 2, 0, 0, 3),
			('.$uid.', '.$map_x.', '.$map_y.', 3, 0, 0, 4),
			('.$uid.', '.$map_x.', '.$map_y.', 4, 0, 0, 5),
			('.$uid.', '.$map_x.', '.$map_y.', 5, 0, 0, 6),
			('.$uid.', '.$map_x.', '.$map_y.', 6, 0, 0, 7),
			('.$uid.', '.$map_x.', '.$map_y.', 22, 0, 0, 0)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * insert the user into the points table
 * @author Neithan
 * @param int $uid
 * @return int
 */
function lib_dal_register_insertPoints($uid)
{
	$sql = 'INSERT INTO dw_points (uid) VALUES ('.$uid.')';
	return lib_util_mysqlQuery($sql);
}
?>