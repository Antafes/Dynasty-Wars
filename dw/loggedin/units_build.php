<?php
$unitSmarty = new Smarty();
$unitSmarty->setTemplateDir('templates/loggedin/');

$unitSmarty->assign('lang', $lang);

$unit = $_GET['unit'];
$build = $_POST['build'];
$count = $_POST['count'];

$train_check = bl\unit\train\checkTraining($_SESSION['user']->getUID(), $city);

$readyScript = '';
if ($train_check['ok'])
	$readyScript .= sprintf('timer(\'%s\', \'%s\', %u);'."\n", $train_check['endtime']->format('F d, Y H:i:s'), date('F d, Y H:i:s'), $train_check['kind']);

\util\html\load_js_ready_script($readyScript);

if ($build && $unit && $count)
{
	$train = bl\unit\train\train($unit, $count, $_SESSION['user']->getUID(), $city);
	$resources = bl\resource\newResources($city);
	$food = $resources['food'];
	$wood = $resources['wood'];
	$rock = $resources['rock'];
	$iron = $resources['iron'];
	$paper = $resources['paper'];
	$koku = $resources['koku'];
	$train_check = bl\unit\train\checkTraining($_SESSION['user']->getUID(), $city);
}

if ($train_check['ok'] && $build)
	bl\general\redirect ('index.php?chose=units');

$buildings = bl\unit\train\checkBuildings($city);
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

	$cap = bl\troops\getCapacities();
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
		if ($building && $building['bid'] && $building['ulvl'] >= $currentUnit['ulvl'])
		{
			$unit = bl\unit\getUnits($unitKind, $_SESSION['user']->getUID());
			if ($unit)
			{
				unset($count);
				foreach ($unit as $part)
					$count = $count+$part['count'];
			}
			else
				$count = 0;

			$prices = bl\unit\train\unitPrices($unitKind);
			$check = bl\buildings\resourceCheck($food, $wood, $rock, $iron, $paper, $koku, $prices['food'], $prices['wood'], $prices['rock'], $prices['iron'], $prices['paper'], $prices['koku']);
			$picture = bl\unit\train\getUnitPicture($unitKind);
			$unitAmount = bl\unit\train\getTrainingUnits($_SESSION['user']->getUID(), $_SESSION['user']->getMainCity(), $unitKind);

			$unitList[] = array(
				'kind' => $unitKind,
				'name' => $lang['unit'][$unitKind],
				'count' => number_format($count, 0, $lang['decimals'], $lang['thousands']),
				'picture' => $picture,
				'description' => $lang['u_descr'][$unitKind],
				'price' => $prices,
				'maxBuildable' => bl\unit\train\maxUnits($unitKind, $city),
				'check' => $check,
				'buildTime' => bl\general\formatTime(bl\unit\train\trainTime($unitKind), 'h:m:s'),
				'capacity' => number_format($cap[$unitKind], 0, $lang['decimals'], $lang['thousands']),
				'unitAmount' => $unitAmount,
			);
		}
	}
	$unitSmarty->assign('unitList', $unitList);
}

$smarty->assign('unitContent', $unitSmarty->fetch('units_build.tpl'));