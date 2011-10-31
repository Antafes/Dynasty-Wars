<?php
include('loggedout/header.php');
lib_bl_general_loadLanguageFile('del_user', 'rest');

$smarty->assign('lang', $lang);

$uid = $_GET['uid'];
if ($uid)
{
	lib_bl_log_saveLog(23, $uid, '', '');
	lib_bl_general_deactivateUser((int)$uid, 1);
}
include('loggedout/footer.php');

$smarty->display('del_user.tpl');