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
	include('../../language/'.$lang['lang'].'/general.php');

	$array['status'] = 'ok';

	$comment_array = lib_bl_tribunal_getComment($item_list['tcoid']);
	$array['comment'] = $comment_array['comment'];

	foreach ($array as &$part)
	{
		$part = utf8_encode($part);
	}

	echo json_encode($array);
}