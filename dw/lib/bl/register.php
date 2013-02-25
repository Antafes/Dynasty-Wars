<?php
namespace bl\register;

/**
 * positioning of the users on the map
 * @author Neithan
 * @param int $uid
 * @param string $city
 * @return int returns 1 if the user is placed on the map, otherwise 0
 */
function placeUserOnMap($uid, $city)
{
	$coords = \dal\register\getFreeCoordinates();
	$lines = count($coords);
	if ($lines > 0) {
		$rand = rand(1, $lines)-1;
		$x = $coords[$rand]['map_x'];
		$y = $coords[$rand]['map_y'];
	}
	$erg2 = \dal\register\updateCoordinates($uid, $city, $x, $y);
	if ($erg2)
		return 1;
	else
		return 0;
}

/**
 * check whether email is valid or not
 * @author Neithan
 * @param string $email
 * @return int returns the length of the string if the regex matches, otherwise 0
 */
function checkMail($email)
{
	if ($email)
		return preg_match('/^[a-z0-9]+[a-z0-9\?\.\+-_]*@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]+$/', $email);
	else
		return 0;
}

/**
 * check for not usable names
 * @author Neithan
 * @param string $nick
 * @param array $notuseable
 * @return int returns 1 if the nickname could not be used, otherwise 0
 */
function checkName($nick, $notuseable)
{
	$nicks = explode(",", $notuseable);
	$lines = count($nicks);

	foreach ($nicks as $part)
		if (stristr($nick, $part))
			return 1;

	return 0;
}

/**
 * register a new user
 * @author Neithan
 * @param string $nick
 * @param string $pws
 * @param string $email
 * @param string $city
 * @return int returns 1 on success, otherwise 0
 */
function createNewUser($nick, $pws, $email, $city)
{
	global $lang;

	$random = '';
	for ($n = 0; $n < 15; $n++)
	{
		$ran = rand(1,2);

		if ($ran == 1)
			$random .= rand(1,9);
		else
			$random .= \bl\general\alpRand();
	}

	$new_uid = \dal\register\insertUser($nick, $pws, $email, $random, $lang['lang']);
	$erg3 = placeUserOnMap($new_uid, $city);

	if ($erg3)
	{
		$coordinates = \dal\user\returnAllCities($new_uid);

		if (count($coordinates) > 0)
		{
			$map_x = $coordinates['map_x'];
			$map_y = $coordinates['map_y'];
		}
	}

	$erg2 = \dal\register\insertResources($new_uid, $map_x, $map_y);
	$erg4 = \dal\register\insertBuildings($new_uid, $map_x, $map_y);
	$erg5 = \dal\register\insertPoints($new_uid);

	if ($new_uid && $erg2 && $erg3 && $erg4 && $erg5)
	{
		$random2 = $new_uid;
		$random2 .= "/";
		$random2 .= $random;
		$header = "From: Dynasty Wars <support@dynasty-wars.de>";
		$activation_link = $GLOBALS['config']['dir_ws_index'].'?chose=activation&amp;id='.$random2;
		$homepage_link = $GLOBALS['config']['dir_ws_index'].'?chose=activation';
		\bl\general\sendMail($email, $lang['subject'], sprintf($lang['message'], $nick, $activation_link, $homepage_link, $random2));
		\bl\log\saveLog (1, $new_uid, 0, "");
		return 1;
	}
	else
		return 0;
}