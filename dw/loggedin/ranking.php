<?php
include('loggedin/header.php');
include('lib/bl/ranking.inc.php');

bl\general\loadLanguageFile('ranking');
$smarty->assign('lang', $lang);

$rank_tab = $_GET['rank_tab'];

$smarty->assign('heading', $lang['ranking']);

$smarty->assign('link_player', '<a href="index.php?chose=ranking&amp;rank_tab=1">'.$lang['player'].'</a>');
$smarty->assign('link_clans', '<a href="index.php?chose=ranking&amp;rank_tab=2">'.$lang['clans'].'</a>');

if (($rank_tab == 1) || (!$rank_tab))
{
	$rankerg1 = bl\ranking\getUserRanking();
	$smarty->assign('rank_list',$rankerg1);
}
elseif ($rank_tab == 2)
{
	$rankerg1 = dal\ranking\getClanRanking();
	$smarty->assign('rank_list',$rankerg1);
}

include('loggedin/footer.php');

$smarty->display('ranking.tpl');