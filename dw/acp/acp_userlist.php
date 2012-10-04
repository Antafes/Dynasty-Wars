<?php
bl\general\loadLanguageFile('userlist', 'acp');

$smarty->assign('lang', $lang);

if ($_GET['reguid'])
{
	$regUser = new bl\user\UserCls();
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

		bl\log\saveLog($status, $_SESSION['user']->getUID(), $regUser->getUID(), "");
	}

	if (isset($_POST["blocked"]))
	{
		//blocking/unblocking
		if ($_POST["blocked"] == 1)
		{
			$regUser->setBlocked();
			bl\log\saveLog(7, $_SESSION['user']->getUID(), $regUser->getUID(), "");
		}
		else
		{
			$regUser->unsetBlocked();
			bl\log\saveLog(8, $_SESSION['user']->getUID(), $regUser->getUID(), "");
		}
	}

	if ($_POST["free"])
	{
		//activation
		$regUser->unsetStatus();
		bl\log\saveLog(9, $_SESSION['user']->getUID(), $regUser->getUID(), "");
	}

	if ($_POST["send"])
	{
		//resend the activation mail
		$header = "From: Dynasty Wars <support@dynastywars.wafriv.de>";
		bl\general\sendMail($regUser->getEMail(), $lang['activateTitle'], sprintf($lang['activateMessage'], $regUser->getNick(), $regUser->getUID(), $regUser->getStatus()));
		bl\log\saveLog(24, $_SESSION['user']->getUID(), $regUser->getUID(), "");
	}

	if ($_GET["del"])
	{
		//deletion
		bl\log\saveLog(10, $_SESSION['user']->getUID(), $regUser->getUID(), "");
		bl\general\deleteUser($regUser->getUID());
	}

	if ($_POST["deact_user"])
	{
		//deactivate the user
		bl\log\saveLog(26, $regUser->getUID(), $_SESSION['user']->getUID(), '');
		$deact = bl\general\deactivateUser($regUser->getUID(), $_POST["deactivation"]);
	}

	if ($regUser && !$_GET["del"])
	{
		//selection of the positiondata
		$smarty->assign('regUser', $regUser);
	}
	else
	{
		//selection of the userinformations
		$smarty->assign('users', bl\user\getACPUserList());
	}
}

$smarty->assign('acpContent', $smarty->fetch('../acp/users.tpl'));