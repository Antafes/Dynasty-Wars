<?php
session_start();
include_once('../config.php');

header('Content-type: text/json');

$con = @mysql_connect($server, $seruser, $serpw);
if ($con)
{
	mysql_select_db($serdb, $con) or die('Fehler, keine Datenbank!');

	include_once('../dal/tribunal.php');
	include_once('../dal/map.php');
	include_once('../bl/general.ajax.inc.php');
	include_once('../bl/login.php');
	include_once('../bl/map.php');

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
		WHERE m.map_x BETWEEN '.mysql_real_escape_string($minX).' AND '.mysql_real_escape_string($maxX).'
			AND m.map_y BETWEEN '.mysql_real_escape_string($minY).' AND '.mysql_real_escape_string($maxY).'
		ORDER BY m.map_y, m.map_x
	';
	$mapRawData = lib_util_mysqlQuery($sql);

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
			'image' => lib_bl_mapTerrain($position['terrain']).'.png',
			'uid' => (int) $position['uid'],
			'map_x' => (int) $position['map_x'],
			'map_y' => (int) $position['map_y'],
			'deactivated' => (int) $position['deactivated'],
			'cid' => (int) $position['cid'],
			'terrain' => (int) $position['terrain'],
		) + $position;
	}
}

if ($mapData)
	echo json_encode($mapData);