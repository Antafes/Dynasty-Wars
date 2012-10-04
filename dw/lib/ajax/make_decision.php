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

$result = bl\tribunal\makeDecision($item_list['ajax_id'], $item_list['decision'], $item_list['reason']);
if ($result > 0)
{
	$hearing = bl\tribunal\getHearing($item_list['ajax_id']);
	$html = '
		<div class="row">
			<div class="left">
				'.$lang['decision'].'
			</div>
			<div class="right" style="width: 500px;">
				'.$lang[$item_list['decision']].'
			</div>
		</div>
		<div class="row">
			<div class="left">
				'.$lang['reason'].'
			</div>
			<div class="right" style="width: 500px;">
				'.nl2br($parser->parseIt($item_list['reason'])).'
			</div>
		</div>
	';

	if (count($hearing['arguments']) > 0)
	{
		$arguments = array();
		foreach ($hearing['arguments'] as $argument)
			$arguments[] = '#approval_links'.$argument['aid'];
	}
	$arguments[] = '#new_argument';

	echo json_encode(array(
		'html' => $html,
		'status' => 'ok',
		'remove' => $arguments,
	));
}
else
	echo json_encode(array('status' => 'failed'));