<?php
require_once('lib/bl/news.inc.php');

bl\general\loadLanguageFile('news', 'acp');
$smarty->assign('lang', $lang);

if (!$_GET['nmode'] || $_GET['nmode'] == 1)
{
	if (!$_GET['nid'])
		$smarty->assign('newsEntries', bl\news\getAllEntries());
	else
	{
		if ($_POST['title'] && $_POST['news'])
		{
			if (bl\news\update($_GET['nid'], $_POST['title'], $_POST['news'], $_SESSION['user']))
			{
				$smarty->assign('message', $lang['updatedEntry']);
				bl\log\saveLog(22, $_SESSION['user']->getUID(), "", $_POST['title']);
			}
		}

		$smarty->assign('newsEntry', bl\news\getEntry($_GET['nid']));
	}
}
elseif ($_GET['nmode'] == 2)
{
	if ($_POST['title'] && $_POST['news'])
	{
		if (bl\news\save($_POST['title'], $_POST['news'], $_SESSION['user']))
		{
			$smarty->assign('message', $lang['insertedEntry']);
			bl\log\saveLog(21, $_SESSION['user']->getUID(), '', $_POST['title']);
		}
	}
}

$smarty->assign('acpContent', $smarty->fetch('../acp/news.tpl'));