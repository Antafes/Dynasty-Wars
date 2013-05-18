<?php
include('loggedin/header.php');
include('lib/bl/buildings.inc.php');

bl\general\loadLanguageFile('building');
\util\html\load_js('timer');
\util\html\load_js('buildings');

$is_building = bl\buildings\checkBuild($_SESSION['user']->getUID(), $city);

$readyScript = '';
if ($is_building)
	foreach ($is_building as $build)
		$readyScript .= sprintf('timer(\'%s\', \'%s\', \'b%u\');'."\n", $build['endtime']->format('F d, Y H:i:s'), date('F d, Y H:i:s'), $build['bid']);

\util\html\load_js_ready_script($readyScript);

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
{
	foreach ($is_building as $build)
	{
		$build_items[$build['kind']] = array(
			'name' => $lang['building_names'][$build['kind']][$build['ulvl']],
			'bid' => $build['bid'],
		);
	}
}

$smarty->assign('buildItems', $build_items);

include('loggedin/footer.php');

$smarty->assign('lang', $lang);

$smarty->display('buildings.tpl');