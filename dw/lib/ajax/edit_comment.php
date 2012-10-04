<?php
session_start();
include_once(__DIR__.'/../config.php');

header('Content-Type: application/json');

include_once(__DIR__.'/../bl/general.inc.php');
include_once(__DIR__.'/../bl/login.php');
include_once(__DIR__.'/../dal/tribunal.php');
include_once(__DIR__.'/../bl/tribunal.php');

$parser = new bl\wikiParser\WikiParser();

$item_list = json_decode($_GET['items']);
$new_item_list = array();
foreach ($item_list as $part)
	$new_item_list[$part->name] = utf8_decode($part->value);
$item_list = $new_item_list;

$_SESSION['user'] = new bl\user\UserCls();
$_SESSION['user']->loadByUID($_SESSION['user']->getUIDFromId($_SESSION['lid']));

$lang['lang'] = $_SESSION['user']->getLanguage();
bl\general\loadLanguageFile('general', '', true);
bl\general\loadLanguageFile('tribunal', 'loggedin', true);

bl\tribunal\editComment($item_list['ajax_id'], $item_list['comment_text'], $_SESSION['user']->getUID());
$comment = bl\tribunal\getComment($item_list['ajax_id']);

$array['status'] = 'ok';
$array['html'] = '<div class="comment_content">
	'.nl2br($parser->parseIt($comment['comment'])).'
</div>';
if ($comment['changed_count'] == 1)
	$changed_count = $lang['one'];
else
	$changed_count = $comment['changed_count'];
$array['html'] .= '
	<div class="comment_changed">
		'.sprintf($lang['last_changed'], $changed_count, bl\general\uid2nick($comment['last_changed_from'], date($lang['acptimeformat'], $comment['date_last_changed']))).'
	</div>';

foreach ($array as &$part)
	$part = utf8_encode($part);

echo json_encode($array);