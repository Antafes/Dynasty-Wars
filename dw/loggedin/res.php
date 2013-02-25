<?php
include('loggedin/header.php');
include_once('lib/bl/unit.inc.php');

bl\general\loadLanguageFile('res');

$cityexp = explode(':', $city);
$x = $cityexp[0];
$y = $cityexp[1];
if ($_POST['changeprod'])
{
	$erg1 = bl\resource\changePaperPercent($_POST['prodfactor'], $x, $y);
	if ($erg1)
		$smarty->assign('textRateChanged', $lang['prodchanged']);
}
$smarty->assign('textLevel', $lang['lvl']);
$smarty->assign('heading', $lang['ressources']);
$smarty->assign('buildingLevels', array(
	'paddy' => $paddy,
	'lumberjack' => $lumberjack,
	'quarry' => $quarry,
	'ironmine' => $ironmine,
	'papermill' => $papermill,
	'tradePostHarbour' => $tradepost + $harbour,
));
$smarty->assign('ressourceList', array(
	'basic' => array(
		'title' => $lang['basicincome'],
		'food' => bl\resource\basicIncome(1, $city),
		'wood' => bl\resource\basicIncome(2, $city),
		'rock' => bl\resource\basicIncome(3, $city),
		'iron' => bl\resource\basicIncome(4, $city),
		'paper' => bl\resource\basicIncome(5, $city),
		'koku' => bl\resource\basicIncome(6, $city),
	),
	'buildings' => array(
		'title' => $lang['buildingincome'],
		'food' => bl\resource\buildingsIncome(1, bl\resource\basicIncome(1, $city), $paddy, $city),
		'wood' => bl\resource\buildingsIncome(2, bl\resource\basicIncome(2, $city), $lumberjack, $city),
		'rock' => bl\resource\buildingsIncome(3, bl\resource\basicIncome(3, $city), $quarry, $city),
		'iron' => bl\resource\buildingsIncome(4, bl\resource\basicIncome(4, $city), $ironmine, $city),
		'paper' => bl\resource\buildingsIncome(5, bl\resource\basicIncome(5, $city), $papermill, $city),
		'koku' => bl\resource\buildingsIncome(6, bl\resource\basicIncome(6, $city), $tradepost + $harbour, $city),
	),
	'upgrades' => array(
		'title' => $lang['upgrades'],
		'food' => bl\resource\incomeUpgrades(1, $city),
		'wood' => bl\resource\incomeUpgrades(2, $city),
		'rock' => bl\resource\incomeUpgrades(3, $city),
		'iron' => bl\resource\incomeUpgrades(4, $city),
		'paper' => bl\resource\incomeUpgrades(5, $city),
		'koku' => bl\resource\incomeUpgrades(6, $city),
	),
	'costs' => array(
		'title' => $lang['costs'],
		'food' => round(bl\unit\calcTotalFoodCost($_SESSION['user']->getUID())),
		'wood' => round(bl\resource\woodCosts($x, $y)),
		'rock' => 0,
		'iron' => 0,
		'paper' => 0,
		'koku' => round(bl\unit\calcTotalKokuCost($_SESSION['user']->getUID())),
	),
	'incomePerHour' => array(
		'title' => $lang['income/h'],
		'food' => bl\resource\income(1, 'h', $paddy, $city),
		'wood' => bl\resource\income(2, 'h', $lumberjack, $city),
		'rock' => bl\resource\income(3, 'h', $quarry, $city),
		'iron' => bl\resource\income(4, 'h', $ironmine, $city),
		'paper' => bl\resource\income(5, 'h', $papermill, $city),
		'koku' => bl\resource\income(6, 'h', $tradepost + $harbour, $city),
	),
	'incomePerDay' => array(
		'title' => $lang['income/d'],
		'food' => bl\resource\income(1, 'd', $paddy, $city),
		'wood' => bl\resource\income(2, 'd', $lumberjack, $city),
		'rock' => bl\resource\income(3, 'd', $quarry, $city),
		'iron' => bl\resource\income(4, 'd', $ironmine, $city),
		'paper' => bl\resource\income(5, 'd', $papermill, $city),
		'koku' => bl\resource\income(6, 'd', $tradepost + $harbour, $city),
	),
));
$smarty->assign('textPaperProduction', $lang['paperprod']);
$smarty->assign('textWoodCosts', $lang['woodcosts']);
$smarty->assign('woodCosts', util\math\numberFormat(bl\resource\woodCosts($x, $y), 0));
$smarty->assign('textProductionFactor', $lang['prodfactor']);
$smarty->assign('productionFactor', bl\resource\getPaperPercent($x, $y));
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