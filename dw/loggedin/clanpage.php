<?php
//requesting of get and post variables
$cmode = $_GET['cmode'];
$enter = $_GET['enter'];
$del = $_GET['del'];
$applicationtext = $_POST['applicationtext'];
$entering = $_GET['entering'];
$appid = $_POST['appid'];
$sendmsg = $_GET['sendmsg'];
$title = $_POST['title'];
$membermsg = $_POST['membermsg'];
$newpublic_text = $_POST['public_text'];
$newinternal_text = $_POST['intern'];
$umode = $_GET['umode'];
$newrankid = $_POST['rankid'];
$new = $_GET['new'];
if ($_GET['do'] == 'new')
	$new = 1;

//new member
if ($_POST['accept'] && $_GET['cid'] == $_SESSION['user']->getCID()) //accepting of the new member
{
	$clanname = lib_util_mysqlQuery('
		SELECT clanname FROM dw_clan
		WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
	');

	//acceptation message
	lib_bl_general_sendMessage(0, $_POST['entuid'], sprintf($lang['applicationat'], $clanname), sprintf($lang['acceptmsg'], $clanname), 3);
	lib_bl_log_saveLog(4, $_SESSION['user']->getUID(), $_POST['entuid'], '');
	$rankid_new = lib_util_mysqlQuery('
		SELECT rankid FROM dw_clan_rank
		WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
			AND standard = 1
	');

	$erg = lib_util_mysqlQuery('
		UPDATE dw_user
		SET cid = '.mysql_real_escape_string($_GET['cid']).',
			rankid = '.mysql_real_escape_string($rankid_new).'
		WHERE uid = '.mysql_real_escape_string($_POST['entuid']).'
	');

	lib_util_mysqlQuery('
		DELETE FROM dw_clan_applications
		WHERE uid = '.mysql_real_escape_string($_POST['entuid']).'
	');
}
elseif ($_POST['decline'] && ($_GET['cid'] == $_SESSION['user']->getCID()))
{ //member not accepted
	$clanname = lib_util_mysqlQuery('
		SELECT clanname FROM dw_clan
		WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
	');

	//decline message
	lib_bl_general_sendMessage(0, $_POST['entuid'], sprintf($lang['applicationat'], $clanname), sprintf($lang['declinemsg'], $clanname), 3);
	lib_bl_log_saveLog(5, $_SESSION['user']->getUID(), $_POST['entuid'], '');

	lib_util_mysqlQuery('
		DELETE FROM dw_clan_applications
		WHERE uid = '.mysql_real_escape_string($_POST['entuid']).'
	');
}

//selection of the clan
$sql = '
	SELECT
		DISTINCT c.clanname,
		c.clantag,
		c.founder,
		c.public_text,
		c.internal_text,
		(SELECT COUNT(appid) FROM dw_clan_applications WHERE cid = c.cid) AS applications,
		COUNT(u.uid) AS userCount,
		SUM(p.unit_points) AS unitPoints,
		SUM(p.building_points) AS buildingPoints,
		SUM(p.unit_points) + SUM(p.building_points) AS points
	FROM dw_clan c
	JOIN dw_user u USING (cid)
	LEFT JOIN dw_points p ON (u.uid = p.uid)
	WHERE c.cid = '.mysql_real_escape_string($_GET['cid']).'
';
$clan = lib_util_mysqlQuery($sql);

if ($_GET['cid'] != $_SESSION['user']->getCID()) //clan applications
{
	if ($enter == 2) //insert of the new application
	{
		$sql = '
			INSERT INTO dw_clan_applications (
				cid,
				uid,
				applicationtext,
				apptime
			) VALUES (
				'.mysql_real_escape_string($_GET['cid']).',
				'.mysql_real_escape_string($_SESSION['user']->getUID()).',
				"'.mysql_real_escape_string($applicationtext).'",
				'.time().'
			)
		';
		if (lib_util_mysqlQuery($sql))
			$smarty->assign('applicationSaved', 1);
	}
}
else //delete clan
{
	if ($del && !$umode)
	{
		$sql = '
			DELETE FROM dw_clan
			WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
		';
		$delerg = lib_util_mysqlQuery($sql);
		$sql = '
			SELECT rnid
			FROM dw_clan_rank
			WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
				AND rnid != 1
				AND rnid != 2
		';
		$rnids = lib_util_mysqlQuery($sql, true);
		if ($rnids)
		{
			$sql = '
				DELETE FROM dw_clan_rankname
				WHERE rnid IN ('.mysql_real_escape_string(implode(', ', $rnids)).')
			';
			lib_util_mysqlQuery($sql);
		}
		$sql = '
			DELETE FROM dw_clan_rank
			WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
		';
		$delerg2 = lib_util_mysqlQuery($sql);
		$sql = '
			UPDATE dw_user
			SET cid = 0,
				rankid = 0
			WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
		';
		$usererg = lib_util_mysqlQuery($sql);
		if ($delerg and $delerg2 and $usererg)
		{
			lib_bl_log_saveLog(6, $_SESSION['user']->getUID(), 0, '');
			$del = 3;
		}
	}
	elseif (($cmode != 1) and ($cmode != 2) and ($cmode != 3) and ($enter != 1)) //showing of own clan
	{
		//showing of new applications (only clan leader)
		if ($clan['applications'] == 1 and $_GET['cid'] == $_SESSION['user']->getCID() and !$own_uid) {
			$apps = $lang['one'];
			$ending = '';
		} elseif ( $clan['applications'] > 1) {
			$apps = $clan['applications'];
			$ending = $lang['ending'];
		}

		$smarty->assign('applicationCount', $apps);
		$smarty->assign('applicationEnding', $ending);
	}
	elseif ($cmode == 2 and $_SESSION['user']->getCID() == $_GET['cid'] and !$own_uid)
	{
		if (($umode != 1) and ($umode != 2) and ($umode != 3))
		{
			//only clan leader
			//changing of the clan description, the clan internal text, the clan ranks, deletion of the clan, member management, applications
			if ($newpublic_text or $_POST['changing'])
			{
				//changing of the clan description
				$sql = '
					UPDATE dw_clan
					SET public_text = "'.mysql_real_escape_string($newpublic_text).'"
					WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
				';
				if (lib_util_mysqlQuery($sql))
					$clan['public_text'] = $newpublic_text;
			}
			if ($newinternal_text or $_POST['changing'])
			{
				//changing of the clan internal text
				$sql = '
					UPDATE dw_clan
					SET internal_text = "'.mysql_real_escape_string($newinternal_text).'"
					WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
				';
				if (lib_util_mysqlQuery($sql))
					$clan['internal_text'] = $newinternal_text;
			}
		}
		elseif ($umode == 1)
		{
			$rank = (int)$_GET['rank'];
			$del = $_GET['del'];
			if (!$_GET['do'])
			{
				$sql = '
					SELECT
						rankid,
						rankname,
						standard
					FROM dw_clan_rank
					LEFT JOIN dw_clan_rankname USING (rnid)
					WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
				';
				$smarty->assign('clanRanks', lib_util_mysqlQuery($sql));
			}
			elseif ($_GET['do'] == 'del' && $_GET['rank'])
			{
				$sql = '
					SELECT
						standard,
						rnid
					FROM dw_clan_rank
					WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
						AND rankid = '.mysql_real_escape_string($_GET['rank']).'
				';
				$helperg = lib_util_mysqlQuery($sql);

				if ($helperg['standard'])
				{
					$sql = '
						SELECT MAX(rankid)
						FROM dw_clan_rank
						WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
					';
					$max_rankid = lib_util_mysqlQuery($sql) - 1;
					$sql = '
						UPDATE dw_clan_rank
						SET standard = 1
						WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
							AND rankid = '.mysql_real_escape_string($max_rankid).'
					';
					lib_util_mysqlQuery($sql);
				}

				$sql = '
					DELETE FROM dw_clan_rank
					WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
						AND rankid = '.mysql_real_escape_string($_GET['rank']).'
				';
				if (lib_util_mysqlQuery($sql))
				{
					$sql = '
						DELETE FROM dw_clan_rankname
						WHERE rnid = '.mysql_real_escape_string($helperg['rnid']).'
					';
					lib_util_mysqlQuery($sql);
					$smarty->assign('deleted', 1);
				}
				$sql = '
					SELECT
						rankid,
						rankname,
						standard
					FROM dw_clan_rank
					LEFT JOIN dw_clan_rankname USING (rnid)
					WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
				';
				$smarty->assign('clanRanks', lib_util_mysqlQuery($sql));
			}
			elseif ($_GET['do'] == 'new' || $rank > 0)
			{
				$rankname = $_POST['rankname'];
				$standard = $_POST['standard'];
				if($rankname)
				{
					$newrankid = lib_util_mysqlQuery('SELECT MAX(rankid) FROM dw_clan_rank WHERE cid='.$_GET['cid'].'');
					$newrankid++;
					if ($standard)
					{
						$standard = 1;

						$sql = '
							UPDATE `dw_clan_rank` SET `standard` = 0
							WHERE `cid` = "'.mysql_real_escape_string($_GET['cid']).'" AND `standard` = 1
						';
						lib_util_mysqlQuery($sql);

						if ($rank > 0)
						{
							$sql = '
								UPDATE `dw_clan_rank` SET `standard` = 1
								WHERE `rankid` = "'.mysql_real_escape_string($rank).'"
							';
							lib_util_mysqlQuery($sql);
						}
					}
					else
						$standard = 0;

					if (!$rank)
					{
						$sql = '
							INSERT INTO dw_clan_rankname (rankname)
							VALUES ("'.mysql_real_escape_string($rankname).'")
						';
						$new_rnid = lib_util_mysqlQuery($sql);
						$sql = '
							INSERT INTO dw_clan_rank (
								cid,
								rankid,
								rnid,
								standard
							) VALUES (
								"'.mysql_real_escape_string($_GET['cid']).'",
								"'.mysql_real_escape_string($newrankid).'",
								"'.mysql_real_escape_string($new_rnid).'",
								"'.mysql_real_escape_string($standard).'")
							';
						$erg1 = lib_util_mysqlQuery($sql);
						if ($erg1)
							$err['rankcreated'] = 1;
					}
					elseif ($rank > 0)
					{
						$sql = '
							SELECT `rnid` FROM `dw_clan_rankname`
							WHERE `rankname` LIKE "'.mysql_real_escape_string($rankname).'"
						';
						$rnid = lib_util_mysqlQuery($sql);
						if (!$rnid)
						{
							$sql = '
								INSERT INTO `dw_clan_rankname` (`rankname`)
								VALUES ("'.mysql_real_escape_string($rankname).'")
							';
							$rnid = lib_util_mysqlQuery($sql);
						}
						$sql = '
							UPDATE `dw_clan_rank` SET `rnid` = "'.mysql_real_escape_string($rnid).'"
							WHERE `rankid` = "'.mysql_real_escape_string($rank).'"
						';
						lib_util_mysqlQuery($sql);
					}
					lib_bl_general_redirect('index.php?chose=clan&cid='.$_GET['cid'].'&cmode=2&umode=1');
				}
				else
				{
					if ($rank > 0)
					{
						$sql = '
							SELECT * FROM `dw_clan_rank` `cr`
							INNER JOIN `dw_clan_rankname` `crn` USING (rnid)
							WHERE `rankid` = "'.mysql_real_escape_string($rank).'"
						';
						$rank_res = lib_util_mysqlQuery($sql);
						$smarty->assign('rankRes', $rank_res);
					}
				}
			}
		}
		elseif ($umode == 2) //changing of the member rank
		{
			if ($_POST['member'])
			{
				$sql = '
					UPDATE dw_user
					SET rankid = '.mysql_real_escape_string($newrankid).'
					WHERE uid = '.mysql_real_escape_string($_POST['member']).'
				';
				$changed = lib_util_mysqlQuery($sql);
				$smarty->assign('changedRank', 1);
			}

			$sql = '
				SELECT
					uid,
					nick,
					rankid
				FROM dw_user
				WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
					AND !deactivated
				ORDER BY rankid
			';
			$smarty->assign('userList', lib_util_mysqlQuery($sql));
			$sql = '
				SELECT
					cr.rankid,
					crn.rankname
				FROM dw_clan_rank cr
				LEFT JOIN dw_clan_rankname crn USING (rnid)
				WHERE cr.cid = '.mysql_real_escape_string($_GET['cid']).'
				ORDER BY cr.rankid
			';
			$ranks = lib_util_mysqlQuery($sql);

			$rankList = array();
			foreach ($ranks as $rank)
				$rankList[$rank['rankid']] = htmlentities($rank['rankname']);

			$smarty->assign('rankList', $rankList);
			/**
			 * @todo
			 * - deleting of clan members
			 * - center the back link and the dropdowns
			 */
		}
		elseif ($umode == 3)
		{
			if (!$_GET['appid'])
			{
				$sql = '
					SELECT
						ca.appid,
						ca.apptime,
						u.nick
					FROM dw_clan_applications ca
					JOIN dw_user u USING(uid)
					WHERE ca.cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
				';
				$applications = lib_util_mysqlQuery($sql, true);
				$GLOBALS['firePHP']->log($applications, 'applications before');

				if ($applications)
				{
					foreach ($applications as &$application)
						$application['apptime'] = date($lang['timeformat'], $application['apptime']);
					unset($application);
				}

				$GLOBALS['firePHP']->log($applications, 'applications after');
				if ($applications)
					$smarty->assign('applications', $applications);
			}
			else
			{
				$sql = '
					SELECT
						u.uid,
						ca.applicationtext,
						ca.apptime,
						u.nick
					FROM dw_clan_applications ca
					JOIN dw_user u USING (uid)
					WHERE ca.appid = '.mysql_real_escape_string($_GET['appid']).'
				';
				$app = lib_util_mysqlQuery($sql);
				$GLOBALS['firePHP']->log($app, 'application');
				$app['apptime'] = date($lang['timeformat'], $app['apptime']);
				$smarty->assign('application', $app);
			}
		}
	}
	elseif ($cmode == 1 && $_SESSION['user']->getCID() == $_GET['cid'] && !$own_uid)
	{
		$sql = '
			SELECT
				u.uid,
				u.nick,
				crn.rankname,
				u.blocked
			FROM dw_user u
			JOIN dw_clan_rank cr USING (rankid)
			JOIN dw_clan_rankname crn USING (rnid)
			WHERE u.cid = '.mysql_real_escape_string($_GET['cid']).'
				AND !u.deactivated
			ORDER BY u.rankid
		';
		$membersList = lib_util_mysqlQuery($sql, true);
		$smarty->assign('membersListData', $membersList);
		$usermapEntry = lib_bl_gameoptions_getEntry('usermap');
		$smarty->assign('usermapEnabled', $usermapEntry['active']);
	}
	elseif ($cmode == 3 && $_SESSION['user']->getCID() == $_GET['cid'] && !$own_uid) //message to all members
	{
		if ($_POST['sent'])
		{
			$sql = 'SELECT uid FROM dw_user WHERE cid = '.mysql_real_escape_string($_GET['cid']).'';
			$recipients = lib_util_mysqlQuery($sql, true);
			$sent = false;
			foreach ($recipients as $recipient)
			{
				$sentResult = lib_bl_general_sendMessage($_SESSION['user']->getUID(), $recipient, mysql_real_escape_string($title), mysql_real_escape_string($membermsg), 1);
				if ($sentResult)
					$sent = true;
			}
			if ($sent)
				$err['msgsend'] = 1;
		}
	}
}

$smarty->assign('clanData', $clan);

$smarty->assign('clanPage', $smarty->fetch('clanpage.tpl'));