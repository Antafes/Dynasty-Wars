<?php
require_once('lib/bl/news.inc.php');

lib_bl_general_loadLanguageFile('news', 'acp');
$smarty->assign('lang', $lang);

if (!$_GET['nmode'] || $_GET['nmode'] == 1)
{
	if (!$_GET['nid'])
		$smarty->assign('newsEntries', lib_bl_news_getAllEntries());
	else
	{
		if ($_POST['title'] && $_POST['news'])
		{
			if (lib_bl_news_update($_GET['nid'], $_POST['title'], $_POST['news'], $_SESSION['user']))
			{
				$smarty->assign('message', $lang['updatedEntry']);
				lib_bl_log_saveLog(22, $_SESSION['user']->getUID(), "", $_POST['title']);
			}
		}

		$smarty->assign('newsEntry', lib_bl_news_getEntry($_GET['nid']));
	}
}
elseif ($_GET['nmode'] == 2)
{
	if ($_POST['title'] && $_POST['news'])
	{
		if (lib_bl_news_save($_POST['title'], $_POST['news'], $_SESSION['user']))
		{
			$smarty->assign('message', $lang['insertedEntry']);
			lib_bl_log_saveLog(21, $_SESSION['user']->getUID(), '', $_POST['title']);
		}
	}
}

$smarty->assign('acpContent', $smarty->fetch('../acp/news.tpl'));