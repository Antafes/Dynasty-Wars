<?php
session_start();
include_once('../config.php');

$con = @mysql_connect($server, $seruser, $serpw);
if ($con)
{
	mysql_select_db($serdb, $con) or die('Fehler, keine Datenbank!');

	include_once('../bl/general.ajax.inc.php');
	include_once('../bl/login.php');
	include_once('../dal/tribunal.php');
	include_once('../bl/tribunal.php');

	$parser = new wikiparser();

	$item_list = json_decode($_GET['items']);
	$new_item_list = array();
	foreach ($item_list as $part)
		$new_item_list[$part->name] = utf8_decode($part->value);
	$item_list = $new_item_list;
	$insert_id = lib_bl_tribunal_saveComment($item_list['ajax_id'], $_SESSION['user']->getUID(), $item_list['comment_text']);

	$lang['lang'] = lib_bl_general_getLanguage($_SESSION['user']->getUID());
	include('../../language/'.$lang['lang'].'/ingame/tribunal.php');
	include('../../language/'.$lang['lang'].'/general.php');

	$array['status'] = 'ok';
	$array['html'] = '<div class="comment">
		'.htmlentities(sprintf($lang['comment_from'], lib_bl_general_uid2nick($_SESSION['user']->getUID()))).'
		<div class="comment_at">'.htmlentities(sprintf($lang['comment_at'], date($lang['acptimeformat'], time()))).'</div>
		<div class="comment_content">
			'.nl2br($parser->parseIt($item_list['comment_text'])).'
		</div>
		<div class="comment_options">
			<a href="javascript:;" onclick="editComment(this, \'comment_dialog\', '.$insert_id.', \'lib/ajax/edit_comment.php\', \''.$lang['save'].'\', \''.$lang['cancel'].'\', 400)">'.htmlentities($lang['edit']).'</a>
			<a href="javascript:;" onclick="deleteComment(this, '.$insert_id.')">'.htmlentities($lang['delete']).'</a>
		</div>
		<hr />
	</div>';

	foreach ($array as &$part)
		$part = utf8_encode($part);

	echo json_encode($array);
}