<?php
include('loggedin/header.php');
include_once('lib/bl/unit.inc.php');

lib_bl_general_loadLanguageFile('res');

$cityexp = explode(':', $city);
$x = $cityexp[0];
$y = $cityexp[1];
if ($_POST['changeprod'])
{
	$erg1 = lib_bl_resource_changePaperPercent($_POST['prodfactor'], $x, $y);
	if ($erg1)
		$smarty->assign('textRateChanged', $lang['prodchanged']);
}
$smarty->assign('textLevel', $lang['lvl']);
$smarty->assign('heading', $lang['ressources']);
$smarty->assign('buildingLevels', array(
	'ricefield' => $ricefield,
	'lumberjack' => $lumberjack,
	'quarry' => $quarry,
	'ironmine' => $ironmine,
	'papermill' => $papermill,
	'tradePostHarbour' => $tradepost + $harbour,
));
$smarty->assign('ressourceList', array(
	'basic' => array(
		'title' => $lang['basicincome'],
		'food' => lib_bl_resource_basicIncome(1, $city),
		'wood' => lib_bl_resource_basicIncome(2, $city),
		'rock' => lib_bl_resource_basicIncome(3, $city),
		'iron' => lib_bl_resource_basicIncome(4, $city),
		'paper' => lib_bl_resource_basicIncome(5, $city),
		'koku' => lib_bl_resource_basicIncome(6, $city),
	),
	'buildings' => array(
		'title' => $lang['buildingincome'],
		'food' => lib_bl_resource_bIncome(1, lib_bl_resource_basicIncome(1, $city), $ricefield, $city),
		'wood' => lib_bl_resource_bIncome(2, lib_bl_resource_basicIncome(2, $city), $lumberjack, $city),
		'rock' => lib_bl_resource_bIncome(3, lib_bl_resource_basicIncome(3, $city), $quarry, $city),
		'iron' => lib_bl_resource_bIncome(4, lib_bl_resource_basicIncome(4, $city), $ironmine, $city),
		'paper' => lib_bl_resource_bIncome(5, lib_bl_resource_basicIncome(5, $city), $papermill, $city),
		'koku' => lib_bl_resource_bIncome(6, lib_bl_resource_basicIncome(6, $city), $tradepost + $harbour, $city),
	),
	'upgrades' => array(
		'title' => $lang['upgrades'],
		'food' => lib_bl_resource_incomeUpgrades(1, $city),
		'wood' => lib_bl_resource_incomeUpgrades(2, $city),
		'rock' => lib_bl_resource_incomeUpgrades(3, $city),
		'iron' => lib_bl_resource_incomeUpgrades(4, $city),
		'paper' => lib_bl_resource_incomeUpgrades(5, $city),
		'koku' => lib_bl_resource_incomeUpgrades(6, $city),
	),
	'costs' => array(
		'title' => $lang['costs'],
		'food' => round(lib_bl_unit_calcTotalFoodCost($_SESSION['user']->getUID())),
		'wood' => round(lib_bl_resource_woodCosts($x, $y)),
		'rock' => 0,
		'iron' => 0,
		'paper' => 0,
		'koku' => round(lib_bl_unit_calcTotalKokuCost($_SESSION['user']->getUID())),
	),
	'incomePerHour' => array(
		'title' => $lang['income/h'],
		'food' => lib_bl_resource_income(1, 'h', $ricefield, $city),
		'wood' => lib_bl_resource_income(2, 'h', $lumberjack, $city),
		'rock' => lib_bl_resource_income(3, 'h', $quarry, $city),
		'iron' => lib_bl_resource_income(4, 'h', $ironmine, $city),
		'paper' => lib_bl_resource_income(5, 'h', $papermill, $city),
		'koku' => lib_bl_resource_income(6, 'h', $tradepost + $harbour, $city),
	),
	'incomePerDay' => array(
		'title' => $lang['income/d'],
		'food' => lib_bl_resource_income(1, 'd', $ricefield, $city),
		'wood' => lib_bl_resource_income(2, 'd', $lumberjack, $city),
		'rock' => lib_bl_resource_income(3, 'd', $quarry, $city),
		'iron' => lib_bl_resource_income(4, 'd', $ironmine, $city),
		'paper' => lib_bl_resource_income(5, 'd', $papermill, $city),
		'koku' => lib_bl_resource_income(6, 'd', $tradepost + $harbour, $city),
	),
));
$smarty->assign('textPaperProduction', $lang['paperprod']);
$smarty->assign('textWoodCosts', $lang['woodcosts']);
$smarty->assign('woodCosts', util\math\numberFormat(lib_bl_resource_woodCosts($x, $y), 0));
$smarty->assign('textProductionFactor', $lang['prodfactor']);
$smarty->assign('productionFactor', lib_bl_resource_getPaperPercent($x, $y));
$smarty->assign('productionFactorList', array(
	0 => '0%',
	10 => '10%',
	20 => '20%',
	30 => '30%',
	40 => '40%',
	50 => '50%',
	60 => '60%',
	70 => '70%',
	80 => '80%',
	90 => '90%',
	100 => '100%',
));
$smarty->assign('textChange', $lang['change']);
include('loggedin/footer.php');

$smarty->display('resources.tpl');
?>