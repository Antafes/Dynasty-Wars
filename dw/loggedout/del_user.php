<?php
include('loggedout/header.php');
bl\general\loadLanguageFile('del_user', 'rest');

$smarty->assign('lang', $lang);

$uid = $_GET['uid'];
if ($uid)
{
	bl\log\saveLog(23, $uid, '', '');
	bl\general\deactivateUser((int)$uid, 1);
}
include('loggedout/footer.php');

$smarty->display('del_user.tpl');