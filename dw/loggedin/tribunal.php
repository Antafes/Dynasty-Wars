<?php
include('lib/bl/tribunal.inc.php');
include('loggedin/header.php');

lib_bl_general_loadLanguageFile('tribunal');
$smarty->assign('lang', $lang);

$parser = new wikiparser;

if ($_GET['sub'] == 'hearings' || !$_GET['sub'])
{
	if (!$_GET['id'])
	{
		$hearings = lib_bl_tribunal_getAllHearings();
		var_dump($hearings);
		$smarty->assign('hearings', ($hearings ? $hearings : array()));
		$smarty->assign('hearingsCount', count($hearings));
	}
	elseif ($_GET['id'])
	{
		if ($_GET['action'])
		{
			if ($_GET['action'] == 'recall')
			{
				lib_bl_tribunal_recallHearing($_GET['id'], $_SESSION['user']->getUID());
				if ($_SESSION['user']->getGameRank() >= 1)
				{
					$h = lib_bl_tribunal_getHearing($_GET['id']);
					lib_bl_log_saveLog(25, $_SESSION['user']->getUID(), $h['suitor'], $_GET['id']);
				}
				lib_bl_general_redirect('index.php?chose=tribunal&sub=hearings');
			}
		}

		$smarty->assign('hearing', lib_bl_tribunal_getHearing($_GET['id']));
		$smarty->assign('commentReadyScript', lib_util_html_createReadyScript('showCommentList("comments", '.$_GET['id'].')'));
	}
}
elseif ($_GET['sub'] == 'newhearing')
{
	if ($_POST['nh_sub'])
	{
		$errors = lib_bl_tribunal_insertHearing($_SESSION['user']->getUID(), $_POST['accused'], $_POST['causes'], $_POST['cause_description'], $_POST['arguments']);

		if ($errors == false)
			lib_bl_general_redirect('index.php?chose=tribunal&sub=newhearing&successful=1');
		else
			$smarty->assign('errors', $errors);
	}

	$smarty->assign('causes', lib_bl_tribunal_getAllCauses($lang['lang']));
	$smarty->assign('messages', lib_bl_tribunal_getAllMessages($_SESSION['user']->getUID()));
}
elseif($_GET['sub'] == 'rules')
{
	if (($_SESSION['user']->getGameRank() == 2 || $_SESSION['user']->getGameRank() == 3) && !$own_uid)
	{
		$languages = lib_bl_general_getLanguages(false);
		$langArray = array();
		foreach ($languages as $language)
			$langArray[$language['language']] = $language['name'];
		$smarty->assign('languages', $langArray);
	}

	if (!$_GET['rules_sub'] || $_GET['rules_sub'] == 'show')
	{
		$rules = lib_bl_tribunal_getAllRules($_GET['languages']);

		$page = $_GET['page'];
		if (!$page)
			$page = 1;

		$pagelinks = '';
		if (count($rules) > 5)
		{
			$pages = ceil(count($rules) / 5);
			$pagelinks = lib_bl_general_createPageLinks($_GET['chose'], $pages, 'sub=rules&rules_sub=show&languages='.$_GET['languages']);
		}

		$smarty->assign('rules', $rules);
		$smarty->assign('page', $page);
		$smarty->assign('pageLinks', $pagelinks);
	}
	elseif ($_GET['rules_sub'] == 'change')
	{
		if ($_GET['delete'])
		{
			switch ($_GET['delete'])
			{
				case 'rule':
					lib_bl_tribunal_deleteRule($_GET['id']);
					break;
				case 'clause':
					lib_bl_tribunal_deleteClause($_GET['id']);
					break;
			}
		}

		if ($_POST['new_rule_sub'])
		{
			$error = array(
				'paragraph' => 0,
				'title' => 0,
				'clause1' => 0,
			);

			if (!$_POST['paragraph'])
				$error['paragraph'] = 1;

			if (!$_POST['title'])
				$error['title'] = 1;

			if (!$_POST['description'][0])
				$error['clause1'] = 1;

			if ($error['paragraph'] != 1 && $error['title'] != 1 && $error['clause1'] != 1)
			{
				lib_bl_tribunal_insertRule(array(
					'paragraph' => $_POST['paragraph'],
					'title' => $_POST['title'],
					'clauses' => $_POST['description'],
					'language' => ($_GET['languages'] ? $_GET['languages'] : $lang['lang']),
				));
			}
		}

		$rules = lib_bl_tribunal_getAllRules($_GET['languages']);

		$page = $_GET['page'];
		if (!$page)
			$page = 1;

		$pagelinks = '';
		if (count($rules) > 10)
		{
			$pages = ceil(count($rules) / 10);
			$pagelinks = lib_bl_general_createPageLinks($_GET['chose'], $pages, 'sub=rules&rules_sub=show&languages='.$_GET['languages']);
		}

		$smarty->assign('rules', $rules);
		$smarty->assign('page', $page);
		$smarty->assign('pageLinks', $pagelinks);
	}
}
include('loggedin/footer.php');

$smarty->display('tribunal.tpl');