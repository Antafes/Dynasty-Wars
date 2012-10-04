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

$insert_id = bl\tribunal\saveComment($item_list['ajax_id'], $_SESSION['user']->getUID(), $item_list['comment_text']);

$array['status'] = 'ok';
$array['html'] = '<div class="comment">
	'.sprintf($lang['comment_from'], bl\general\uid2nick($_SESSION['user']->getUID())).'
	<div class="comment_at">'.sprintf($lang['comment_at'], date($lang['acptimeformat'], time())).'</div>
	<div class="comment_content">
		'.nl2br($parser->parseIt($item_list['comment_text'])).'
	</div>
	<div class="comment_options">
		<a href="javascript:;" onclick="editComment(this, \'comment_dialog\', '.$insert_id.', \'lib/ajax/edit_comment.php\', \''.$lang['save'].'\', \''.$lang['cancel'].'\', 400)">'.$lang['edit'].'</a>
		<a href="javascript:;" onclick="deleteComment(this, '.$insert_id.')">'.$lang['delete'].'</a>
	</div>
	<hr />
</div>';

foreach ($array as &$part)
	$part = utf8_encode($part);

echo json_encode($array);