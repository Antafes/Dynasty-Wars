<?php
lib_bl_general_loadLanguageFile('userlist', 'acp');

$smarty->assign('lang', $lang);

if ($_GET['reguid'])
{
	$regUser = new UserCls();
	$regUser->loadByUID($_GET['reguid']);
}

if ($_SESSION['user']->getGameRank() == 1 || $_SESSION['user']->getGameRank() == 2)
{
	$smarty->assign('fromc', urlencode('acp§sub=userlist§reguid='));

	if (isset($_POST['nregadmin']))
	{
		//setting of user status
		$regUser->setGameRank($_POST['nregadmin']);

		if ($_POST['nregadmin'] == 3)
			$status = 35;
		elseif ($_POST['nregadmin'] == 2)
			$status = 11;
		elseif ($_POST['nregadmin'] == 1)
			$status = 12;
		elseif ($_POST['nregadmin'] == 0)
			$status = 13;

		lib_bl_log_saveLog($status, $_SESSION['user']->getUID(), $regUser->getUID(), "");
	}

	if (isset($_POST["blocked"]))
	{
		//blocking/unblocking
		if ($_POST["blocked"] == 1)
		{
			$regUser->setBlocked();
			lib_bl_log_saveLog(7, $_SESSION['user']->getUID(), $regUser->getUID(), "");
		}
		else
		{
			$regUser->unsetBlocked();
			lib_bl_log_saveLog(8, $_SESSION['user']->getUID(), $regUser->getUID(), "");
		}
	}

	if ($_POST["free"])
	{
		//activation
		$regUser->unsetStatus();
		lib_bl_log_saveLog(9, $_SESSION['user']->getUID(), $regUser->getUID(), "");
	}

	if ($_POST["send"])
	{
		//resend the activation mail
		$header = "From: Dynasty Wars <support@dynastywars.wafriv.de>";
		lib_bl_general_sendMail($regUser->getEMail(), $lang['activateTitle'], sprintf($lang['activateMessage'], $regUser->getNick(), $regUser->getUID(), $regUser->getStatus()));
		lib_bl_log_saveLog(24, $_SESSION['user']->getUID(), $regUser->getUID(), "");
	}

	if ($_GET["del"])
	{
		//deletion
		lib_bl_log_saveLog(10, $_SESSION['user']->getUID(), $regUser->getUID(), "");
		lib_bl_general_delUser($regUser->getUID());
	}

	if ($_POST["deact_user"])
	{
		//deactivate the user
		lib_bl_log_saveLog(26, $regUser->getUID(), $_SESSION['user']->getUID(), '');
		$deact = lib_bl_general_deactivateUser($regUser->getUID(), $_POST["deactivation"]);
	}

	if ($regUser && !$_GET["del"])
	{
		//selection of the positiondata
		$smarty->assign('regUser', $regUser);
	}
	else
	{
		//selection of the userinformations
		$smarty->assign('users', lib_bl_user_getACPUserList());
	}
}

$smarty->assign('acpContent', $smarty->fetch('../acp/users.tpl'));