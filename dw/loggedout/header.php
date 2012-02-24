<?php
ob_start();
$lang['lang'] = lib_bl_general_getLanguage();
lib_bl_general_loadLanguageFile('main', 'loggedout');
lib_bl_general_loadLanguageFile('general', null);
$gameOptions = util\mysql\query('SELECT board, show_board, adminmail, version FROM dw_game');

$smarty->assign('logo_image', 'pictures/logo.png');

$smarty->assign('menu_home', htmlentities($lang['home']));
$smarty->assign('menu_news', htmlentities($lang['news']));
$smarty->assign('menu_board', htmlentities($lang['board']));
if ($gameOptions['board'])
	$smarty->assign('menu_board_link', 'http://'.$gameOptions['board']);
$smarty->assign('menu_login', htmlentities($lang['login']));
$smarty->assign('menu_register', htmlentities($lang['register']));
$smarty->assign('menu_imprint', htmlentities($lang['imprint']));