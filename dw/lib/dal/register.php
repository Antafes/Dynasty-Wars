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
	return \util\mysql\query($sql);
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
		SET `uid` = '.\util\mysql\sqlval($uid).',
			`city` = '.\util\mysql\sqlval($city).',
			`maincity` = 1
		WHERE `map_x` = '.\util\mysql\sqlval($x).'
			AND `map_y` = '.\util\mysql\sqlval($y).'
	';
	return \util\mysql\query($sql);
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
	$now = new \DWDateTime();
	$sql = '
		INSERT INTO dw_user (
		SET nick = '.\util\mysql\sqlval($nick).',
			password = '.\util\mysql\sqlval($pws).',
			email = '.\util\mysql\sqlval($email).',
			registration_datetime = '.\util\mysql\sqlval($now->format()).',
			status = '.\util\mysql\sqlval($random).',
			language = '.\util\mysql\sqlval($language).'
	';
	return \util\mysql\query($sql);
}

/**
 * insert the resources of the new inserted user
 * @author Neithan
 * @param int $uid
 * @return int
 */
function insertResources($uid, $x, $y)
{
	$now = new \DWDateTime();
	$sql = '
		INSERT INTO dw_res
		SET uid = '.\util\mysql\sqlval($uid).',
			last_datetime = '.\util\mysql\sqlval($now->format()).',
			map_x = '.\util\mysql\sqlval($x).',
			map_y = '.\util\mysql\sqlval($y).'
	';
	return \util\mysql\query($sql, true); //with the second parameter an insert returns the number of affected rows
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
		) VALUES ('.\util\mysql\sqlval($uid).', '.\util\mysql\sqlval($map_x).', '.\util\mysql\sqlval($map_y).', 19, 0, 0, 1),
			('.\util\mysql\sqlval($uid).', '.\util\mysql\sqlval($map_x).', '.\util\mysql\sqlval($map_y).', 1, 0, 0, 2),
			('.\util\mysql\sqlval($uid).', '.\util\mysql\sqlval($map_x).', '.\util\mysql\sqlval($map_y).', 2, 0, 0, 3),
			('.\util\mysql\sqlval($uid).', '.\util\mysql\sqlval($map_x).', '.\util\mysql\sqlval($map_y).', 3, 0, 0, 4),
			('.\util\mysql\sqlval($uid).', '.\util\mysql\sqlval($map_x).', '.\util\mysql\sqlval($map_y).', 4, 0, 0, 5),
			('.\util\mysql\sqlval($uid).', '.\util\mysql\sqlval($map_x).', '.\util\mysql\sqlval($map_y).', 5, 0, 0, 6),
			('.\util\mysql\sqlval($uid).', '.\util\mysql\sqlval($map_x).', '.\util\mysql\sqlval($map_y).', 6, 0, 0, 7),
			('.\util\mysql\sqlval($uid).', '.\util\mysql\sqlval($map_x).', '.\util\mysql\sqlval($map_y).', 22, 0, 0, 0)
	';
	return \util\mysql\query($sql);
}

/**
 * insert the user into the points table
 * @author Neithan
 * @param int $uid
 * @return int
 */
function insertPoints($uid)
{
	$sql = 'INSERT INTO dw_points (uid) VALUES ('.\util\mysql\sqlval($uid).')';
	return \util\mysql\query($sql);
}