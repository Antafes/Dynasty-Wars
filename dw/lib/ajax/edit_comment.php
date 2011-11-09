<?php
session_start();
include_once('../config.php');

$con = @mysql_connect($server, $seruser, $serpw);
if ($con)
{
	mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

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

	$_SESSION['user'] = new UserCls();
	$_SESSION['user']->loadByUID($_SESSION['user']->getUIDFromId($_SESSION['lid']));

	$lang['lang'] = $_SESSION['user']->getLanguage();
	lib_bl_general_loadLanguageFile('general', '', true);
	lib_bl_general_loadLanguageFile('tribunal', 'loggedin', true);

	lib_bl_tribunal_editComment($item_list['ajax_id'], $item_list['comment_text'], $_SESSION['user']->getUID());
	$comment = lib_bl_tribunal_getComment($item_list['ajax_id']);

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
			'.htmlentities(sprintf($lang['last_changed'], $changed_count, lib_bl_general_uid2nick($comment['last_changed_from']), date($lang['acptimeformat'], $comment['date_last_changed']))).'
		</div>';

	foreach ($array as &$part)
		$part = utf8_encode($part);

	echo json_encode($array);
}