<?php
include("loggedin/header.php");
include_once('lib/bl/register.inc.php');

bl\general\loadLanguageFile('options');
$smarty->assign('lang', $lang);

if ($_GET['del'])
{
	if ($_POST["delcheck"])
	{
		$header = "From: Dynasty Wars <support@dynastywars.wafriv.de>";
		mail ($email, $lang["deltitle"], sprintf($lang["delmsg"], $_SESSION['user']->getNick(), $_SESSION['user']->getUID()), $header);
		$smarty->assign('infoMessage', $lang['accountDeleteMessage']);
	}
}

if ($_POST['changepw'] == 1)
{
	if (lib_bl_options_getOldPassword($_SESSION['user']->getUID()) == md5($_POST['oldpw']) && $_POST['newpw'] == $_POST['newpww'])
	{
		if ($_SESSION['user']->setPW($_POST['newpw'], $_POST['newpww']))
		{
			$id = bl\login\createID($_SESSION['user']->getUID());

			if ($_SESSION['lid'])
				$_SESSION['lid'] = $id;
			else
				setcookie("lid", $id, time() + 604800, "", ".dynasty-wars.de");

			$smarty->assign('infoMessage', $lang['passwordChanged']);
		}
	}
}

if ($_GET['textchange'])
{
	$_SESSION['user']->setDescription($_POST['description']);
	$_SESSION['user']->setLanguage($_POST['language']);
	$_SESSION['language'] = $_POST['language'];
	$smarty->assign('infoMessage', $lang['descriptionChanged']);
}

if ($_POST['changeemail'] == 1)
{
	$check_mail = bl\register\checkMail($_POST['email']);
	if (!$check_mail)
	{
		$smarty->assign('infoMessage', $lang['emailFormat']);
		unset($_POST['email']);
	}
	else
	{
		if ($_POST['email'])
			if ($_SESSION['user']->setEmail($_POST['email']))
				$smarty->assign('infoMessage', $lang['emailChanged']);
		else
			$smarty->assign('infoMessage', $lang['noEmail']);
	}
}

if ($_GET['leave'] == 2)
{
	$sql = '
		SELECT COUNT(*)
		FROM dw_user
		WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
	';
	$users = util\mysql\query($sql);
	$sql = '
		SELECT COUNT(u.uid)
		FROM dw_user u
		JOIN dw_clan_rank cr USING (rankid)
		WHERE u.uid = '.mysql_real_escape_string($_SESSION['user']->getUid()).'
			AND cr.admin
		GROUP BY u.uid
	';
	$admins = util\mysql\query($sql);

	if ($users && $admins)
	{
		if ($admins == 1 && $users > 1)
		{
			$sql = '
				SELECT rankid
				FROM dw_clan_rank
				WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
					AND admin
			';
			$adminRankID = util\mysql\query($sql);

			$sql = '
				SELECT u.uid
				FROM dw_user u
				JOIN dw_clan_rank cr USING (rankid)
				WHERE u.cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
					AND !cr.standard
					AND !u.deleted
				ORDER BY RAND()
				LIMIT 1
			';
			$newAdmin = util\mysql\query($sql);

			if (!$newAdmin)
			{
				$sql = '
					SELECT u.uid
					FROM dw_user u
					WHERE u.cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
						AND !u.deleted
					ORDER BY RAND()
					LIMIT 1
				';
				$newAdmin = util\mysql\query($sql);
			}

			$newAdminUser = new bl\user\UserCls();
			$newAdminUser->loadByUID($newAdmin);
			$newAdminUser->setRankID($adminRankID);
		}

		if ($users == 1)
		{
			$sql = '
				DELETE FROM dw_clan
				WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
			';
			$deleteResult = util\mysql\query($sql);
			$sql = '
				DELETE FROM dw_clan_rank
				WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
			';
			$delerg2 = util\mysql\query($sql);
		}

		if ($_SESSION['user']->setCID(0))
			$smarty->assign('infoMessage', $lang['clanLeft']);
	}
}

$languages = bl\general\getLanguages();

$languagesArray = array();
foreach ($languages as $language)
	$languagesArray[$language['language']] = $language['name'];

$smarty->assign('languages', $languagesArray);

include("loggedin/footer.php");

$smarty->display('options.tpl');