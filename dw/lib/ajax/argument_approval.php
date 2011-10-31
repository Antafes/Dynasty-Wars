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

	$check = lib_bl_tribunal_approveArgument($item_list['aid'], $item_list['state']);
	$argument = lib_bl_tribunal_getArgument($item_list['aid']);

	if ($argument['approved'] == 1)
		$approved = ' ['.$lang['approved'].']';
	elseif ($argument['approved'] == -1)
		$approved = ' ['.$lang['not_approved'].']';
	else
		$approved = ' ['.$lang['no_approve'].']';

	$links = '';
	if ($argument['approved'] == -1 or !$argument['approved'])
	{
		$links .= '<a href="javascript:;" onclick="';
		$links .= htmlentities('argument_approval("lib/ajax/argument_approval.php", "accept", '.$argument['aid'].');');
		$links .= '">';
	}
	$links .= htmlentities($lang['accept']);
	if ($argument['approved'] == -1 or !$argument['approved'])
		$links .= '</a>';
	$links .= ' ';
	if ($argument['approved'] == 1 or !$argument['approved'])
	{
		$links .= '<a href="javascript:;" onclick="';
		$links .= htmlentities('argument_approval("lib/ajax/argument_approval.php", "decline", '.$argument['aid'].');');
		$links .= '">';
	}
	$links .= htmlentities($lang['decline']);
	if ($argument['approved'] == 1)
		$links .= '</a>';

	if ($check > 0)
	{
		echo json_encode(array(
			'status' => 'ok',
			'approvalState' => $approved,
			'approvalLinks' => $links,
		));
	}
	else
		echo json_encode(array('status' => 'failed'));
}