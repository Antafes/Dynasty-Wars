<?php
namespace bl\login;

/**
 * get all data of this user
 * @author Neithan
 * @param string $nick
 * @return array
 */
function getAllData($nick)
{
	$uid = dal\user\nick2uid($nick);

	if (isset($uid) && $uid > 0)
		return dal\login\getAllData($uid);
	else
		return false;
}

/**
 * check for closed login
 * @author Neithan
 * @return int
 */
function checkLogin()
{
	return dal\login\checkLogin();
}

/**
 * set last login time
 * @author Neithan
 * @param int $uid
 * @return int
 */
function setLastLogin($uid)
{
	return dal\login\setLastLogin($uid);
}

/**
 * get the maincity of this user
 * @author Neithan
 * @param int $uid
 * @return string
 */
function getMainCity($uid)
{
	$city = dal\login\getMainCity($uid);

	if (count($city) > 0)
		return $city['map_x'].':'.$city['map_y'];
}

/**
 * create an unique id for this login
 * @author Neithan
 * @param int $uid
 * @return string
 */
function createID($uid)
{
	$user = dal\login\getAllData($uid);
	$uidpos = rand(1, 9);
	$uidlen = strlen($uid);
	$id = substr($user['password'], 0, $uidpos);
	$id .= $uid;
	$id .= substr($user['password'], $uidpos, -3);
	$id .= $uidpos;

	if ($uidlen < 10)
		$id .= 0;

	$id .= $uidlen;
	$id .= substr($user['password'], -3);
	return $id;
}

/**
 * check id
 * @author Neithan
 * @param string $id
 * @return boolean returns true if the id is correct for this user, otherwise false
 */
function checkID($id)
{
	$uidpos = substr($id, -6, 1);
	$uidlen = substr($id, -5, 2);
	$pw = substr($id, 0, $uidpos);
	$pw .= substr($id, $uidpos+$uidlen, -6);
	$pw .= substr($id, -3);
	$uid = substr($id, $uidpos, $uidlen);
	$user = dal\login\getAllData($uid);

	return ($pw === $user['password']);
}

/**
 * get uid from id
 * @author Neithan
 * @param string $id
 * @return int
 */
function getUIDFromID($id)
{
	$uidpos = substr($id, -6, 1);
	$uidlen = substr($id, -5, 2);
	return (int)substr($id, $uidpos, $uidlen);
}