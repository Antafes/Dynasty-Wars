<?php
session_start();
include_once(__DIR__.'/../config.php');

header('Content-Type: application/json');

include_once(__DIR__.'/../dal/map.php');
include_once(__DIR__.'/../bl/general.inc.php');
include_once(__DIR__.'/../bl/login.php');
include_once(__DIR__.'/../bl/map.php');

$firePHP = FirePHP::getInstance(true);

if (!$debug || !$firePHP_debug)
	$firePHP->setEnabled(false);
else
{
	$firePHP->setEnabled(true);
	$firePHP->registerErrorHandler($throwErrorExceptions=true);
	$firePHP->registerExceptionHandler();
	$firePHP->registerAssertionHandler($convertAssertionErrorsToExceptions=true, $throwAssertionExceptions=false);
}

$newX = $_GET['new_x'];
$x = $_GET['x'];
$newY = $_GET['new_y'];
$y = $_GET['y'];
$minY = $newY - 8;
$maxY = $newY + 8;
$minX = $newX - 8;
$maxX = $newX + 8;

$sql = '
	SELECT
		m.terrain,
		m.uid,
		m.city,
		u.nick,
		m.map_x,
		m.map_y,
		u.cid,
		u.deactivated,
		c.clanname,
		c.clantag
	FROM dw_map m
	LEFT JOIN dw_user u USING (uid)
	LEFT JOIN dw_clan c USING (cid)
	WHERE m.map_x BETWEEN '.util\mysql\sqlval($minX).' AND '.util\mysql\sqlval($maxX).'
		AND m.map_y BETWEEN '.util\mysql\sqlval($minY).' AND '.util\mysql\sqlval($maxY).'
	ORDER BY m.map_y, m.map_x
';
$mapRawData = util\mysql\query($sql);

$mapData = array();
$row = 0;
$oldY = 0;
foreach ($mapRawData as $position)
{
	if (!$oldY)
		$oldY = $position['map_y'];

	if ($oldY != $position['map_y'])
	{
		$row++;
		$oldY = $position['map_y'];
	}

	$mapData[$row][] = array(
		'image' => bl\map\terrain($position['terrain']).'.png',
		'uid' => (int) $position['uid'],
		'map_x' => (int) $position['map_x'],
		'map_y' => (int) $position['map_y'],
		'deactivated' => (int) $position['deactivated'],
		'cid' => (int) $position['cid'],
		'terrain' => (int) $position['terrain'],
	) + $position;
}

if ($mapData)
	echo json_encode($mapData);