<?php
session_start();
require_once(__DIR__.'/../config.php');

header('Content-Type: application/json');

require_once(__DIR__.'/../bl/general.inc.php');
require_once(__DIR__.'/../bl/login.inc.php');
require_once(__DIR__.'/../bl/buildings.inc.php');
require_once(__DIR__.'/../bl/resource.inc.php');

if ($_GET['buildPlace'])
{
	$city = $_GET['city'];
	$_SESSION['user'] = new bl\user\UserCls();
	$_SESSION['user']->loadByUID($_SESSION['user']->getUIDFromId($_SESSION['lid']));
	$lang['lang'] = $_SESSION['user']->getLanguage();
	bl\general\loadLanguageFile('general', '');
	bl\general\loadLanguageFile('building', 'loggedin');
	$cityExp = explode(':', $city);

	if ($_SESSION['user']->checkCity($cityExp[0], $cityExp[1]))
	{
		$building = \bl\buildings\selectBuilding($cityExp[0], $cityExp[1], $_GET['buildPlace']);

		if ($building)
		{
			$resources = \bl\resource\newResources($_GET['city']);
			$has_upgrades = bl\buildings\getUpgradeable($building['kind']);
			$has_harbour = bl\buildings\getHarbour($cityExp[0], $cityExp[1]);
			$prices = bl\buildings\prices($building['kind'], $building['lvl'], $has_harbour, $city);
			$time = bl\buildings\buildTime($building['kind'], $building['lvl']);

			if ($has_upgrades > 0)
				$u_time = bl\buildings\buildTime($building['kind'], $building['lvl'], 1, $building['ulvl']);

			if (bl\buildings\checkBuildable($_SESSION['user']->getUID(), $building['kind'], $cityExp[0], $cityExp[1]) == 0)
				$building['notBuildable'] = 1;

			if (!$building['ulvl'] && $has_upgrades)
				$building['ulvl'] = 1;

			if ($building['kind'] != 6)
				$building['buildingName'] = $lang['building_names'][$building['kind']][$building['ulvl']];
			elseif ($building['kind'] == 6)
				$building['buildingName'] = $lang['building_names'][$building['kind']][$has_harbour];

			if (bl\buildings\checkBuildable($_SESSION['user']->getUID(), $building['kind'], $cityExp[0], $cityExp[1]))
			{
				$building['level'] = $lang['level'].' '.$building['lvl'];
				unset($building['lvl']);
			}

			$building['buildPlace'] = $_GET['buildPlace'];

			if (bl\buildings\checkBuildable($_SESSION['user']->getUID(), $building['kind'], $cityExp[0], $cityExp[1]))
			{
				if ($building['lvl'])
					$building['buildingPicture'] = bl\buildings\getBuildPlacePicture($city, $building);
				elseif ($building['lvl'] == 0 && $_GET['buildplace'] < 8)
					$building['buildingPicture'] = bl\buildings\getBuildPlacePicture($city, $building, 1);

				if ($building['kind'] != 6)
					$building['buildingDescription'] = $lang['descr'][$building['kind']];
				elseif ($building['kind'] == 6)
					$building['buildingDescription'] = $lang['descr'][$building['kind']][$has_harbour];

				$building['buildingResources'] = array(
					'food' => util\math\numberFormat($prices['food'], 0),
					'wood' => util\math\numberFormat($prices['wood'], 0),
					'rock' => util\math\numberFormat($prices['rock'], 0),
					'iron' => util\math\numberFormat($prices['iron'], 0),
					'paper' => util\math\numberFormat($prices['paper'], 0),
					'koku' => util\math\numberFormat($prices['koku'], 0),
				);

				$res_values = array(
					'res_food' => $resources['food'],
					'res_wood' => $resources['wood'],
					'res_rock' => $resources['rock'],
					'res_iron' => $resources['iron'],
					'res_paper' => $resources['paper'],
					'res_koku' => $resources['koku'],
				);
				if (is_array($prices))
					$res_values += $prices;

				$canBuild = bl\buildings\resourceCheck($res_values);
				if ($building['kind'] == 19 && $building['lvl'] > 0 && $building['lvl'] % 10 == 0 && $building['lvl'] / 10 >= $building['ulvl'])
					$canBuild = 0;

				$building['canBuild'] = $canBuild;
				$building['freeBuildPosition'] = bl\buildings\checkFreeBuildPosition($city, $building['kind']);
				$building['buildTime'] = bl\general\formatTime($time, 'd h:m:s');
			}
			else
				$building['notYetBuildable'] = true;

			if ($has_upgrades)
			{
				$prices_upgr = $upgrade_prices = bl\buildings\upgradePrices($building['kind'], $building['ulvl']);

				if (is_array($upgrade_prices))
				{
					//moved here, because there is no upgrade posibility, if the building has reached it's maximum upgrade
					$building['hasUpgrades'] = $has_upgrades;

					foreach ($upgrade_prices as &$upgrade_price)
						$upgrade_price = util\math\numberFormat($upgrade_price, 0);

					$building['upgradePrices'] = $upgrade_prices;
					$res_values = array(
						'res_food' => $resources['food'],
						'res_wood' => $resources['wood'],
						'res_rock' => $resources['rock'],
						'res_iron' => $resources['iron'],
						'res_paper' => $resources['paper'],
						'res_koku' => $resources['koku'],
					);

					if (is_array($prices_upgr))
						$res_values += $prices_upgr;

					$canUpgrade = false;
					if (bl\buildings\resourceCheck($res_values) && bl\buildings\checkUpgradeable($building['kind'], $city))
						$canUpgrade = true;

					$building['canUpgrade'] = $canUpgrade;
					$building['upgradeTime'] = bl\general\formatTime($u_time, 'd h:m:s');
				}
			}

			if ($building['kind'] == 19)
			{
				$building['showDefenseBuildings'] = true;
				$defenseBuildings = bl\buildings\getDefense($city);
				$building['upgradeLevel'] = $building['ulvl'];

				if ($building['ulvl'] >= 2)
				{
					$building['defense'] = array();
					for ($i = 0; $i < 3; $i++)
					{
						$defense = $defenseBuildings[$i];

						if (!is_array($defense))
						{
							$defense = array(
								'position' => ($i == 0 ? 23 : ($i == 1 ? 24 : 25)),
							);
						}

						$building['defense'][] = array(
							'image' => \bl\buildings\getBuildPlacePicture($city, $defense, !!$defense['lvl']),
						) + $defense;
					}
				}
				else
					$building['noBuild'] = $lang['noBuild'];
			}

			echo \bl\general\jsonEncode(array(
				'type' => 'building',
				'data' => $building,
			));
		}
		else
		{
			$resources = \bl\resource\newResources($_GET['city']);
			$smarty_buildings = array();

			if ($_GET['buildPlace'] < 23)
				$buildables = bl\buildings\getNotBuilt($city);
			else
				$buildables = bl\buildings\getNotBuilt($city, 1);

			if ($buildables)
			{
				foreach ($buildables as $buildable)
				{
					if (bl\buildings\checkBuildable($_SESSION['user']->getUID(), $buildable['kind'], $cityExp[0], $cityExp[1]))
					{
						$has_upgrades = bl\buildings\getUpgradeable($buildable['kind']);
						$has_harbour = bl\buildings\getHarbour($cityExp[0], $cityExp[1]);

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
							'res_food' => $resources['food'],
							'res_wood' => $resources['wood'],
							'res_rock' => $resources['rock'],
							'res_iron' => $resources['iron'],
							'res_paper' => $resources['paper'],
							'res_koku' => $resources['koku'],
						);

						if (is_array($prices))
							$res_values += $prices;

						$description = $lang['descr'][$buildable['kind']];
						if ($buildable['kind'] == 6)
							$description = $lang['descr'][$buildable['kind']][$has_harbour];

						$smarty_buildings[] = array(
							'kind' => $buildable['kind'],
							'buildingName' => $lang['building_names'][$buildable['kind']][$buildable['ulvl']],
							'buildingDescription' => $description,
							'buildingPicture' => bl\buildings\getBuildPlacePicture($city, $buildable, 1),
							'buildingResources' => $prices_formatted,
							'buildTime' => bl\general\formatTime($time, 'd h:m:s'),
							'canBuild' => bl\buildings\resourceCheck($res_values),
							'freeBuildPosition' => bl\buildings\checkFreeBuildPosition($city, $buildable['kind']),
						);
					}
				}
			}

			echo \bl\general\jsonEncode(array(
				'type' => 'buildingsList',
				'data' => $smarty_buildings,
				'newBuilding' => $lang['newBuilding'],
			));
		}
	}
}