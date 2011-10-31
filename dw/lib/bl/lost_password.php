<?php
/**
 * generates an unique id for this lost password action
 * @author Neithan
 * @param string $email
 * @return void|string
 */
function lib_bl_lost_password_generateID($email)
{
	$uid = lib_dal_user_returnUID($email);
	if ($uid)
	{
		unset($id);
		for ($i = 0; $i < 25; $i++)
		{
			$rand = rand(1,3);
			if ($rand == 1)
				$id .= lib_bl_general_alpRand();
			if ($rand == 2)
				$id .= rand(0,9);
			if ($rand == 3)
				$id .= lib_bl_general_signRand();
		}
		$uid_pos = rand(0,19);
		$parts[0] = substr($id, 0, $uid_pos);
		$parts[1] = substr($id, $uid_pos, -4);
		$parts[2] = substr($id, -4);
		if ($uid_pos < 10)
			$uid_pos = '0'.$uid_pos;
		if ($uid < 10)
			$uid = '00'.$uid;
		elseif ($uid < 100)
			$uid = '0'.$uid;
		return $parts[0].$uid.$parts[1].$uid_pos.$parts[2];
	}
	else
		return ;
}

/**
 * send an email for the password recovery
 * @author Neithan
 * @param string $email
 * @return <int> returns 1 on success
 * 				 returns 0 if failed
 * 				 returns -1 if no id has been created (no user found)
 * 				 returns -2 if the e-mail format is wrong
 */
function lib_bl_lost_password_sendLPMail($email)
{
	global $lang;
	$check = lib_bl_register_checkMail($email);
	if ($check > 0)
	{
		$id = lib_bl_lost_password_generateID($email);
		if ($id)
		{
			$uid = lib_dal_user_returnUID($email);
			$user = lib_dal_user_getUserInfos($uid);
			$link = DIR_WS_INDEX.'?chose=lost_password&id='.$id;
			$message = sprintf($lang['email_html'], $link, date($lang['timeformat']), date($lang['clockformat']));
			lib_bl_general_sendMail($user['email'], $lang['subject'], $message);
			$rec_check = lib_dal_lost_password_checkRecoveries($uid);
			if ($rec_check)
				$recovery = lib_dal_lost_password_updateRecovery($id, time());
			else
				$recovery = lib_dal_lost_password_insertRecovery($id, time(), $uid);
			if ($recovery > 0)
				return 1;
			else
				return 0;
		}
		else
			return -1;
	}
	else
		return -2;
}

/**
 * check if the id is in the db
 * @author Neithan
 * @param string $id
 * @return <int> returns the uid on success, otherwise 0
 */
function lib_bl_lost_password_checkID($id)
{
	$uid_pos = (int)substr($id, -6, 2);
	$uid = (int)substr($id, $uid_pos, 3);
	$recovery = lib_dal_lost_password_getRecoveryRequest($uid);
	if (strcasecmp($id, $recovery['mailid']) == 0)
		return $uid;
	else
		return 0;
}

/**
 * change the users password
 * @author Neithan
 * @param string $newpw
 * @param string $newpww
 * @param int $uid
 * @return <int> returns 1 on success
 * 				 returns -1 if the entered passwords are not the same
 * 				 returns -2 if the old password is the same like the new
 */
function lib_bl_lost_password_changePassword($newpw, $newpww, $uid)
{
	global $lang;
	if ($newpw === $newpww)
	{
		$pws = md5(mysql_real_escape_string($newpw));
		$changed = lib_bl_options_changePassword($pws, $uid);
		if ($changed)
		{
			$city = lib_bl_login_getMainCity($uid);
			$id = lib_bl_login_createId($uid);
			$_SESSION["lid"] = $id;
			$_SESSION["city"] = $city;
			$_SESSION["language"] = $lang["lang"];
			lib_bl_lost_password_removeRecoveryRequest($uid);
			return 1;
		}
		else
			return -2;
	}
	else
		return -1;
}

/**
 * delete the recovery request
 * @author Neithan
 * @param int $uid
 * @return int
 */
function lib_bl_lost_password_removeRecoveryRequest($uid)
{
	return lib_dal_lost_password_removeRecoveryRequest($uid);
}
?>