<?php
include('loggedin/header.php');
include('lib/bl/ranking.inc.php');

lib_bl_general_loadLanguageFile('ranking');
$smarty->assign('lang', $lang);

$rank_tab = $_GET['rank_tab'];

$smarty->assign('heading', htmlentities($lang['ranking']));

$smarty->assign('link_player', '<a href="index.php?chose=ranking&amp;rank_tab=1">'.htmlentities($lang['player']).'</a>');
$smarty->assign('link_clans', '<a href="index.php?chose=ranking&amp;rank_tab=2">'.htmlentities($lang['clans']).'</a>');

if (($rank_tab == 1) || (!$rank_tab))
{
	$rankerg1 = lib_bl_ranking_getUserRanking();
	$smarty->assign('rank_list',$rankerg1);
}
elseif ($rank_tab == 2)
{
	$rankerg1 = lib_dal_ranking_getClanRanking();
	$smarty->assign('rank_list',$rankerg1);
}

include('loggedin/footer.php');

$smarty->display('ranking.tpl');