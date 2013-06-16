<?php
include('lib/bl/tribunal.inc.php');
include('loggedin/header.php');

bl\general\loadLanguageFile('tribunal');
\util\html\load_js('tribunal_ajax');

$smarty->assign('lang', $lang);

$parser = new bl\wikiParser\WikiParser;

if ($_GET['sub'] == 'hearings' || !$_GET['sub'])
{
	if (!$_GET['id'])
	{
		$hearings = bl\tribunal\getAllHearings();
//		var_dump($hearings);
		$smarty->assign('hearings', ($hearings ? $hearings : array()));
		$smarty->assign('hearingsCount', count($hearings));
	}
	elseif ($_GET['id'])
	{
		if ($_GET['action'])
		{
			if ($_GET['action'] == 'recall')
			{
				bl\tribunal\recallHearing($_GET['id'], $_SESSION['user']->getUID());
				if ($_SESSION['user']->getGameRank() >= 1)
				{
					$h = bl\tribunal\getHearing($_GET['id']);
					bl\log\saveLog(25, $_SESSION['user']->getUID(), $h['suitor'], $_GET['id']);
				}
				bl\general\redirect('index.php?chose=tribunal&sub=hearings');
			}
		}

		$smarty->assign('hearing', bl\tribunal\getHearing($_GET['id']));
		$smarty->assign('commentReadyScript', util\html\createReadyScript('showCommentList("comments", '.$_GET['id'].')'));
	}
}
elseif ($_GET['sub'] == 'newhearing')
{
	if ($_POST['nh_sub'])
	{
		$errors = bl\tribunal\insertHearing($_SESSION['user']->getUID(), $_POST['accused'], $_POST['causes'], $_POST['cause_description'], $_POST['arguments']);

		if ($errors == false)
			bl\general\redirect('index.php?chose=tribunal&sub=newhearing&successful=1');
		else
			$smarty->assign('errors', $errors);
	}

	$smarty->assign('causes', bl\tribunal\getAllCauses($lang['lang']));
	$smarty->assign('messages', bl\tribunal\getAllMessages($_SESSION['user']->getUID()));
}
elseif($_GET['sub'] == 'rules')
{
	if (($_SESSION['user']->getGameRank() == 2 || $_SESSION['user']->getGameRank() == 3) && !$own_uid)
	{
		$languages = bl\general\getLanguages(false);
		$langArray = array();
		foreach ($languages as $language)
			$langArray[$language['language']] = $language['name'];
		$smarty->assign('languages', $langArray);
	}

	if (!$_GET['rules_sub'] || $_GET['rules_sub'] == 'show')
	{
		$rules = bl\tribunal\getAllRules($_GET['languages']);

		$page = $_GET['page'];
		if (!$page)
			$page = 1;

		$pagelinks = '';
		if (count($rules) > 5)
		{
			$pages = ceil(count($rules) / 5);
			$pagelinks = bl\general\createPageLinks($_GET['chose'], $pages, 'sub=rules&rules_sub=show&languages='.$_GET['languages']);
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
					bl\tribunal\deleteRule($_GET['id']);
					break;
				case 'clause':
					bl\tribunal\deleteClause($_GET['id']);
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
				bl\tribunal\insertRule(array(
					'paragraph' => $_POST['paragraph'],
					'title' => $_POST['title'],
					'clauses' => $_POST['description'],
					'language' => ($_GET['languages'] ? $_GET['languages'] : $lang['lang']),
				));
			}
		}

		$rules = bl\tribunal\getAllRules($_GET['languages']);

		$page = $_GET['page'];
		if (!$page)
			$page = 1;

		$pagelinks = '';
		if (count($rules) > 10)
		{
			$pages = ceil(count($rules) / 10);
			$pagelinks = bl\general\createPageLinks($_GET['chose'], $pages, 'sub=rules&rules_sub=show&languages='.$_GET['languages']);
		}

		$smarty->assign('rules', $rules);
		$smarty->assign('page', $page);
		$smarty->assign('pageLinks', $pagelinks);
	}
}
include('loggedin/footer.php');

$smarty->display('tribunal.tpl');