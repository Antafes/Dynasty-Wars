<?php
include('loggedin/header.php');
include('lib/bl/buildings.inc.php');

bl\general\loadLanguageFile('building');

$template_file = '';

if (!$_GET['buildplace'])
{
	if ($_POST['sub_build'] || $_POST['sub_upgrade'])
	{
		if ($_POST['sub_upgrade'])
			$upgrade = 1;
		else
			$upgrade = 0;

		bl\buildings\build((int) $_POST['buildplace'], $_SESSION['user']->getUID(), $city, $upgrade, $_POST['kind']);
		bl\general\redirect(util\html\createLink(array('chose' => 'buildings'), true));
	}

	$cityexp = explode(':', $city);
	$buildings = bl\buildings\selectAll($cityexp[0], $cityexp[1]);
	$religion = bl\buildings\checkReligion($_SESSION['user']->getUID());
	$city_position = array(
		1 => array('top' => 255, 'left' => 278),
		2 => ($buildings[2]['lvl'] == 0 ? array('top' => 460, 'left' => 384) : array('top' => 410, 'left' => 384)),
		3 => array('top' => 410, 'left' => 42),
		4 => array('top' => 62, 'left' => 478),
		5 => array('top' => 28, 'left' => 435),
		6 => array('top' => 350, 'left' => 198),
		7 => array('top' => 70, 'left' => 234),
		8 => array('top' => 168, 'left' => 300),
		9 => array('top' => 220, 'left' => 374),
		10 => array('top' => 294, 'left' => 360),
		11 => array('top' => 338, 'left' => 290),
		12 => array('top' => 280, 'left' => 188),
		13 => array('top' => 206, 'left' => 206),
		14 => array('top' => 126, 'left' => 162),
		15 => array('top' => 156, 'left' => 86),
		16 => array('top' => 192, 'left' => 10),
		17 => array('top' => 224, 'left' => 124),
		18 => array('top' => 300, 'left' => 68),
		19 => array('top' => 222, 'left' => 458),
		20 => array('top' => 370, 'left' => 376),
		21 => array('top' => 424, 'left' => 290),
	);
	$max_buildplaces = 19;
	$check_geisha_factory = bl\buildings\checkGeishaAndFactory($city);

	if ($check_geisha_factory['geisha'])
		$max_buildplaces++;

	if ($check_geisha_factory['factory'])
		$max_buildplaces++;

//	$season = bl\general\getSeason();
//	if ($season == 1)
//		$season = 'summer';
//	elseif ($season == 2)
//		$season = 'winter';

	$season = 'summer'; //this is a temporary solution, because there are no winter pics for the city background
	$terrain = 'grass';

	$smarty->assign('buildings', $lang['buildings']);
	$smarty->assign('cityBackground', util\html\createLink(array(
		'file' => 'pictures/city/'.$terrain.'/'.$season.'/city.jpg',
	)));
	$smarty->assign('maxBuildplaces', $max_buildplaces + 1); // + 1 for the loop in smarty
	$smarty->assign('buildingPositions', $city_position);

	$building_pictures = array();
	for ($i = 1; $i <= $max_buildplaces; $i++)
		$building_pictures[$i] = bl\buildings\getBuildPlacePicture($city, $buildings[$i]);
	$smarty->assign('buildingPictures', $building_pictures);

	// $is_building is filled in header.php
	$smarty->assign('isBuilding', $is_building);
	$smarty->assign('buildList', $lang['build_list']);

	$build_items = array();
	if ($is_building)
		foreach ($is_building as $build)
			$build_items[$build['kind']] = $lang['building_names'][$build['kind']][$build['ulvl']];

	$smarty->assign('buildItems', $build_items);
	$template_file = 'buildings.tpl';
}
elseif (is_numeric($_GET['buildplace']))
{
	$cityexp = explode(':', $city);
	$building = bl\buildings\selectBuilding($cityexp[0], $cityexp[1], $_GET['buildplace']);
	$ressources = bl\resource\newResources($range, $lumberjack, $quarry, $ironmine, $papermill, $tradepost, $city);

	if ($building['lvl'] || ($building['lvl'] == 0 && $_GET['buildplace'] < 8))
	{
		$has_upgrades = bl\buildings\getUpgradeable($building['kind']);
		$has_harbour = bl\buildings\getHarbour($cityexp[0], $cityexp[1]);
		$prices = bl\buildings\prices($building['kind'], $building['lvl'], $has_harbour, $city);
		$time = bl\buildings\buildTime($building['kind'], $building['lvl']);

		if ($has_upgrades > 0)
			$u_time = bl\buildings\buildTime($building['kind'], $building['lvl'], 1, $building['ulvl']);

		if (bl\buildings\checkBuildable($_SESSION['user']->getUID(), $building['kind'], $cityexp[0], $cityexp[1]) == 0)
			$smarty->assign('notBuildable', 1);

		if ($building['kind'] != 6)
			$smarty->assign('buildingName', $lang['building_names'][$building['kind']][$building['ulvl']]);
		elseif ($building['kind'] == 6)
			$smarty->assign('buildingName', $lang['building_names'][$building['kind']][$has_harbour]);

		if (bl\buildings\checkBuildable($_SESSION['user']->getUID(), $building['kind'], $cityexp[0], $cityexp[1]))
		{
			$smarty->assign('levelInfo', $lang['level']);
			$smarty->assign('level', $building['lvl']);
		}

		$smarty->assign('buildPlace', $_GET['buildplace']);

		if (bl\buildings\checkBuildable($_SESSION['user']->getUID(), $building['kind'], $cityexp[0], $cityexp[1]))
		{
			if ($building['lvl'])
				$smarty->assign('buildingPicture', bl\buildings\getBuildPlacePicture($city, $building));
			elseif ($building['lvl'] == 0 && $_GET['buildplace'] < 8)
				$smarty->assign('buildingPicture', bl\buildings\getBuildPlacePicture($city, $building, 1));

			if ($building['kind'] != 6)
				$smarty->assign('buildingDescription', $lang['descr'][$building['kind']]);
			elseif ($building['kind'] == 6)
				$smarty->assign('buildingDescription', $lang['descr'][$building['kind']][$has_harbour]);

			$smarty->assign('lvlup', $lang['lvlup']);
			$smarty->assign('buildingRessources', array(
				'food' => util\math\numberFormat($prices['food'], 0),
				'wood' => util\math\numberFormat($prices['wood'], 0),
				'rock' => util\math\numberFormat($prices['rock'], 0),
				'iron' => util\math\numberFormat($prices['iron'], 0),
				'paper' => util\math\numberFormat($prices['paper'], 0),
				'koku' => util\math\numberFormat($prices['koku'], 0),
			));

			$res_values = array(
				'res_food' => $ressources['food'],
				'res_wood' => $ressources['wood'],
				'res_rock' => $ressources['rock'],
				'res_iron' => $ressources['iron'],
				'res_paper' => $ressources['paper'],
				'res_koku' => $ressources['koku'],
			);
			if (is_array($prices))
				$res_values += $prices;

			$can_build = bl\buildings\resourceCheck($res_values);
			if ($building['kind'] == 19 && $building['lvl'] > 0 && $building['lvl'] % 10 == 0 && $building['lvl'] / 10 >= $building['ulvl'])
				$can_build = 0;

			$smarty->assign('canBuild', $can_build);
			$smarty->assign('freeBuildPosition', bl\buildings\checkFreeBuildPosition($city, $building['kind']));
			$smarty->assign('build', $lang['build']);
			$smarty->assign('buildTime', bl\general\formatTime($time, 'd h:m:s'));
		}
		elseif (bl\buildings\checkBuildable($_SESSION['user']->getUID(), $building['kind'], $cityexp[0], $cityexp[1]) == 0)
			$smarty->assign('notYetBuildable', $lang['not_yet_buildable']);

		if ($has_upgrades)
		{
			$prices_upgr = $upgrade_prices = bl\buildings\upgradePrices($building['kind'], $building['ulvl'], $building['lvl'], $building['ulvl']);

			if (is_array($upgrade_prices))
			{
				//moved here, because there is no upgrade posibility, if the building has reached it's maximum upgrade
				$smarty->assign('hasUpgrades', $has_upgrades);
				foreach ($upgrade_prices as &$upgrade_price)
					$upgrade_price = util\math\numberFormat($upgrade_price, 0);
				$smarty->assign('upgradePrices', $upgrade_prices);
				$res_values = array(
					'res_food' => $ressources['food'],
					'res_wood' => $ressources['wood'],
					'res_rock' => $ressources['rock'],
					'res_iron' => $ressources['iron'],
					'res_paper' => $ressources['paper'],
					'res_koku' => $ressources['koku'],
				);
				if (is_array($prices_upgr))
					$res_values += $prices_upgr;
				$can_upgrade['resCheck'] = bl\buildings\resourceCheck($res_values);
				$can_upgrade['upgradeCheck'] = bl\buildings\checkUpgradeable($building['kind'], $city);
				$smarty->assign('canUpgrade', $can_upgrade);
				$smarty->assign('upgrade', $lang['upgrade']);
				$smarty->assign('upgradeTime', bl\general\formatTime($u_time, 'd h:m:s'));
			}
		}

		if ($building['kind'] == 19)
		{
			$smarty->assign('showDefenseBuildings', true);
			$defense = bl\buildings\getDefense($city);
			$smarty->assign('defense', $lang['defense']);
			$smarty->assign('upgradeLevel', $building['ulvl']);

			if ($building['ulvl'] >= 2)
			{
				$smarty->assign('defensePictures', array(
					'd23' => bl\buildings\getBuildPlacePicture($city, $defense[23]),
					'd24' => bl\buildings\getBuildPlacePicture($city, $defense[24]),
					'd25' => bl\buildings\getBuildPlacePicture($city, $defense[25]),
				));
			}
			elseif ($building['ulvl'] < 2)
				$smarty->assign('noBuild', $lang['nobuild']);
		}
		$smarty->assign('back', $lang['back']);

		$template_file = 'buildplace.tpl';
	}
	else
	{
		if ($_GET['buildplace'] < 23)
			$buildables = bl\buildings\getNotBuilt($cityexp[0], $cityexp[1], $_SESSION['user']->getUID());
		else
			$buildables = bl\buildings\getNotBuilt($cityexp[0], $cityexp[1], $_SESSION['user']->getUID(), 1);
		$smarty->assign('buildables', $buildables);
		$smarty->assign('newBuilding', $lang['new_building']);

		if (count($buildables))
		{
			$smarty->assign('lvlUp', $lang['lvlup']);
			$smarty->assign('build', $lang['build']);
			$smarty_buildings = array();

			foreach ($buildables as $buildable)
			{
				if (bl\buildings\checkBuildable($_SESSION['user']->getUID(), $buildable['kind'], $cityexp[0], $cityexp[1]))
				{
					$has_upgrades = bl\buildings\getUpgradeable($buildable['kind']);

					if (!$has_upgrades || $buildable['ulvl'])
					{
						$prices = bl\buildings\prices($buildable['kind'], $buildable['lvl'], $has_harbour, $city);
						$time = bl\buildings\buildTime($buildable['kind'], $buildable['lvl']);
					}
					else
					{
						$prices = bl\buildings\upgradePrices($buildable['kind'], $buildable['lvl'], $buildable['ulvl'], $has_harbour);
						$time = bl\buildings\buildTime($buildable['kind'], $buildable['lvl'], 1, $buildable['ulvl']);
					}

					if (bl\buildings\getUpgradeable($buildable['kind']) && $buildable['ulvl'] == 0)
						$buildable['ulvl'] = 1;

					$prices_formatted = array(
						'food' => util\math\numberFormat($prices['food'], 0),
						'wood' => util\math\numberFormat($prices['wood'], 0),
						'rock' => util\math\numberFormat($prices['rock'], 0),
						'iron' => util\math\numberFormat($prices['iron'], 0),
						'paper' => util\math\numberFormat($prices['paper'], 0),
						'koku' => util\math\numberFormat($prices['koku'], 0),
					);
					$res_values = array(
						'res_food' => $ressources['food'],
						'res_wood' => $ressources['wood'],
						'res_rock' => $ressources['rock'],
						'res_iron' => $ressources['iron'],
						'res_paper' => $ressources['paper'],
						'res_koku' => $ressources['koku'],
					);

					if (is_array($prices))
						$res_values += $prices;

					$smarty_buildings[] = array(
						'kind' => $buildable['kind'],
						'name' => $lang['building_names'][$buildable['kind']][$buildable['ulvl']],
						'image' => bl\buildings\getBuildPlacePicture($city, $buildable, 1),
						'prices' => $prices_formatted,
						'time' => bl\general\formatTime($time, 'd h:m:s'),
						'canBuild' => bl\buildings\resourceCheck($res_values),
						'freeBuildPosition' => bl\buildings\checkFreeBuildPosition($city, $buildable['kind']),
					);
				}
			}

			$smarty->assign('buildablesCount', count($smarty_buildings));
			$smarty->assign('buildingsList', $smarty_buildings);
			$smarty->assign('back', $lang['back']);
		}

		if (count($smarty_buildings) === 0)
			$smarty->assign('noBuilding', $lang['no_building']);
		$template_file = 'buildings_list.tpl';
	}
}
include('loggedin/footer.php');

$smarty->display($template_file);