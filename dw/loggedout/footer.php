<?php
if ($gameOptions['adminmail'])
{
	$smarty->assign('adminmail', $gameOptions['adminmail']);
}

$smarty->assign('version', $gameOptions['version']);
$smarty->assign('nowYear', date('Y'));
ob_end_flush();