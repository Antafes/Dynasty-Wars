<?php
$unitSmarty = new Smarty();

$unitSmarty->assign('lang', $lang);

$unit = $_GET['unit'];
$build = $_POST['build'];
$count = $_POST['count'];

if ($build && $unit && $count)
{
	$train = lib_bl_unit_train_train($unit, $count, $_SESSION['user']->getUID(), $city);
	$ressources = lib_bl_resource_newRes($range, $lumberjack, $quarry, $ironmine, $papermill, $tradepost, $city);
	$food = $ressources['food'];
	$wood = $ressources['wood'];
	$rock = $ressources['rock'];
	$iron = $ressources['iron'];
	$paper = $ressources['paper'];
	$koku = $ressources['koku'];
	$train_check = lib_bl_unit_train_checkTraining($_SESSION['user']->getUID(), $city);
}

if ($train_check['ok'] && $build)
	lib_bl_general_redirect ('index.php?chose=units');

$buildings = lib_bl_unit_train_checkBuildings($city);
$check = count($buildings);
$unitSmarty->assign('check', $check);
$unitSmarty->assign('unitBuild', $lang['unitbuild']);
if (!$check)
	$unitSmarty->assign('noBuild', $lang['no_build']);
else
{
	$sortedUnitsArray = array(
		1 => array(
			'building' => 7,
			'ulvl' => 1,
		),
		4 => array(
			'building' => 7,
			'ulvl' => 2,
		),
		9 => array(
			'building' => 7,
			'ulvl' => 3,
		),
		2 => array(
			'building' => 8,
			'ulvl' => 1,
		),
		7 => array(
			'building' => 8,
			'ulvl' => 2,
		),
		14 => array(
			'building' => 8,
			'ulvl' => 3,
		),
		3 => array(
			'building' => 9,
			'ulvl' => 0,
		),
		5 => array(
			'building' => 10,
			'ulvl' => 0,
		),
		6 => array(
			'building' => 12,
			'ulvl' => 0,
		),
		8 => array(
			'building' => 17,
			'ulvl' => 1,
		),
		10 => array(
			'building' => 17,
			'ulvl' => 2,
		),
		15 => array(
			'building' => 17,
			'ulvl' => 4,
		),
		11 => array(
			'building' => 20,
			'ulvl' => 1,
		),
		12 => array(
			'building' => 18,
			'ulvl' => 1,
		),
		13 => array(
			'building' => 21,
			'ulvl' => 1,
		),
		16 => array(
			'building' => 15,
			'ulvl' => 0,
		),
		17 => array(
			'building' => 15,
			'ulvl' => 0,
		),
		18 => array(
			'building' => 11,
			'ulvl' => 0,
		),
	);

	$cap = lib_bl_troops_getCaps();
	$unitSmarty->assign('trainCheck', $train_check);
	$unitSmarty->assign('build', $lang['build']);
	$unitSmarty->assign('buildTime', $lang['time']);
	$unitSmarty->assign('capacity', $lang['cap']);
	$unitSmarty->assign('ressources', array(
		'food' => $lang['food'],
		'wood' => $lang['wood'],
		'rock' => $lang['rock'],
		'iron' => $lang['iron'],
		'paper' => $lang['paper'],
		'koku' => $lang['koku'],
	));

	$unitList = array();
	foreach ($sortedUnitsArray as $unitKind => $currentUnit)
	{
		$building = $buildings[$currentUnit['building']];
		if ($building['bid'] && $building['ulvl'] >= $currentUnit['ulvl'])
		{
			$unit = lib_bl_unit_getUnits($unitKind, $_SESSION['user']->getUID());
			if ($unit)
			{
				unset($count);
				foreach ($unit as $part)
					$count = $count+$part['count'];
			}
			else
				$count = 0;

			$prices = lib_bl_unit_train_unitPrices($unitKind);
			$check = lib_bl_buildings_resCheck($food, $wood, $rock, $iron, $paper, $koku, $prices['food'], $prices['wood'], $prices['rock'], $prices['iron'], $prices['paper'], $prices['koku']);
			$picture = lib_bl_unit_train_getUnitPicture($unitKind);

			$unitList[] = array(
				'kind' => $unitKind,
				'name' => $lang['unit'][$unitKind],
				'count' => number_format($count, 0, $lang['decimals'], $lang['thousands']),
				'picture' => $picture,
				'description' => $lang['u_descr'][$unitKind],
				'price' => $prices,
				'maxBuildable' => lib_bl_unit_train_maxUnits($unitKind, $city),
				'check' => $check,
				'buildTime' => lib_bl_general_formatTime(lib_bl_unit_train_trainTime($unitKind), 'h:m:s'),
				'capacity' => number_format($cap[$unitKind], 0, $lang['decimals'], $lang['thousands']),
			);
		}
	}
	$unitSmarty->assign('unitList', $unitList);
}

$smarty->assign('unitContent', $unitSmarty->fetch('units_build.tpl'));