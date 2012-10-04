<?php
session_start();
include_once(__DIR__.'/../config.php');

header('Content-Type: application/json');

include_once(__DIR__.'/../bl/general.inc.php');
include_once(__DIR__.'/../bl/login.php');
include_once(__DIR__.'/../dal/tribunal.php');
include_once(__DIR__.'/../bl/tribunal.php');

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

$check = bl\tribunal\approveArgument($item_list['aid'], $item_list['state']);
$argument = bl\tribunal\getArgument($item_list['aid']);

if ($argument['approved'] == 1)
	$approved = ' ['.$lang['approved'].']';
elseif ($argument['approved'] == -1)
	$approved = ' ['.$lang['not_approved'].']';
else
	$approved = ' ['.$lang['no_approve'].']';

$links = '';
if ($argument['approved'] == -1 || !$argument['approved'])
{
	$links .= '<a href="javascript:;" onclick="';
	$links .= 'argument_approval("lib/ajax/argument_approval.php", "accept", '.$argument['aid'].');';
	$links .= '">';
}
$links .= $lang['accept'];
if ($argument['approved'] == -1 || !$argument['approved'])
	$links .= '</a>';
$links .= ' ';
if ($argument['approved'] == 1 || !$argument['approved'])
{
	$links .= '<a href="javascript:;" onclick="';
	$links .= 'argument_approval("lib/ajax/argument_approval.php", "decline", '.$argument['aid'].');';
	$links .= '">';
}
$links .= $lang['decline'];
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