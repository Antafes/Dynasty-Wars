<?php
session_destroy();
setcookie('lid', $_COOKIE['lid'], time()-100, '', '.dynasty-wars.de');
setcookie('city', $city, time()-100, '', '.dynasty-wars.de');
setcookie('language', $lang['lang'], time()-100, '', '.dynasty-wars.de');
if ($_COOKIE['own_uid'])
	setcookie('own_uid', $_COOKIE['own_uid'], time()-100, '', '.dynasty-wars.de');
$lang['lang'] = lib_bl_general_getLanguage();
lib_bl_general_loadLanguageFile('logout');
$smarty->assign('lang', $lang);
include('loggedout/header.php');

include('loggedout/footer.php');

$smarty->display('logout.tpl');