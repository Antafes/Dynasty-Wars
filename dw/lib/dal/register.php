<?php
namespace dal\register;

/**
 * get all coordinates where the terrain is not water or walkable and where no uid is set
 * @author Neithan
 * @return array
 */
function getFreeCoordinates()
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
	return util\mysql\query($sql);
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
function updateCoordinates($uid, $city, $x, $y)
{
	$sql = '
		UPDATE `dw_map`
		SET `uid` = "'.mysql_real_escape_string($uid).'",
			`city` = "'.mysql_real_escape_string($city).'",
			`maincity` = 1
		WHERE `map_x` = '.$x.'
			AND `map_y` = '.$y.'
	';
	return util\mysql\query($sql);
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
function insertUser($nick, $pws, $email, $random, $language)
{
	$sql = '
		INSERT INTO dw_user (
			nick,
			password,
			email,
			registration_datetime,
			status,
			language
		) VALUES (
			"'.mysql_real_escape_string($nick).'",
			"'.mysql_real_escape_string($pws).'",
			"'.mysql_real_escape_string($email).'",
			NOW(),
			"'.mysql_real_escape_string($random).'",
			"'.mysql_real_escape_string($language).'"
		)
	';
	return util\mysql\query($sql);
}

/**
 * insert the resources of the new inserted user
 * @author Neithan
 * @param int $uid
 * @return int
 */
function insertResources($uid, $x, $y)
{
	$sql = '
		INSERT INTO dw_res
		SET uid = '.mysql_real_escape_string($uid).',
			last_datetime = NOW(),
			map_x = '.mysql_real_escape_string($x).',
			map_y = '.mysql_real_escape_string($y).'
	';
	return util\mysql\query($sql, true); //with the second parameter an insert returns the number of affected rows
}

/**
 * insert the needed buildings into the database
 * @author Neithan
 * @param int $uid
 * @param int $map_x
 * @param int $map_y
 * @return int
 */
function insertBuildings($uid, $map_x, $map_y)
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
	return util\mysql\query($sql);
}

/**
 * insert the user into the points table
 * @author Neithan
 * @param int $uid
 * @return int
 */
function insertPoints($uid)
{
	$sql = 'INSERT INTO dw_points (uid) VALUES ('.$uid.')';
	return util\mysql\query($sql);
}