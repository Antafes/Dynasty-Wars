<?php
include('loggedin/header.php');
bl\general\loadLanguageFile('clan');

unset($foundation);
//requesting of get and post variables
$clantag = $_POST['clantag'];
$clanname = $_POST['clan'];
if (isset($_GET['cid']) && is_numeric($_GET['cid']))
	$cid = $_GET['cid'];

$smarty->assign('lang', $lang);

//showing of the clan
if ($_SESSION['user']->getCID() > 0 || $_GET['cid'] > 0)
	include ('clanpage.php');
else //search for clans
{
	if ($_GET['searchclan'])
	{
		if ($_POST['clan'] || $_POST['clantag'])
		{
			//selection of the searched clan
			if ($clanname) //searching for clan name
			{
				$sql = '
					SELECT
						cid,
						clanname,
						clantag
					FROM dw_clan
					WHERE clanname like '.util\mysql\sqlval('%'.$clanname.'%').'
				';
			}
			elseif ($clantag) //searching for clan tag
			{
				$sql = '
					SELECT
						cid,
						clanname,
						clantag
					FROM dw_clan
					WHERE clantag = '.util\mysql\sqlval($clantag).'
				';
			}

			$clanData = util\mysql\query($sql, true);

			if ($clanData) //showing of the found clan
			{
				foreach ($clanData as &$clan)
				{
					$sql = '
						SELECT COUNT(*)
						FROM dw_user
						WHERE cid = '.util\mysql\sqlval($clan['cid']).'
							AND !deactivated
						GROUP BY cid
					';
					$clan['users'] = util\mysql\query($sql);
				}
				unset($clan);

				$smarty->assign('clanData', $clanData);
			}
		}
	}
	elseif ($_GET['newclan']) //clan foundation
	{
		if ($clanname && $clantag)
		{
			$sql = '
				INSERT INTO dw_clan (
					clanname,
					clantag,
					founder
				) VALUES (
					'.util\mysql\sqlval($clanname).',
					'.util\mysql\sqlval($clantag).',
					'.util\mysql\sqlval($_SESSION['user']->getNick()).'
				)
			';
			$cid = util\mysql\query($sql);
			$points = $_SESSION['user']->getPoints();
			$sql = '
				INSERT INTO dw_clan_points (
					unit_points,
					building_points
				) VALUES (
					'.util\mysql\sqlval($points['unit_points']).',
					'.util\mysql\sqlval($points['building_points']).'
				)
			';
			util\mysql\query($sql);
			$sql = '
				INSERT INTO dw_clan_rank (
					cid,
					rnid,
					admin,
					standard
				) VALUES (
					'.util\mysql\sqlval($cid).',
					2,
					0,
					1
				), (
					'.util\mysql\sqlval($cid).',
					1,
					1,
					0
				)
			';
			util\mysql\query($sql);

			if ($cid && $_SESSION['user']->setCID($cid) && $_SESSION['user']->setRankID(1))
			{
				bl\log\saveLog(2, $_SESSION['user']->getUID(), 0, '');
				bl\general\redirect('index.php?chose=clan&cid='.$cid.'');
			}
		}
	}
	else //shown if your not in a clan
	{
		$cityexp = explode(':', $city);
		$map_x = $cityexp[0];
		$map_y = $cityexp[1];
		$sql = '
			SELECT lvl
			FROM dw_buildings
			WHERE uid = '.util\mysql\sqlval($_SESSION['user']->getUID()).'
				AND kind = 13
		';
		$smarty->assign('gardenLvl', util\mysql\query($sql));
	}
}

include('loggedin/footer.php');

$smarty->display('clan.tpl');