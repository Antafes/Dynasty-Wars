<?php
if ($gameOptions['adminmail'])
	$smarty->assign('adminmail', $gameOptions['adminmail']);
$smarty->assign('version', $gameOptions['version']);
ob_end_flush();