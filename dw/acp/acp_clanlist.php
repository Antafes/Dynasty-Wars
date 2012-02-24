<?php
lib_bl_general_loadLanguageFile('clans', 'acp');
$smarty->assign('lang', $lang);

if ($_SESSION['user']->getGameRank() == 1 || $_SESSION['user']->getGameRank() == 2)
{
	if ($_GET['cid'])
	{
		if ($_GET['umode'] == 1)
		{
			$sql = '
				SELECT
					u.uid,
					u.nick,
					u.blocked,
					crn.rankname
				FROM dw_user u
				JOIN dw_clan_rank cr USING (rankid)
				JOIN dw_clan_rankname crn USING (rnid)
				WHERE u.cid = '.mysql_real_escape_string($_GET['cid']).'
			';
			$smarty->assign('memberList', util\mysql\query($sql, true));
		}
		else
		{
			$sql = '
				SELECT
					cid,
					clanname,
					clantag,
					founder,
					public_text,
					internal_text,
					(
						SELECT count(*)
						FROM dw_user
						WHERE cid = '.$_GET['cid'].'
							AND NOT deactivated
					) AS memberCount
				FROM dw_clan
				WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
				ORDER BY cid
			';
			$clan = util\mysql\query($sql);
			$sql = '
				SELECT sum(p.unit_points) + sum(p.building_points) + sum(p.unit_points + p.building_points) AS points
				FROM dw_clan c
				LEFT JOIN dw_user u USING (cid)
				LEFT JOIN dw_points p USING (uid)
				WHERE c.cid = '.mysql_real_escape_string($_GET['cid']).'
					AND NOT u.deactivated
				GROUP BY c.cid
			';
			$clan['points'] = util\mysql\query($sql);

			$smarty->assign('clan', $clan);
		}
	}
	else
	{
//selection of all clans
		$sql = '
			SELECT
				cid,
				clanname,
				clantag
			FROM dw_clan
		';
		$data = util\mysql\query($sql, true);
		$GLOBALS['firePHP']->log($data);
		$smarty->assign('clanList', $data);
	}
}

$smarty->assign('acpContent', $smarty->fetch('../acp/clans.tpl'));