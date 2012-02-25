<?php
namespace bl\lostPassword;

/**
 * generates an unique id for this lost password action
 * @author Neithan
 * @param string $email
 * @return void|string
 */
function generateID($email)
{
	$uid = \dal\user\returnUID($email);
	if ($uid)
	{
		$id = '';
		for ($i = 0; $i < 25; $i++)
		{
			$rand = rand(1,3);
			if ($rand == 1)
				$id .= \bl\general\alpRand();
			if ($rand == 2)
				$id .= rand(0,9);
			if ($rand == 3)
				$id .= \bl\general\signRand();
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
 * @global array $lang
 * @param string $email
 * @return int returns 1 on success
 * 				 returns 0 if failed
 * 				 returns -1 if no id has been created (no user found)
 * 				 returns -2 if the e-mail format is wrong
 */
function sendLostPasswordMail($email)
{
	global $lang;

	$check = \bl\register\checkMail($email);

	if ($check > 0)
	{
		$id = generateID($email);

		if ($id)
		{
			$uid = \dal\user\returnUID($email);
			$user = \dal\user\getUserInfos($uid);
			$link = DIR_WS_INDEX.'?chose=lost_password&id='.$id;
			$message = sprintf($lang['email_html'], $link, date($lang['timeformat']), date($lang['clockformat']));
			\bl\general\sendMail($user['email'], $lang['subject'], $message);
			$rec_check = \dal\lostPassword\checkRecoveries($uid);

			if ($rec_check)
				$recovery = \dal\lostPassword\updateRecovery($id, time());
			else
				$recovery = \dal\lostPassword\insertRecovery($id, time(), $uid);

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
 * @return int returns the uid on success, otherwise 0
 */
function checkID($id)
{
	$uid_pos = (int)substr($id, -6, 2);
	$uid = (int)substr($id, $uid_pos, 3);
	$recovery = \dal\lostPassword\getRecoveryRequest($uid);

	if (strcasecmp($id, $recovery['mailid']) == 0)
		return $uid;
	else
		return 0;
}

/**
 * change the users password
 * @author Neithan
 * @global array $lang
 * @param string $newpw
 * @param string $newpww
 * @param int $uid
 * @return int returns 1 on success
 * 				 returns -1 if the entered passwords are not the same
 */
function changePassword($newpw, $newpww, $uid)
{
	global $lang;

	$changed = $_SESSION['user']->setPW($newpw, $newpww);

	if ($changed)
	{
		$city = \bl\login\getMainCity($uid);
		$id = \bl\login\createID($uid);
		$_SESSION["lid"] = $id;
		$_SESSION["city"] = $city;
		$_SESSION["language"] = $lang["lang"];
		removeRecoveryRequest($uid);
		return 1;

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
function removeRecoveryRequest($uid)
{
	return \dal\lostPassword\removeRecoveryRequest($uid);
}