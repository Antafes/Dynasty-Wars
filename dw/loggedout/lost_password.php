<?php
include('loggedout/header.php');
include('lib/bl/lost_password.inc.php');
bl\general\loadLanguageFile('lost_password', 'loggedout');

$smarty->assign('heading', $lang['lost_password']);
$smarty->assign('id', $_GET['id']);

if (!$_GET['id'])
{
	$smarty->assign('action', 'index.php?chose=lost_password');
	if ($_POST['lp_sub'])
		$sent = bl\lostPassword\sendLostPasswordMail($_POST['email']);

	if ($sent)
	{
		if ($sent == 1)
			$smarty->assign('sent', $lang['successful']);
		elseif ($sent == -1)
			$smarty->assign('sent', $lang['no_user_found']);
		elseif ($sent == -2)
			$smarty->assign('sent', $lang['no_email']);
	}

	$smarty->assign('email', $lang['email']);
	$smarty->assign('button_send', $lang['send']);
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
				echo $lang['passwords_not_equal'];
			elseif ($error == -2)
				echo $lang['same_as_old'];
		}

		$smarty->assign('new_password', $lang['newpw']);
		$smarty->assign('repeat_password', $lang['reppw']);
		$smarty->assign('change', $lang['change']);
	}
	else
		$smarty->assign('wrong_id', $lang['wrong_id']);
}

$smarty->display($smarty->template_dir[0].'lost_password.tpl');
include('loggedout/footer.php');
?>