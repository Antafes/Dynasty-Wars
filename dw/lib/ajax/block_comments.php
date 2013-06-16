<?php
session_start();
require_once(__DIR__.'/../config.php');

header('Content-Type: application/json');

require_once(__DIR__.'/../bl/general.inc.php');
require_once(__DIR__.'/../dal/tribunal.php');
require_once(__DIR__.'/../bl/login.php');
require_once(__DIR__.'/../bl/tribunal.php');

$item_list = json_decode($_GET['items']);
$new_item_list = array();
foreach ($item_list as $part)
	$new_item_list[$part->name] = utf8_decode($part->value);
$item_list = $new_item_list;

$_SESSION['user'] = new bl\user\UserCls();
$_SESSION['user']->loadByUID($_SESSION['user']->getUIDFromId($_SESSION['lid']));

$lang['lang'] = $_SESSION['user']->getLanguage();
bl\general\loadLanguageFile('general', '');
bl\general\loadLanguageFile('tribunal');

$result = bl\tribunal\blockComments($item_list['tid'], $item_list['block']);

if ($result > 0)
{
	$hearing = bl\tribunal\getHearing($item_list['tid']);

	$html = '<a href="javascript:;" onclick="';
	$html .= 'blockComments(this, "lib/ajax/block_comments.php", ';
	if ($hearing['block_comments'] == 1)
		$html .= 0;
	else
		$html .= 1;
	$html .= ', '.$item_list['tid'].')">';
	$html .= $lang[($hearing['block_comments'] == 1 ? 'unblock_comments' : 'block_comments')];
	$html .= '</a>';

	echo json_encode(array(
		'html' => $html,
		'status' => 'ok',
	));
}
else
	echo json_encode(array('status' => 'failed'));