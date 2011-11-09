<?php
include('loggedin/header.php');
lib_bl_general_loadLanguageFile('clan');

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
					WHERE clanname like "%'.mysql_real_escape_string($clanname).'%"
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
					WHERE clantag = "'.mysql_real_escape_string($clantag).'"
				';
			}

			$clanData = lib_util_mysqlQuery($sql, true);

			if ($clanData) //showing of the found clan
			{
				foreach ($clanData as &$clan)
				{
					$sql = '
						SELECT COUNT(*)
						FROM dw_user
						WHERE cid = '.mysql_real_escape_string($clan['cid']).'
							AND !deactivated
						GROUP BY cid
					';
					$clan['users'] = lib_util_mysqlQuery($sql);
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
					"'.mysql_real_escape_string($clanname).'",
					"'.mysql_real_escape_string($clantag).'",
					"'.mysql_real_escape_string($_SESSION['user']->getNick()).'"
				)
			';
			$cid = lib_util_mysqlQuery($sql);
			$points = $_SESSION['user']->getPoints();
			$sql = '
				INSERT INTO dw_clan_points (
					unit_points,
					building_points
				) VALUES (
					'.mysql_real_escape_string($points['unit_points']).',
					'.mysql_real_escape_string($points['building_points']).'
				)
			';
			lib_util_mysqlQuery($sql);
			$sql = '
				INSERT INTO dw_clan_rank (
					cid,
					rnid,
					admin,
					standard
				) VALUES (
					'.mysql_real_escape_string($cid).',
					2,
					0,
					1
				), (
					'.mysql_real_escape_string($cid).',
					1,
					1,
					0
				)
			';
			lib_util_mysqlQuery($sql);

			if ($cid && $_SESSION['user']->setCID($cid) && $_SESSION['user']->setRankID(1))
			{
				lib_bl_log_saveLog(2, $_SESSION['user']->getUID(), 0, '');
				lib_bl_general_redirect('index.php?chose=clan&cid='.$cid.'');
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
			WHERE uid = '.mysql_real_escape_string($_SESSION['user']->getUID()).'
				AND kind = 13
		';
		$smarty->assign('gardenLvl', lib_util_mysqlQuery($sql));
	}
}

include('loggedin/footer.php');

$smarty->display('clan.tpl');