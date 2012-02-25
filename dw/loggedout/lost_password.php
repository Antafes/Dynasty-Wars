<?php
include('loggedout/header.php');
include('lib/bl/lost_password.inc.php');
bl\general\loadLanguageFile('lost_password', 'loggedout');

$smarty->assign('heading', htmlentities($lang['lost_password']));
$smarty->assign('id', $_GET['id']);

if (!$_GET['id'])
{
	$smarty->assign('action', 'index.php?chose=lost_password');
	if ($_POST['lp_sub'])
		$sent = bl\lostPassword\sendLostPasswordMail($_POST['email']);

	if ($sent)
	{
		if ($sent == 1)
			$smarty->assign('sent', htmlentities($lang['successful']));
		elseif ($sent == -1)
			$smarty->assign('sent', htmlentities($lang['no_user_found']));
		elseif ($sent == -2)
			$smarty->assign('sent', htmlentities($lang['no_email']));
	}

	$smarty->assign('email', htmlentities($lang['email']));
	$smarty->assign('button_send', htmlentities($lang['send']));
}
elseif ($_GET['id'])
{
	$smarty->assign('action', 'index.php?chose=lost_password&id='.$_GET['id']);
	$uid = bl\lostPassword\checkID($_GET['id']);
	if ($uid > 0)
	{
		if ($_POST['lostpwsub'])
		{
			$error = bl\lostPassword\changePassword($_POST['newpw'], $_POST['newpww'], $uid);
			if ($error == 1)
				header('Location: index.php');
		}

		if ($error)
		{
			if ($error == -1)
				echo htmlentities($lang['passwords_not_equal']);
			elseif ($error == -2)
				echo htmlentities($lang['same_as_old']);
		}

		$smarty->assign('new_password', htmlentities($lang['newpw']));
		$smarty->assign('repeat_password', htmlentities($lang['reppw']));
		$smarty->assign('change', htmlentities($lang['change']));
	}
	else
		$smarty->assign('wrong_id', htmlentities($lang['wrong_id']));
}

$smarty->display($smarty->template_dir[0].'lost_password.tpl');
include('loggedout/footer.php');
?>