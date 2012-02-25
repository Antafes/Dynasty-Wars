<?php
include('loggedout/header.php');
bl\general\loadLanguageFile('activation', 'rest');

$smarty->assign('lang', $lang);

if ($_REQUEST['id'])
{
	$id = $_REQUEST['id'];
	$idt = explode('/', $id);
	$id1 = mysql_real_escape_string($idt[0]);
	$id2 = mysql_real_escape_string($idt[1]);
	$stat = util\mysql\query('SELECT status FROM dw_user WHERE uid='.$id1.'');
	$errors = array();
	if ($stat)
	{
		if (strcasecmp($id2, $stat) == 0 && $id1)
		{
			$erg2 = util\mysql\query('UPDATE dw_user SET status = "" WHERE uid='.$id1.'');
			bl\log\saveLog(3, $id1, 0, '');
			if ($erg2)
				$errors['activated'] = 1;
			else
				$errors['activationFailed'] = 1;
		} else
			$errors['activationFailed'] = 1;
	} else
		$errors['notlocated'] = 1;

	$smarty->assign('errors', $errors);
}

include('loggedout/footer.php');

$smarty->display('activation.tpl');