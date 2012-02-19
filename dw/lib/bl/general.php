<?php
/**
 * get the ressource amounts of the defined user
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return array
 */
function lib_bl_general_getRes($x, $y)
{
	$resources = lib_dal_general_getRes($x, $y);

	$resource = array();
	if ($resources)
	{
		foreach ($resources as $key => $res)
		{
			if (!is_numeric($key))
				$resource[$key] = $res;
		}
	}

	return $resource;
}

/**
 * chosing language
 * @author Neithan
 * @param int $uid default = ''
 * @return string
 */
function lib_bl_general_getLanguage($uid = '')
{
	if ($uid)
		$language = lib_dal_general_getLanguage($uid);
	else
		$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	return $language;
}

/**
 * get all languages
 * @author Neithan
 * @param boolean $active (default: true) if true, only usable languages are returned
 * @return array
 */
function lib_bl_general_getLanguages($active = true)
{
	return lib_dal_general_getLanguages($active);
}

/**
 * formating the build times
 * @author Neithan
 * @param int $time
 * @param string $format
 * @return number|string returns a number or a string, depending on the format
 */
function lib_bl_general_formatTime($time, $format)
{
	$d = floor($time / 86400);
	$time_h = $time - $d * 86400;
	$h = floor($time_h / 3600);
	$time_m = $time_h - $h * 3600;
	$m = floor($time_m / 60);
	$s = floor($time_m - $m * 60);
	if ($d == 0)
		$d = '';
	if ($format == 'd')
		return $d;
	if ($format == "h")
		return $h;
	elseif ($format == "m")
	{
		if ($m < 10)
			return "0".$m;
		else
			return $m;
	}
	elseif ($format == "s")
	{
		if ($s < 10)
			return "0".$s;
		else
			return $s;
	}
	elseif ($format == "h:m:s")
	{
		if ($m < 10)
			$m = "0".$m;
		if ($s < 10)
			$s = "0".$s;
		return $h.":".$m.":".$s;
	}
	elseif ($format == 'd h:m:s')
	{
		unset($tf);
		if ($d)
			$tf = $d.'d ';
		if ($h < 10)
			$h = '0'.$h;
		if ($m < 10)
			$m = '0'.$m;
		if ($s < 10)
			$s = '0'.$s;
		$tf .= $h.":".$m.":".$s;
		return $tf;
	}
}

/**
 * get the actual season
 * @author Neithan
 * @return int
 */
function lib_bl_general_getSeason()
{
	return lib_dal_general_getSeason();
}

/**
 * get the max storage value
 * @author Neithan
 * @param string $city
 * @return int
 */
function lib_bl_general_getMaxStorage($city)
{
	$cityexp = explode(':', $city);
	$storage_building = lib_dal_buildings_getBuildingByKind(22, $cityexp[0], $cityexp[1]);
	$storage_lvl = $storage_building['lvl'];
	$storage = 10000;
	for ($i = 1; $i <= $storage_lvl; $i++)
		$storage = $storage*1.25;
	return (int)floor($storage);
}

/**
 * random generator for characters
 * @author Neithan
 * @return string
 */
function lib_bl_general_alpRand()
{
	$x = rand(1, 26);
	$alp = array(
		1 => "a", 2 => "b", 3 => "c", 4 => "d", 5 => "e", 6 =>  "f", 7 => "g", 8 => "h", 9 => "i", 10 => "j",
		11 => "k", 12 => "l", 13 => "m", 14 => "n", 15 => "o", 16 => "p", 17 => "q", 18 => "r", 19 => "s", 20 => "t",
		21 => "u", 22 => "v", 23 => "w", 24 => "x", 25 => "y", 26 => "z"
	);
	return $alp[$x];
}

/**
 * random generator for additional characters
 * @author Neithan
 * @return string
 */
function lib_bl_general_signRand()
{
	$x = rand(1, 5);
	$sign = array(1 => "!", 2 => "§", 3 => "$", 4 => "(", 5 => ")");
	return $sign[$x];
}

/**
 * sending messages
 * @author Neithan
 * @param int $uid_sender
 * @param int $uid_recipient
 * @param string $title
 * @param string $message
 * @param int $type
 * @return <int> returns 1 on success, otherwise 0
 */
function lib_bl_general_sendMessage($uid_sender, $uid_recipient, $title, $message, $type)
{
	if (is_numeric($uid_recipient) && is_numeric($uid_sender))
	{
		$date = time();
		$msgerg = lib_dal_general_sendMessage($uid_sender, $uid_recipient, $date, $title, $message, $type);
		if ($msgerg) {
			return 1;
		} else {
			return 0;
		}
	}
}

/**
 * deleting user
 * @author Neithan
 * @param int $reguid
 * @return <int> returns 1 on success, otherwise 0
 */
function lib_bl_general_delUser($reguid)
{
	$helperg = lib_dal_user_getClanRank($reguid);

	if (count($helperg) > 0) {
		$regcid = $helperg['cid'];
		$rankid = $helperg['rankid'];
	}

	if ($rankid == 1) {
		$helperg = lib_dal_user_getUIDFromCID($regcid);
		if (count($helperg) <= 0)
		{
			$clan_user = lib_dal_clan_getAllUser($regcid);
			$new_leader = rand(0, count($clan_user) - 1);
			$leaderg = lib_dal_general_setClanLeader($clan_user[$new_leader]['uid']);
		}
	}

	$erg1 = lib_dal_general_deleteFrom('dw_user', 'uid = '.$reguid);
	$erg2 = lib_dal_general_deleteFrom('dw_res', 'uid = '.$reguid);
	$erg3 = lib_dal_general_updateMap($reguid);
	$erg4 = lib_dal_general_deleteBuildings($reguid);
	$erg5 = lib_dal_general_deleteFrom('dw_message', 'uid_recipient = '.$reguid);
	$erg6 = lib_dal_general_deleteFrom('dw_points', 'uid = '.$reguid);
	$erg7 = lib_dal_general_deleteFrom('dw_clan_applications', 'uid = '.$reguid);
	$erg8 = lib_dal_general_deleteFrom('dw_research', 'uid = '.$reguid);

	if ($erg1 && $erg2 && $erg3 && $erg4 && $erg5 && $erg6 && $erg7 && $erg8)
		return 1;
	else
		return 0;
}

/**
 * deactivate user
 * @author Neithan
 * @param int $uid
 * @param int $state
 * @return <int> returns 1 on success, otherwise 0
 */
function lib_bl_general_deactivateUser($uid, $state)
{
	$helperg = lib_dal_user_getClanRank($reguid);
	if (count($helperg) > 0) {
		$regcid = $helperg['cid'];
		$rankid = $helperg['rankid'];
	}
	if ($rankid == 1) {
		$helperg = lib_dal_user_getUIDFromCID($regcid);
		if (count($helperg) <= 0)
		{
			$clan_user = lib_dal_clan_getAllUser($regcid);
			$new_leader = rand(0, count($clan_user) - 1);
			$leaderg = lib_dal_general_setClanLeader($clan_user[$new_leader]['uid']);
		}
	}
	$erg1 = lib_dal_general_deactivateUser($uid, $state);
	if ($erg1 > 0 && $leaderg > 0)
		return 1;
	else
		return 0;
}

/**
 * changing users position
 * @author Neithan
 * @param int $uid
 * @param int $x
 * @param int $y
 * @param string $city
 * @return <int> returns 1 on success
 * 		2 if there is a user at the selected coordinates and the uid's don't match
 * 		3 if the selected coordinates are not on the map or the terrain is 1 (water) or 5 (not walkable)
 * 		4 if the queries didn't return something
 */
function lib_bl_general_changePos($uid, $x, $y, $city)
{
	if ($x < 293 && $y < 91)
		return 3;
	else
	{
		$reguid = lib_dal_user_getUIDFromMapPosition($x, $y);
		if ($reguid != $uid)
			return 2;
		else
		{
			$terrain = lib_dal_map_getTerrain($x, $y);
			if ($terrain != 1 && $terrain != 5)
			{
				$erg3 = lib_dal_general_changePosition('uid = '.$uid);
				$where = 'map_x='.mysql_real_escape_string($x).' AND map_y='.mysql_real_escape_string($y);
				$erg4 = lib_dal_general_changePosition($where, $uid, $city);
				if ($erg3 && $erg4)
					return 1;
				else
					return 4;
			}
			else
				return 3;
		}
	}
}

/**
 * sending an email
 * @author Neithan
 * @param string $recipient
 * @param string $subject
 * @param string $message
 * @param string $sender [optional]
 * @return bool
 */
function lib_bl_general_sendMail($recipient, $subject, $message, $sender = 'support@dynasty-wars.de')
{
	$mailer = new PHPMailer(true);
	$mailer->AddReplyTo($recipient);
	$mailer->From($sender);
	if ($sender == 'support@dynasty-wars.de')
		$mailer->FromName('Dynasty Wars');
	$mailer->Subject = $subject;
	$mailer->AltBody = strip_tags($message);
	$mailer->MsgHTML($message);
	$mailer->IsHTML(true);
	return $mailer->Send();
}

/**
 * check if the menu entry is active or not
 * @author Neithan
 * @param string $entry_name
 * @return int
 */
function lib_bl_general_checkMenuEntry($entry_name)
{
	$check = lib_dal_general_checkMenuEntry($entry_name);
	if ($check < 1)
		header('Location: index.php?chose=home');
	else
		return $check;
}

/**
 * returns the game rank of this user
 * @author Neithan
 * @param int $uid
 * @return int
 */
function lib_bl_general_getGameRank($uid)
{
	$user = lib_dal_login_getAllData($uid);
	return $user['game_rank'];
}

/**
 * returns the nick of the user
 * @author Neithan
 * @param int $uid
 * @return string
 */
function lib_bl_general_uid2nick($uid)
{
	return lib_dal_user_uid2nick($uid);
}

/**
 * returns the uid to the given nick
 * @author Neithan
 * @param String $nick
 * @return ind
 */
function lib_bl_general_nick2uid($nick)
{
	return lib_dal_user_nick2uid($nick);
}

/**
 * generate pages
 * @author Neithan
 * @param string $chose
 * @param array $pages
 * @param string $parameters
 * @return string
 */
function lib_bl_general_createPageLinks($chose, $pages, $parameters)
{
	$html = '';
	$parameters = preg_replace('/\&/', '&amp;', $parameters);
	for ($i = 1; $i <= $pages; $i++)
		$html .= '<a href="index.php?chose='.$chose.'&amp;'.$parameters.'&amp;page='.$i.'">'.$i.'</a> ';
	return $html;
}

/**
 * redirects to the given site
 * @author Neithan
 * @param string $target
 * @return void
 */
function lib_bl_general_redirect($target)
{
	header('Location: '.$target);
	die();
}

/**
 * returns a text shortened to the given number of signs
 * @author Neithan
 * @param string $text
 * @param int $signs
 * @return string
 */
function lib_bl_general_cutOffText($text, $signs)
{
	$text = trim($text);
	if (strlen($text) > $signs)
	{
		$exp_text = explode(' ', $text);
		$text_array = array();
		foreach($exp_text as $part)
		{
			 $text_array[] = $part;
			 $signs -= strlen($part) + 1;
			 if ($signs <= 0)
			 	break;
		}
		return implode(' ', $text_array).'...';
	}
	else
		return $text;
}

/**
 * get the total number of unread messages
 * @author Neithan
 * @param int $recipient
 * @return int
 */
function lib_bl_general_getUnreadMessageCount($recipient)
{
	$msgs = lib_dal_general_getUnreadMessagesCount($recipient);
	if (!$msgs)
		$msgs = 0;
	return (int)$msgs;
}

/**
 * check, if there is a missionary for the specified user
 * @author Neithan
 * @param int $uid
 * @return boolean
 */
function lib_bl_general_getMissionary($uid)
{
	$missionary = lib_dal_general_getMissionary($uid);
	if ($missionary > 0)
		return true;
	else
		return false;
}

/**
 * loads a language file depending on the chosen language using the given page
 * and the location (default = ingame)
 * @author Neithan
 * @param String $page
 * @param String $location
 * @param boolean $isAjax
 * @param String $userLang if set, $lang will be returned
 * @return void/array
 */
function lib_bl_general_loadLanguageFile($page, $location = 'loggedin', $isAjax = false, $userLang = null)
{
	global $lang;

	$usedLang = $lang['lang'];

	if($userLang)
		$usedLang = $userLang;

	$path = '';

	if ($isAjax)
		$path .= '../../';

	$path .= 'language/'.$usedLang.'/'.($location ? $location.'/' : '');
	include_once($path.$page.'.php');

	if ($userLang)
		return $lang;
}