<?php
include('loggedin/header.php');

lib_bl_general_loadLanguageFile('main', 'acp');
$smarty->assign('lang', $lang);

if ($_SESSION['user']->getGameRank())
{
	$users = util\mysql\query('SELECT COUNT(uid) FROM dw_user');
	$clans = util\mysql\query('SELECT COUNT(cid) FROM dw_clan');

	$smarty->assign('userCount', $users);
	$smarty->assign('clanCount', $clans);

	switch ($_GET['sub'])
	{
		case 'userlist':
		default:
			include('acp/acp_userlist.php');
			break;
		case 'clanlist':
			include('acp/acp_clanlist.php');
			break;
		case 'log':
			include('acp/acp_log.php');
			break;
		case 'gameoptions':
			include('acp/acp_gameoptions.php');
			break;
		case 'news':
			include('acp/acp_news_write.php');
			break;
	}
}

include('loggedin/footer.php');

$smarty->display('acp.tpl');