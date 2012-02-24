<?php
include('loggedin/header.php');
include('lib/bl/buildings.inc.php');

lib_bl_general_loadLanguageFile('building');

$template_file = '';

if (!$_GET['buildplace'])
{
	if ($_POST['sub_build'] || $_POST['sub_upgrade'])
	{
		if ($_POST['sub_upgrade'])
			$upgrade = 1;
		else
			$upgrade = 0;

		lib_bl_buildings_build((int) $_POST['buildplace'], $_SESSION['user']->getUID(), $city, $upgrade, $_POST['kind']);
		lib_bl_general_redirect(util\html\createLink(array('chose' => 'buildings'), true));
	}

	$cityexp = explode(':', $city);
	$buildings = lib_bl_buildings_selectAll($cityexp[0], $cityexp[1]);
	$religion = lib_bl_buildings_checkReligion($_SESSION['user']->getUID());
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
		22 => array('top' => 500, 'left' => 300)
	);
	$max_buildplaces = 19;
	if ($religion != 1)
		$max_buildplaces++;
	$check_geisha_factory = lib_bl_buildings_checkGeishaFactory($city);
	if ($check_geisha_factory['geisha'])
		$max_buildplaces++;
	if ($check_geisha_factory['factory'])
		$max_buildplaces++;
	$season = lib_bl_general_getSeason();
	/*if ($season == 1)
		$season = 'summer';
	elseif ($season == 2)
		$season = 'winter';*/
	$season = 'summer'; //this is a temporary solution, because there are no winter pics for the city background
	$terrain = 'grass';

	$smarty->assign('buildings', $lang['buildings']);
	$smarty->assign('cityBackground', util\html\createLink(array(
		'file' => 'pictures/city/'.$terrain.'/'.$season.'/city.jpg',
	)));
	$smarty->assign('maxBuildplaces', $max_buildplaces + 1); // + 1 for the loop in smarty
	$smarty->assign('buildingPositions', $city_position);
	$smarty->assign('wayPart', lib_bl_buildings_getPic($city, 'way_part'));

	$building_pictures = array();
	for ($i = 1; $i <= $max_buildplaces; $i++)
		$building_pictures[$i] = lib_bl_buildings_getPic($city, $buildings[$i]);
	$smarty->assign('buildingPictures', $building_pictures);

	$smarty->assign('isBuilding', $is_building);
	if ($is_building)
	{
		$smarty->assign('buildList', $lang['build_list']);
		$build_items = array();
		foreach ($is_building as $build)
			$build_items[$build['kind']] = $lang['building_names'][$build['kind']][$build['ulvl']];
		$smarty->assign('buildItems', $build_items);
	}
	$template_file = 'buildings.tpl';
}
elseif (is_numeric($_GET['buildplace']))
{
	$cityexp = explode(':', $city);
	$building = lib_bl_buildings_selectBuilding($cityexp[0], $cityexp[1], $_GET['buildplace']);
	$ressources = lib_bl_resource_newRes($range, $lumberjack, $quarry, $ironmine, $papermill, $tradepost, $city);

	if ($building['lvl'] || ($building['lvl'] == 0 && $_GET['buildplace'] < 8))
	{
		$has_upgrades = lib_bl_buildings_getUpgradeable($building['kind']);
		$has_harbour = lib_bl_buildings_getHarbour($cityexp[0], $cityexp[1]);
		$prices = lib_bl_buildings_prices($building['kind'], $building['lvl'], $building['ulvl'], $has_harbour, $city);
		$time = lib_bl_buildings_buildTime($building['kind'], $building['lvl']);

		if ($has_upgrades > 0)
			$u_time = lib_bl_buildings_buildTime($building['kind'], $building['lvl'], 1, $building['ulvl']);

		if (lib_bl_buildings_checkBuildable($_SESSION['user']->getUID(), $building['kind'], $cityexp[0], $cityexp[1]) == 0)
			$smarty->assign('notBuildable', 1);

		if ($building['kind'] != 6)
			$smarty->assign('buildingName', htmlentities($lang['building_names'][$building['kind']][$building['ulvl']]));
		elseif ($building['kind'] == 6)
			$smarty->assign('buildingName', htmlentities($lang['building_names'][$building['kind']][$has_harbour]));

		if (lib_bl_buildings_checkBuildable($_SESSION['user']->getUID(), $building['kind'], $cityexp[0], $cityexp[1]))
		{
			$smarty->assign('levelInfo', htmlentities($lang['level']));
			$smarty->assign('level', $building['lvl']);
		}

		$smarty->assign('buildPlace', $_GET['buildplace']);

		if (lib_bl_buildings_checkBuildable($_SESSION['user']->getUID(), $building['kind'], $cityexp[0], $cityexp[1]))
		{
			if ($building['lvl'])
				$smarty->assign('buildingPicture', lib_bl_buildings_getPic($city, $building));
			elseif ($building['lvl'] == 0 && $_GET['buildplace'] < 8)
				$smarty->assign('buildingPicture', lib_bl_buildings_getPic($city, $building, 1));

			if ($building['kind'] != 6)
				$smarty->assign('buildingDescription', htmlentities($lang['descr'][$building['kind']]));
			elseif ($building['kind'] == 6)
				$smarty->assign('buildingDescription', htmlentities($lang['descr'][$building['kind']][$has_harbour]));

			$smarty->assign('lvlup', htmlentities($lang['lvlup']));
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

			$can_build = lib_bl_buildings_resCheck($res_values);
			if ($building['kind'] == 19 && $building['lvl'] > 0 && $building['lvl'] % 10 == 0 && $building['lvl'] / 10 >= $building['ulvl'])
				$can_build = 0;

			$smarty->assign('canBuild', $can_build);
			$smarty->assign('freeBuildPosition', lib_bl_buildings_checkFreeBuildPosition($city, $building['kind']));
			$smarty->assign('build', htmlentities($lang['build']));
			$smarty->assign('buildTime', lib_bl_general_formatTime($time, 'd h:m:s'));
		}
		elseif (lib_bl_buildings_checkBuildable($_SESSION['user']->getUID(), $building['kind'], $cityexp[0], $cityexp[1]) == 0)
			$smarty->assign('notYetBuildable', htmlentities($lang['not_yet_buildable']));

		if ($has_upgrades)
		{
			$prices_upgr = $upgrade_prices = lib_bl_buildings_upgradePrices($building['kind'], $building['ulvl'], $building['lvl'], $building['ulvl']);

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
				$can_upgrade['resCheck'] = lib_bl_buildings_resCheck($res_values);
				$can_upgrade['upgradeCheck'] = lib_bl_buildings_checkUpgradeable($building['kind'], $city);
				$smarty->assign('canUpgrade', $can_upgrade);
				$smarty->assign('upgrade', htmlentities($lang['upgrade']));
				$smarty->assign('upgradeTime', lib_bl_general_formatTime($u_time, 'd h:m:s'));
			}
		}

		if ($building['kind'] == 19)
		{
			$smarty->assign('showDefenseBuildings', true);
			$defense = lib_bl_buildings_getDefense($city);
			$smarty->assign('defense', htmlentities($lang['defense']));
			$smarty->assign('upgradeLevel', $building['ulvl']);

			if ($building['ulvl'] >= 2)
			{
				$smarty->assign('defensePictures', array(
					'd23' => lib_bl_buildings_getPic($city, $defense[23]),
					'd24' => lib_bl_buildings_getPic($city, $defense[24]),
					'd25' => lib_bl_buildings_getPic($city, $defense[25]),
				));
			}
			elseif ($building['ulvl'] < 2)
				$smarty->assign('noBuild', htmlentities($lang['nobuild']));
		}
		$smarty->assign('back', htmlentities($lang['back']));

		$template_file = 'buildplace.tpl';
	}
	else
	{
		if ($_GET['buildplace'] < 23)
			$buildables = lib_bl_buildings_getNotBuilt($cityexp[0], $cityexp[1], $_SESSION['user']->getUID());
		else
			$buildables = lib_bl_buildings_getNotBuilt($cityexp[0], $cityexp[1], $_SESSION['user']->getUID(), 1);
		$smarty->assign('buildables', $buildables);
		$smarty->assign('newBuilding', htmlentities($lang['new_building']));

		if (count($buildables))
		{
			$smarty->assign('lvlUp', $lang['lvlup']);
			$smarty->assign('build', $lang['build']);
			$smarty_buildings = array();

			foreach ($buildables as $buildable)
			{
				if (lib_bl_buildings_checkBuildable($_SESSION['user']->getUID(), $buildable['kind'], $cityexp[0], $cityexp[1]))
				{
					$has_upgrades = lib_bl_buildings_getUpgradeable($buildable['kind']);

					if (!$has_upgrades || $buildable['ulvl'])
					{
						$prices = lib_bl_buildings_prices($buildable['kind'], $buildable['lvl'], $buildable['ulvl'], $has_harbour, $city);
						$time = lib_bl_buildings_buildTime($buildable['kind'], $buildable['lvl']);
					}
					else
					{
						$prices = lib_bl_buildings_upgradePrices($buildable['kind'], $buildable['lvl'], $buildable['ulvl'], $has_harbour);
						$time = lib_bl_buildings_buildTime($buildable['kind'], $buildable['lvl'], 1, $buildable['ulvl']);
					}

					if (lib_bl_buildings_getUpgradeable($buildable['kind']) && $buildable['ulvl'] == 0)
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
						'name' => htmlentities($lang['building_names'][$buildable['kind']][$buildable['ulvl']]),
						'image' => lib_bl_buildings_getPic($city, $buildable, 1),
						'prices' => $prices_formatted,
						'time' => lib_bl_general_formatTime($time, 'd h:m:s'),
						'canBuild' => lib_bl_buildings_resCheck($res_values),
						'freeBuildPosition' => lib_bl_buildings_checkFreeBuildPosition($city, $buildable['kind']),
					);
				}
			}

			$smarty->assign('buildablesCount', count($smarty_buildings));
			$smarty->assign('buildingsList', $smarty_buildings);
			$smarty->assign('back', htmlentities($lang['back']));
		}

		if (count($smarty_buildings) === 0)
			$smarty->assign('noBuilding', htmlentities($lang['no_building']));
		$template_file = 'buildings_list.tpl';
	}
}
include('loggedin/footer.php');

$smarty->display($template_file);