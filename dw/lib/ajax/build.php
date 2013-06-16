<?php
// general ajax functionality
session_start();
require_once(__DIR__.'/../config.php');

header('Content-Type: application/json');

require_once(__DIR__.'/../bl/general.inc.php');
require_once(__DIR__.'/../bl/login.inc.php');
require_once(__DIR__.'/../bl/resource.inc.php');

$_SESSION['user'] = new bl\user\UserCls();
$_SESSION['user']->loadByUID($_SESSION['user']->getUIDFromId($_SESSION['lid']));

$lang['lang'] = $_SESSION['user']->getLanguage();
bl\general\loadLanguageFile('general', '');
bl\general\loadLanguageFile('building');

// build functionality
require_once(__DIR__.'/../bl/buildings.inc.php');

$response = array(
	'error' => array(),
);

if (!$_POST['buildPlace'])
	$response['error'][] = 'buildPlaceMissing';

if (!$_POST['city'])
	$response['error'][] = 'cityMissing';

if (!$response['error'])
{
	bl\buildings\build((int) $_POST['buildPlace'], $_SESSION['user']->getUID(), $_POST['city'], $_POST['type'] == 'upgrade', $_POST['kind']);

	$is_building = bl\buildings\checkBuild($_SESSION['user']->getUID(), $_POST['city']);

	if ($is_building)
	{
		foreach ($is_building as $build)
		{
			$response['timer'] = array(
				'endTime' => $build['endtime']->format('F d, Y H:i:s'),
				'now' => date('F d, Y H:i:s'),
				'bid' => $build['bid'],
				'kind' => $build['kind'],
				'position' => $build['position'],
				'text' => $lang['building_names'][$build['kind']][$build['ulvl']],
			);
		}
	}
}

echo \bl\general\jsonEncode($response);