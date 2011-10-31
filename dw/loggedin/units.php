<?php
include('loggedin/header.php');

lib_bl_general_loadLanguageFile('units');

$smarty->assign('lang', $lang);

if ($_GET['sub'] == 'build' xor !$_GET['sub'])
	include('loggedin/units_build.php');
elseif ($_GET['sub'] == 'move')
	include('loggedin/units_move.php');

include('loggedin/footer.php');

$smarty->display('units.tpl');