<?php
include ('loggedout/header.php');
lib_bl_general_loadLanguageFile('login', 'loggedout');
unset ($err);
$login = $_POST['login'];
$user = $_POST['nick'];
$pw = $_POST['pw'];
$pws = md5($pw);
$save_login = $_POST['save_login'];

$smarty->assign('heading', $lang['login']);
$smarty->assign('login', $login);

if ($login == 1)
{
	$login = lib_bl_login_getAllData($user);
	$uid = $login['uid'];
	$reguser = $login['nick'];
	$regpw = $login['password'];
	$blocked = $login['blocked'];
	$status = $login['status'];
	$admin = $login['game_rank'];
	$lang['lang'] = $login['language'];
	$deactivated = $login['deactivated'];
	$login_closed = lib_bl_login_checkLogin();
	if ($status)
		$err['status'] = 1;
	if ($blocked)
		$err['blocked'] = 1;
	if (($login_closed == 1) && ($_SESSION['user']->getGameRank() < 1))
		$err['login_closed'] = 1;
	elseif (($login_closed == 2) && ($_SESSION['user']->getGameRank() < 2))
		$err['login_closed'] = 2;
	if (
		(strcasecmp($user, $reguser) == 0 && $pws === $regpw)
		&& !$err['status']
		&& !$err['blocked']
		&& !$err['login_closed']
		&& !$deactivated
	)
	{
		lib_bl_login_setLastLogin($uid);
		$city = lib_bl_login_getMainCity($uid);
		$id = lib_bl_login_createId($uid);
		if ($save_login)
		{
			setcookie('lid', $id, time()+604800, '', '.dynasty-wars.de');
			setcookie('city', $city, time()+604800, '', '.dynasty-wars.de');
			setcookie('language', $lang['lang'], time()+604800, '', '.dynasty-wars.de');
		} else
		{
			$_SESSION['lid'] = $id;
			$_SESSION['city'] = $city;
			$_SESSION['language'] = $lang['lang'];
		}
		lib_bl_general_redirect(lib_util_html_createLink(array('chose' => 'home'), true));
	}
	else
	{
		if (strcasecmp($user, $reguser) != 0 || $pws !== $regpw)
			$err['failed_login'] = true;

		if ($err['failed_login'])
			$error = htmlentities($lang['loginfailed']);
		elseif ($err['status'])
			$error = nl2br(htmlentities($lang['noactivation']));
		elseif ($err['blocked'])
			$error = nl2br(htmlentities($lang['blocked']));
		elseif ($err['login_closed'] == 1)
			$error = htmlentities($lang['onlyadmin']);
		elseif ($err['login_closed'] == 2)
			$error = htmlentities($lang['loginclosed']);
		elseif ($deactivated)
			$error = nl2br(htmlentities($lang['deactivated']));

		$smarty->assign('error', $error);
		$smarty->assign('back', htmlentities($lang['back']));
	}
}
else
{
	$smarty->assign('name', htmlentities($lang['name']));
	$smarty->assign('password', htmlentities($lang['password']));
	$smarty->assign('remind_login', htmlentities($lang['remindlogin']));
	$smarty->assign('login_button', htmlentities($lang['login']));
	$smarty->assign('lost_password', htmlentities($lang['lost_password']));
}

include ('loggedout/footer.php');

$smarty->display($smarty->template_dir[0].'login.tpl');