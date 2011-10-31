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

	$item_list = json_decode($_GET['items']);
	$new_item_list = array();
	foreach ($item_list as $part)
		$new_item_list[$part->name] = utf8_decode($part->value);
	$item_list = $new_item_list;
	$lang['lang'] = lib_bl_general_getLanguage($_SESSION['user']->getUID());
	include('../../language/'.$lang['lang'].'/ingame/tribunal.php');

	$result = lib_bl_tribunal_blockComments($item_list['tid'], $item_list['block']);

	if ($result > 0)
	{
		$hearing = lib_bl_tribunal_getHearing($item_list['tid']);

		$html = '<a href="javascript:;" onclick="';
		$html .= htmlentities('blockComments(this, "lib/ajax/block_comments.php", ');
		if ($hearing['block_comments'] == 1)
			$html .= 0;
		else
			$html .= 1;
		$html .= ', '.$item_list['tid'].')">';
		$html .= htmlentities($lang[($hearing['block_comments'] == 1 ? 'unblock_comments' : 'block_comments')]);
		$html .= '</a>';

		echo json_encode(array(
			'html' => $html,
			'status' => 'ok',
		));
	}
	else
		echo json_encode(array('status' => 'failed'));
}