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
	$clanname = util\mysql\query('
		SELECT clanname FROM dw_clan
		WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
	');

	//acceptation message
	bl\general\sendMessage(0, $_POST['entuid'], sprintf($lang['applicationat'], $clanname), sprintf($lang['acceptmsg'], $clanname), 3);
	bl\log\saveLog(4, $_SESSION['user']->getUID(), $_POST['entuid'], '');
	$rankid_new = util\mysql\query('
		SELECT rankid FROM dw_clan_rank
		WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
			AND standard = 1
	');

	$erg = util\mysql\query('
		UPDATE dw_user
		SET cid = '.mysql_real_escape_string($_GET['cid']).',
			rankid = '.mysql_real_escape_string($rankid_new).'
		WHERE uid = '.mysql_real_escape_string($_POST['entuid']).'
	');

	util\mysql\query('
		DELETE FROM dw_clan_applications
		WHERE uid = '.mysql_real_escape_string($_POST['entuid']).'
	');
}
elseif ($_POST['decline'] && ($_GET['cid'] == $_SESSION['user']->getCID())) //member not accepted
{
	$clanname = util\mysql\query('
		SELECT clanname FROM dw_clan
		WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
	');

	//decline message
	bl\general\sendMessage(0, $_POST['entuid'], sprintf($lang['applicationat'], $clanname), sprintf($lang['declinemsg'], $clanname), 3);
	bl\log\saveLog(5, $_SESSION['user']->getUID(), $_POST['entuid'], '');

	util\mysql\query('
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
$clan = util\mysql\query($sql);

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
				NOW()
			)
		';
		if (util\mysql\query($sql))
			$smarty->assign('applicationSaved', 1);
	}
}
else
{
	if ($del && !$umode) //delete clan
	{
		$sql = '
			DELETE FROM dw_clan
			WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
		';
		$delerg = util\mysql\query($sql);
		$sql = '
			SELECT rnid
			FROM dw_clan_rank
			WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
				AND rnid != 1
				AND rnid != 2
		';
		$rnids = util\mysql\query($sql, true);
		if ($rnids)
		{
			$sql = '
				DELETE FROM dw_clan_rankname
				WHERE rnid IN ('.mysql_real_escape_string(implode(', ', $rnids)).')
			';
			util\mysql\query($sql);
		}
		$sql = '
			DELETE FROM dw_clan_rank
			WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
		';
		$delerg2 = util\mysql\query($sql);
		$sql = '
			UPDATE dw_user
			SET cid = 0,
				rankid = 0
			WHERE cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
		';
		$usererg = util\mysql\query($sql);
		if ($delerg && $delerg2 && $usererg)
		{
			bl\log\saveLog(6, $_SESSION['user']->getUID(), 0, '');
			$del = 3;
		}
	}
	elseif (($cmode != 1) && ($cmode != 2) && ($cmode != 3) && ($enter != 1)) //showing of own clan
	{
		//showing of new applications (only clan leader)
		if ($clan['applications'] == 1 && $_GET['cid'] == $_SESSION['user']->getCID() && !$own_uid) {
			$apps = $lang['one'];
			$ending = '';
		} elseif ( $clan['applications'] > 1) {
			$apps = $clan['applications'];
			$ending = $lang['ending'];
		}

		$smarty->assign('applicationCount', $apps);
		$smarty->assign('applicationEnding', $ending);
	}
	elseif ($cmode == 2 && $_SESSION['user']->getCID() == $_GET['cid'] && !$own_uid)
	{
		if (($umode != 1) && ($umode != 2) && ($umode != 3))
		{
			//only clan leader
			//changing of the clan description, the clan internal text, the clan ranks, deletion of the clan, member management, applications
			if ($newpublic_text || $_POST['changing'])
			{
				//changing of the clan description
				$sql = '
					UPDATE dw_clan
					SET public_text = "'.mysql_real_escape_string($newpublic_text).'"
					WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
				';
				if (util\mysql\query($sql))
					$clan['public_text'] = $newpublic_text;
			}
			if ($newinternal_text || $_POST['changing'])
			{
				//changing of the clan internal text
				$sql = '
					UPDATE dw_clan
					SET internal_text = "'.mysql_real_escape_string($newinternal_text).'"
					WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
				';
				if (util\mysql\query($sql))
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
				$smarty->assign('clanRanks', util\mysql\query($sql));
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
				$helperg = util\mysql\query($sql);

				if ($helperg['standard'])
				{
					$sql = '
						SELECT MAX(rankid)
						FROM dw_clan_rank
						WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
					';
					$max_rankid = util\mysql\query($sql) - 1;
					$sql = '
						UPDATE dw_clan_rank
						SET standard = 1
						WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
							AND rankid = '.mysql_real_escape_string($max_rankid).'
					';
					util\mysql\query($sql);
				}

				$sql = '
					DELETE FROM dw_clan_rank
					WHERE cid = '.mysql_real_escape_string($_GET['cid']).'
						AND rankid = '.mysql_real_escape_string($_GET['rank']).'
				';
				if (util\mysql\query($sql))
				{
					$sql = '
						DELETE FROM dw_clan_rankname
						WHERE rnid = '.mysql_real_escape_string($helperg['rnid']).'
					';
					util\mysql\query($sql);
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
				$smarty->assign('clanRanks', util\mysql\query($sql));
			}
			elseif ($_GET['do'] == 'new' || $rank > 0)
			{
				$rankname = $_POST['rankname'];
				$standard = $_POST['standard'];
				if($rankname)
				{
					$newrankid = util\mysql\query('SELECT MAX(rankid) FROM dw_clan_rank WHERE cid='.$_GET['cid'].'');
					$newrankid++;
					if ($standard)
					{
						$standard = 1;

						$sql = '
							UPDATE `dw_clan_rank` SET `standard` = 0
							WHERE `cid` = "'.mysql_real_escape_string($_GET['cid']).'" AND `standard` = 1
						';
						util\mysql\query($sql);

						if ($rank > 0)
						{
							$sql = '
								UPDATE `dw_clan_rank` SET `standard` = 1
								WHERE `rankid` = "'.mysql_real_escape_string($rank).'"
							';
							util\mysql\query($sql);
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
						$new_rnid = util\mysql\query($sql);
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
						$erg1 = util\mysql\query($sql);
						if ($erg1)
							$err['rankcreated'] = 1;
					}
					elseif ($rank > 0)
					{
						$sql = '
							SELECT `rnid` FROM `dw_clan_rankname`
							WHERE `rankname` LIKE "'.mysql_real_escape_string($rankname).'"
						';
						$rnid = util\mysql\query($sql);
						if (!$rnid)
						{
							$sql = '
								INSERT INTO `dw_clan_rankname` (`rankname`)
								VALUES ("'.mysql_real_escape_string($rankname).'")
							';
							$rnid = util\mysql\query($sql);
						}
						$sql = '
							UPDATE `dw_clan_rank` SET `rnid` = "'.mysql_real_escape_string($rnid).'"
							WHERE `rankid` = "'.mysql_real_escape_string($rank).'"
						';
						util\mysql\query($sql);
					}
					bl\general\redirect('index.php?chose=clan&cid='.$_GET['cid'].'&cmode=2&umode=1');
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
						$rank_res = util\mysql\query($sql);
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
				$changed = util\mysql\query($sql);
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
			$smarty->assign('userList', util\mysql\query($sql));
			$sql = '
				SELECT
					cr.rankid,
					crn.rankname
				FROM dw_clan_rank cr
				LEFT JOIN dw_clan_rankname crn USING (rnid)
				WHERE cr.cid = '.mysql_real_escape_string($_GET['cid']).'
				ORDER BY cr.rankid
			';
			$ranks = util\mysql\query($sql);

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
						ca.create_datetime,
						u.nick
					FROM dw_clan_applications ca
					JOIN dw_user u USING(uid)
					WHERE ca.cid = '.mysql_real_escape_string($_SESSION['user']->getCID()).'
				';
				$applications = util\mysql\query($sql, true);
				$GLOBALS['firePHP']->log($applications, 'applications before');

				if ($applications)
				{
					foreach ($applications as &$application)
					{
						$applicationTime = \DWDateTime::createFromFormat('Y-m-d H:i:s', $application['create_datetime']);
						$application['create_datetime'] = $applicationTime->format($lang['timeformat']);
					}
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
						ca.create_datetime,
						u.nick
					FROM dw_clan_applications ca
					JOIN dw_user u USING (uid)
					WHERE ca.appid = '.mysql_real_escape_string($_GET['appid']).'
				';
				$app = util\mysql\query($sql);
				$GLOBALS['firePHP']->log($app, 'application');
				$applicationTime = \DWDateTime::createFromFormat('Y-m-d H:i:s', $app['create_datetime']);
				$app['create_datetime'] = $applicationTime->format($lang['timeformat']);
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
		$membersList = util\mysql\query($sql, true);
		$smarty->assign('membersListData', $membersList);
		$usermapEntry = bl\gameOptions\getMenuEntry('usermap');
		$smarty->assign('usermapEnabled', $usermapEntry['active']);
		$smarty->assign('encodeString', urlencode('clan§cid='.$_GET['cid'].'§cmode=1'));
	}
	elseif ($cmode == 3 && $_SESSION['user']->getCID() == $_GET['cid'] && !$own_uid) //message to all members
	{
		if ($_POST['sent'])
		{
			$sql = 'SELECT uid FROM dw_user WHERE cid = '.mysql_real_escape_string($_GET['cid']).'';
			$recipients = util\mysql\query($sql, true);
			$sent = false;
			foreach ($recipients as $recipient)
			{
				$sentResult = bl\general\sendMessage($_SESSION['user']->getUID(), $recipient, mysql_real_escape_string($title), mysql_real_escape_string($membermsg), 1);
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