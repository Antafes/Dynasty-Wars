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
	$smarty->assign('user_info_since',htmlentities($lang['since']));
	$smarty->assign('user_info_position',htmlentities($lang['position']));
	$smarty->assign('user_info_points',htmlentities($lang['points']));
	$smarty->assign('user_info_clan',htmlentities($lang['clan']));
	$smarty->assign('rank_list',$rankerg1);
}
elseif ($rank_tab == 2)
{
	$rankerg1 = lib_dal_ranking_getClanRanking();
	$smarty->assign('table_rank',htmlentities($lang['rank']));
	$smarty->assign('table_player',htmlentities($lang['player']));
	$smarty->assign('table_units',htmlentities($lang['units']));
	$smarty->assign('table_buildings',htmlentities($lang['buildings']));
	$smarty->assign('table_total',htmlentities($lang['total']));
	$smarty->assign('rank_list',$rankerg1);
}

include('loggedin/footer.php');

$smarty->display('ranking.tpl');