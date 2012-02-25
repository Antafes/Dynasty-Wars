<?php
ob_start();
//requesting of get and post variables
if ($_POST['citychange'])
{
	if ($_SESSION['lid'])
	{
		$_SESSION["city"] = $_POST['citychange'];
		bl\general\redirect(util\html\createLink(array('chose' => $_GET['chose']), true));
	}
	elseif ($_COOKIE['lid'])
	{
		setcookie("city", $_POST['citychange'], time()-604800, "", ".dynasty-wars.de");
		bl\general\redirect(util\html\createLink(array('chose' => $_GET['chose']), true));
	}
}
if ($_SESSION["lid"]) {
	$city = $_SESSION['city'];
	$lang['lang'] = $_SESSION['language'];
} elseif ($_COOKIE['lid']) {
	$city = $_COOKIE['city'];
	$lang['lang'] = $_COOKIE['language'];
}
//selection of the user informations
$nick = bl\general\uid2nick($_SESSION['user']->getUID());
$admin = bl\general\getGameRank($_SESSION['user']->getUID());
//language
if (!$lang['lang'])
	$lang['lang'] = bl\general\getLanguage($_SESSION['user']->getUID());

bl\general\loadLanguageFile('main');
bl\general\loadLanguageFile('general', null);

$smarty->assign('userInfos', array(
	'city' => $city,
	'lang' => $lang['lang'],
	'nick' => $_SESSION['user']->getNick(),
	'gameRank' => $_SESSION['user']->getGameRank(),
));

include('lib/bl/resource.inc.php');
$gameOptions = util\mysql\query('SELECT board, adminmail, version FROM dw_game');

if ($_GET['chose'] == 'buildings')
{
	include('lib/bl/buildings.inc.php');
//check for running build
	if ($_GET['chose'] == 'buildings' && !$_GET['buildplace'])
		$is_building = bl\buildings\checkBuild($_SESSION['user']->getUID(), $city);
}
elseif ($_GET['chose'] == 'units')
{
	include('lib/bl/unit.inc.php');
	$train_check = bl\unit\train\checkTraining($_SESSION['user']->getUID(), $city);
}
//new messages
$sql = '
	SELECT COUNT(msgid) FROM dw_message
	WHERE uid_recipient = '.mysql_real_escape_string($_SESSION['user']->getUID()).'
		AND unread
';
$new_msg = util\mysql\query($sql);
if (!$new_msg)
	$new_msg = 0;

if ($_GET['chose'] == 'market')
	$smarty->assign('marketCSS', '<link rel="stylesheet" type="text/css" href="css/market.css" />');

if ($_GET['chose'] == 'map')
{
	$mapJS = '<script language="javascript" type="text/javascript" src="lib/js/map.js"></script>';
	$smarty->assign('mapJS', $mapJS);
}
elseif ($_GET['chose'] == 'buildings' || $_GET['chose'] == 'units')
{
	if ($_GET['chose'] == 'units')
		$smarty->assign('unitsJS', '<script language="javascript" type="text/javascript" src="lib/js/unit.js"></script>');

	if (($_GET['chose'] == 'units' && (!$_GET['sub'] || $_GET['sub'] == 'build')) || $_GET['chose'] == 'buildings')
		$smarty->assign('timerJS', '<script language="javascript" type="text/javascript" src="lib/js/timer.js"></script>');
}
elseif ($_GET['chose'] == 'tribunal')
	$smarty->assign('tribunalAjaxJS', '<script language="javascript" type="text/javascript" src="lib/js/tribunal_ajax.js"></script>');

//actualising the ressources
$res_buildings = bl\resource\getResourceBuildings($city);
if ($res_buildings)
{
	foreach ($res_buildings as $res_building)
	{
		switch($res_building['kind']){
			case 1:
				$ricefield = $res_building['lvl'];
				break;
			case 2:
				$lumberjack = $res_building['lvl'];
				break;
			case 3:
				$quarry = $res_building['lvl'];
				break;
			case 4:
				$ironmine = $res_building['lvl'];
				break;
			case 5:
				$papermill = $res_building['lvl'];
				break;
			case 6:
				$tradepost = $res_building['lvl'];
				break;
		}
	}
	if (!$ricefield)
		$ricefield = 0;
	if (!$lumberjack)
		$lumberjack = 0;
	if (!$quarry)
		$quarry = 0;
	if (!$ironmine)
		$ironmine = 0;
	if (!$papermill)
		$papermill = 0;
	if (!$tradepost)
		$tradepost = 0;
}
$max_storage = bl\general\getMaxStorage($city);
$ressources = bl\resource\newResources($ricefield, $lumberjack, $quarry, $ironmine, $papermill, $tradepost, $city);
$food = $ressources['food'];
$wood = $ressources['wood'];
$rock = $ressources['rock'];
$iron = $ressources['iron'];
$paper = $ressources['paper'];
$koku = $ressources['koku'];
$troops_moving = bl\troops\checkMoving($_SESSION['user']->getUID());
$tids = bl\troops\checkTroops($_SESSION['user']->getUID());
$bodyonload = sprintf('r(%d, %d, %d, %d, %d, %d, %f, %f, %f, %f, %f, %f, %f);'."\n", $food, $wood, $rock, $iron, $paper, $koku,
	bl\resource\income(1, 's', $ricefield, $city), bl\resource\income(2, 's', $lumberjack, $city), bl\resource\income(3, 's', $quarry, $city),
	bl\resource\income(4, 's', $ironmine, $city), bl\resource\income(5, 's', $papermill, $city), bl\resource\income(6, 's', $tradepost, $city),
	$max_storage
);
if ($is_building)
	foreach ($is_building as $build)
		$bodyonload .= sprintf('timer(\'%s\', \'%s\', \'b%u\');'."\n", $build['endtime']->format('F d, Y H:i:s'), date('F d, Y H:i:s'), $build['bid']);

if ($_GET['chose'] == 'units' && $train_check['ok'] && $_GET['sub'] != 'move')
	$bodyonload .= sprintf('timer(\'%s\', \'%s\', %u);'."\n", $train_check['endtime']->format('F d, Y H:i:s'), date('F d, Y H:i:s'), $train_check['kind']);
elseif ($_GET['chose'] == 'units' && $_GET['sub'] == 'move' && $troops_moving && !$_GET['mode'])
{
	foreach ($tids as $tid)
	{
		$troop = bl\troops\checkTroop($tid);
		$bodyonload .= sprintf('timer(%u, %u);'."\n", $troop['end_datetime']->format('F d, Y H:i:s'), date('F d, Y H:i:s'), $tid);
	}
}

$smarty->assign('readyJS', util\html\createReadyScript($bodyonload));

//selection of the map position
$cities = util\mysql\query('
	SELECT
		CONCAT(map_x, ":", map_y) coords,
		city
	FROM dw_map
	WHERE uid = '.mysql_real_escape_string($_SESSION['user']->getUID()).'
	ORDER BY `city`
', true);
$smarty->assign('cities', $cities);
foreach ($cities as &$cities_part)
	$cities_part['city'] = htmlentities ($cities_part['city']);

$menuEntries = bl\gameOptions\getAllMenuEntries();
$block_entry = $menuEntries[count($menuEntries)-3];
$block = 1;

$menu = '';
foreach ($menuEntries as $menu_entry)
{
	if (($menu_entry['menu_name'] == 'home' || $menu_entry['menu_name'] == 'logout' || $menu_entry['menu_name'] == 'acp'))
	{
		if (($menu_entry['menu_name'] == 'acp' && ($_SESSION['user']->getGameRank() == 1 || $_SESSION['user']->getGameRank() == 2) && !$own_uid) || $menu_entry['menu_name'] != 'acp')
		{
			$menu .= '<div class="menu3';
			if ($menu_entry['menu_name'] == 'home')
				$menu .= ' first_menu';
			$menu .= '">';
			if ($menu_entry['active'] == 1)
				$menu .= '<a href="index.php?chose='.$menu_entry['menu_name'].'" class="a2">';
			$menu .= htmlentities($lang[$menu_entry['menu_name']]);
			if ($menu_entry['active'] == 1)
				$menu .= '</a>';
			$menu .= '</div>';
		}
	}
	else
	{
		if ($block == 1)
		{
			if ($block_entry['sort']-$menu_entry['sort'] > 4)
				$max_block = 4;
			else
				$max_block = 3;
			$menu .= '<div class="menu';
			if ($block_entry['sort']-$menu_entry['sort'] > 4)
				$menu .= '1';
			else
				$menu .= '2';
			$menu .= '">';
		}
		$link = 'index.php?chose='.$menu_entry['menu_name'];
		if ($menu_entry['menu_name'] == 'clan')
			$link .= '&amp;cid='.$_SESSION['user']->getCID();
		elseif ($menu_entry['menu_name'] == 'board')
			$link = 'http://'.$gameOptions['board'].'" target="_blank';

		if ($menu_entry['active'] == 1)
			$menu .= '<a href="'.$link.'" class="a2">';
		$menu .= htmlentities(($lang[$menu_entry['menu_name']] ? $lang[$menu_entry['menu_name']] : $menu_entry['menu_name']));
		if ($menu_entry['active'] == 1)
			$menu .= '</a>';

		$block++;
		if ($block > $max_block)
		{
			$block = 1;
			$menu .= '</div>';
		}
		else
			$menu .= '<br />';
	}
}
$smarty->assign('menu', $menu);
if ($own_uid || $_SESSION['user']->getGameRank())
{
	$special_line = '<div class="add_info">';
	if ($own_uid)
	{
		$own_nick = bl\general\uid2nick($own_uid);
		$special_line .= htmlentities($own_nick).' eingeloggt als '.htmlentities($nick).'. <a href="index.php?chose=acp&amp;sub=userlist&amp;change=back">Zur&uuml;ck wechseln.</a>';
	}
	if ($_SESSION['user']->getGameRank() >= 1)
		$special_line .= htmlentities($lang['gameRank'][$_SESSION['user']->getGameRank()]);
	else
		$special_line .= '&nbsp;';
	$special_line .= '</div>';
	$smarty->assign('special_line', $special_line);
}

$smarty->assign('ressources', array(
	'food' => $lang['food'],
	'wood' => $lang['wood'],
	'rock' => $lang['rock'],
	'iron' => $lang['iron'],
	'paper' => $lang['paper'],
	'koku' => $lang['koku'],
	'storage' => $lang['storage'],
	'food_escaped' => htmlentities($lang['food']),
	'wood_escaped' => htmlentities($lang['wood']),
	'rock_escaped' => htmlentities($lang['rock']),
	'iron_escaped' => htmlentities($lang['iron']),
	'paper_escaped' => htmlentities($lang['paper']),
	'koku_escaped' => htmlentities($lang['koku']),
	'storage_escaped' => htmlentities($lang['storage']),
));
$smarty->assign('storage', $max_storage);