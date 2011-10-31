<?php
include('loggedin/header.php');
include('lib/bl/map.inc.php');

lib_bl_general_loadLanguageFile('map');


$smarty->assign('lang', $lang);

//abfrage GET, POST variablen
$x = $_GET['x'];
$y = $_GET['y'];
$ox = $_GET['ox'];
$oy = $_GET['oy'];
if ($_POST['x'] and $_POST['y'] and !$x and !$y) {
	$x = $_POST['x'];
	$y = $_POST['y'];
} elseif (!$_POST['x'] and !$_POST['y'] and !$x and !$y) {
	$city_exp = explode(':', $city);
	$x = $city_exp[0];
	$y = $city_exp[1];
}
if ($x > 394)
	$x = 394;
elseif ($y <= 90 and $x < 299)
	$x = 299;
elseif ($x < 6)
	$x = 6;

if ($y > 244)
	$y = 244;
elseif ($x <= 293 and $y < 96)
	$y = 96;
elseif ($y < 6)
	$y = 6;

$min_x = $x - 8;
$min_y = $y - 8;
$max_x = $x + 8;
$max_y = $y + 8;
$season = lib_bl_general_getSeason();
if ($season == 1)
	$path = 'pictures/map/summer/';
elseif ($season == 2)
	$path = 'pictures/map/winter/';

$smarty->assign('backgroundPath', $path);
$smarty->assign('mapX', $x);
$smarty->assign('mapY', $y);

$sql = '
	SELECT
		m.terrain,
		m.uid,
		m.map_x,
		m.map_y,
		u.deactivated
	FROM dw_map m
	LEFT JOIN dw_user u USING (uid)
	WHERE m.map_x BETWEEN '.mysql_real_escape_string($min_x).' AND '.mysql_real_escape_string($max_x).'
		AND m.map_y BETWEEN '.mysql_real_escape_string($min_y).' AND '.mysql_real_escape_string($max_y).'
	ORDER BY m.map_y, m.map_x
';
$mapRawData = lib_util_mysqlQuery($sql);

$uidList = array();
$mapData = array();
$row = 0;
$i = 0;
foreach ($mapRawData as $position)
{
	if (!$row)
		$row = $position['map_y'];
	if ($i == 17)
	{
		$row++;
		$i = 0;
	}

	$mapData[$row][] = array(
		'image' => lib_bl_mapTerrain($position['terrain']).'.png',
	) + $position;

	if ($position['uid'])
	{
		$uidList[] = array(
			'x' => $position['map_x'],
			'y' => $position['map_y'],
			'uid' => $position['uid'],
		);
	}

	$i++;
}

if ($uidList)
	$smarty->assign('uidList', json_encode($uidList));
$smarty->assign('mapData', $mapData);

include('loggedin/footer.php');

$smarty->display('map.tpl');