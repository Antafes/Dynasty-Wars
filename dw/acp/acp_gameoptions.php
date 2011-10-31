<?php
include('lib/bl/gameoptions.inc.php');

lib_bl_general_loadLanguageFile('gameoptions', 'acp');
$smarty->assign('lang', $lang);

if ($_GET['gameOptionsSub'] == 'common' || !$_GET['gameOptionsSub'])
{
	$message = '';
	//checking if login is closed
	if (isset($_POST['login_closed']))
	{
		$sql = '
			UPDATE dw_game
			SET login_closed='.$_POST['login_closed'].'
		';
		if (lib_util_mysqlQuery($sql))
		{
			if ($_POST['login_closed'] == 0)
			{
				lib_bl_log_saveLog(17, $_SESSION['user']->getUID(), 0, '');
				$message = $lang['loginUnblocked'];
			}
			elseif ($_POST['login_closed'] == 1)
			{
				lib_bl_log_saveLog(16, $_SESSION['user']->getUID(), 0, '');
				$message = $lang['loginAdminOnly'];
			}
			elseif ($_POST['login_closed'] == 2)
			{
				lib_bl_log_saveLog(15, $_SESSION['user']->getUID(), 0, '');
				$message = $lang['loginBlocked'];
			}
		}
	}

	//checking if registration is closed
	if (isset($_POST['reg_closed']))
	{
		$sql = '
			UPDATE dw_game
			SET reg_closed='.$_POST['reg_closed'].'
		';
		if (lib_util_mysqlQuery($sql))
		{
			if ($_POST['reg_closed'] == 0)
			{
				lib_bl_log_saveLog(18, $_SESSION['user']->getUID(), 0, '', $con);
				$message = $lang['registrationUnblocked'];
			}
			elseif ($_POST['reg_closed'] == 1)
			{
				lib_bl_log_saveLog(19, $_SESSION['user']->getUID(), 0, '', $con);
				$message = $lang['registrationBlocked'];
			}
		}
	}
	//reset of the game
	if ($_POST['reset1'] == 1 && $_POST['reset2'] == 1)
	{
		$sql = 'SELECT uid FROM dw_user WHERE admin = 2';
		$superAdmins = lib_util_mysqlQuery($sql, true);

		$where = '';
		foreach ($superAdmins as $superAdmin)
		{
			if (!$where)
				$where = 'uid = '.mysql_real_escape_string($superAdmin);
			else
				$where .= ' OR uid = '.mysql_real_escape_string($superAdmin);
		}

		$truncateArray = array(
			'dw_clan',
			'dw_clan_applications',
			'dw_clan_rank',
			'dw_clan_points',
			'dw_log',
			'dw_message',
		);

		$deleteArray = array(
			'dw_buildings',
			'dw_points',
			'dw_res',
			'dw_research',
			'dw_user',
		);

		foreach ($truncateArray as $truncate)
		{
			$sql = '
				TRUNCATE '.$truncate.'
			';
			lib_util_mysqlQuery($sql);
		}

		foreach ($deleteArray as $delete)
		{
			$sql = '
				DELETE FROM '.$delete.' WHERE NOT ('.$where.')
			';
			lib_util_mysqlQuery($sql);
		}

		$sql = '
			UPDATE dw_map
			SET uid = "0",
				city = ""
			WHERE city != ""
				AND NOT ('.$where.')
		';
		lib_util_mysqlQuery($sql);

		$message = $lang['resetGame'];
		lib_bl_log_saveLog(20, $_SESSION['user']->getUID(), '', '');
	}
	//changing of the board adress
	if ($_POST['board'])
	{
		var_dump($_POST['board']);
		$sql = '
			UPDATE dw_game
			SET board="'.mysql_real_escape_string($_POST['board']).'"
		';
		if (lib_util_mysqlQuery($sql))
		{
			lib_bl_log_saveLog(27, $_SESSION['user']->getUID(), '', $_POST['board']);
			$message = sprintf($lang['changedBoardAddress'], $_POST['board']);
		}
	}

	if ($_POST['errorReportingChanged'])
	{
		$new_error_report = $_POST['errorReporting'][0] + $_POST['errorReporting'][1] + $_POST['errorReporting'][2] + $_POST['errorReporting'][3];
		$sql = '
			UPDATE dw_game
			SET error_report = '.mysql_real_escape_string($new_error_report).'
		';
		if (lib_util_mysqlQuery($sql))
		{
			lib_bl_log_saveLog(28, $_SESSION['user']->getUID(), '', $new_error_report);
			$message = $lang['changedErrorReporting'];
		}
	}
	//change the status of the unit costs
	if (isset($_POST['unitCosts']))
	{
		$sql = '
			UPDATE dw_game
			SET unitcosts = '.mysql_real_escape_string($_POST['unitCosts']).'
		';
		lib_util_mysqlQuery($sql);

		if ($_POST['unitCosts'])
		{
			lib_bl_log_saveLog(29, $_SESSION['user']->getUID(), '', '');
			$message = $lang['enabledUnitCosts'];
		}
		else
		{
			lib_bl_log_saveLog(30, $_SESSION['user']->getUID(), '', '');
			$message = $lang['disabledUnitCosts'];
		}
	}
	//is attacking allowed?
	if (isset($_POST['canAttack']))
	{
		$sql = '
			UPDATE dw_game
			SET canattack = "'.mysql_real_escape_string($_POST['canAttack']).'"
		';
		lib_util_mysqlQuery($sql);

		if ($_POST['canAttack'])
		{
			lib_bl_log_saveLog(31, $_SESSION['user']->getUID(), '', '');
			$message = $lang['enabledAttacking'];
		}
		else
		{
			lib_bl_log_saveLog(32, $_SESSION['user']->getUID(), '', '');
			$message = $lang['disabledAttacking'];
		}
	}
	//change the games version
	if ($_POST['version'])
	{
		$sql = '
			UPDATE dw_game
			SET version = "'.mysql_real_escape_string($_POST['version']).'"
		';
		if (lib_util_mysqlQuery($sql))
		{
			lib_bl_log_saveLog(33, $_SESSION['user']->getUID(), '', $_POST['version']);
			$message = $lang['changedVersion'];
		}
	}

	if ($message)
		$smarty->assign('message', $message);

	//selection of the gameoptions
	$sql = 'SELECT * FROM dw_game';
	$gameOptionsArray = lib_util_mysqlQuery($sql);
	$smarty->assign('gameOptions', $gameOptionsArray);
}
elseif ($_GET['gameOptionsSub'] == 'menu')
{
	if ($_POST['sort'] || $_POST['entries'])
	{
		lib_bl_gameoptions_setAllEntries($_POST['entries'], $_POST['sort'], $_POST['visible']);
		lib_bl_log_saveLog(34, $_SESSION['user']->getUID(), '', '');
		lib_bl_general_redirect('index.php?chose=acp&sub=gameoptions&gameOptionsSub=menu');
	}

	$acpMenuEntries = lib_bl_gameoptions_getAllEntries(false);
	$count = count($acpMenuEntries) - 3;
	$sortingArray = array_combine(range(2, $count), range(2, $count));
	$smarty->assign('menuEntries', $acpMenuEntries);
	$smarty->assign('entryCount', $count);
	$smarty->assign('sortingArray', $sortingArray);
}
?>
</table>
<?php
$smarty->assign('acpContent', $smarty->fetch('../acp/game.tpl'));