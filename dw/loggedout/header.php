<?php
ob_start();
$lang['lang'] = bl\general\getLanguageByUID();
bl\general\loadLanguageFile('main', 'loggedout');
bl\general\loadLanguageFile('general', null);
$gameOptions = util\mysql\query('SELECT board, show_board, adminmail, version FROM dw_game');

$smarty->assign('logo_image', 'pictures/logo.png');

$smarty->assign('menu_home', $lang['home']);
$smarty->assign('menu_news', $lang['news']);
$smarty->assign('menu_board', $lang['board']);
if ($gameOptions['board'])
	$smarty->assign('menu_board_link', 'http://'.$gameOptions['board']);
$smarty->assign('menu_login', $lang['login']);
$smarty->assign('menu_register', $lang['register']);
$smarty->assign('menu_imprint', $lang['imprint']);

// load css files
\util\html\load_css('main');

// load js files
\util\html\load_js('jquery-1.9.1.min');
\util\html\load_js('several');
