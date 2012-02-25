<?php
include('lib/bl/gameoptions.inc.php');

bl\general\loadLanguageFile('gameoptions', 'acp');
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
		if (util\mysql\query($sql))
		{
			if ($_POST['login_closed'] == 0)
			{
				bl\log\saveLog(17, $_SESSION['user']->getUID(), 0, '');
				$message = $lang['loginUnblocked'];
			}
			elseif ($_POST['login_closed'] == 1)
			{
				bl\log\saveLog(16, $_SESSION['user']->getUID(), 0, '');
				$message = $lang['loginAdminOnly'];
			}
			elseif ($_POST['login_closed'] == 2)
			{
				bl\log\saveLog(15, $_SESSION['user']->getUID(), 0, '');
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
		if (util\mysql\query($sql))
		{
			if ($_POST['reg_closed'] == 0)
			{
				bl\log\saveLog(18, $_SESSION['user']->getUID(), 0, '', $con);
				$message = $lang['registrationUnblocked'];
			}
			elseif ($_POST['reg_closed'] == 1)
			{
				bl\log\saveLog(19, $_SESSION['user']->getUID(), 0, '', $con);
				$message = $lang['registrationBlocked'];
			}
		}
	}
	//reset of the game
	if ($_POST['reset1'] == 1 && $_POST['reset2'] == 1)
	{
		$sql = 'SELECT uid FROM dw_user WHERE admin = 2';
		$superAdmins = util\mysql\query($sql, true);

		$where = '';
		foreach ($superAdmins as $superAdmin)
		{
			if (!$where)
				$where = 'uid = '.util\mysql\sqlval($superAdmin);
			else
				$where .= ' OR uid = '.util\mysql\sqlval($superAdmin);
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
			util\mysql\query($sql);
		}

		foreach ($deleteArray as $delete)
		{
			$sql = '
				DELETE FROM '.$delete.' WHERE NOT ('.$where.')
			';
			util\mysql\query($sql);
		}

		$sql = '
			UPDATE dw_map
			SET uid = "0",
				city = ""
			WHERE city != ""
				AND NOT ('.$where.')
		';
		util\mysql\query($sql);

		$message = $lang['resetGame'];
		bl\log\saveLog(20, $_SESSION['user']->getUID(), '', '');
	}
	//changing of the board adress
	if ($_POST['board'])
	{
		var_dump($_POST['board']);
		$sql = '
			UPDATE dw_game
			SET board = '.util\mysql\sqlval($_POST['board']).'
		';
		if (util\mysql\query($sql))
		{
			bl\log\saveLog(27, $_SESSION['user']->getUID(), '', $_POST['board']);
			$message = sprintf($lang['changedBoardAddress'], $_POST['board']);
		}
	}

	if ($_POST['errorReportingChanged'])
	{
		$new_error_report = $_POST['errorReporting'][0] + $_POST['errorReporting'][1] + $_POST['errorReporting'][2] + $_POST['errorReporting'][3];
		$sql = '
			UPDATE dw_game
			SET error_report = '.util\mysql\sqlval($new_error_report).'
		';
		if (util\mysql\query($sql))
		{
			bl\log\saveLog(28, $_SESSION['user']->getUID(), '', $new_error_report);
			$message = $lang['changedErrorReporting'];
		}
	}
	//change the status of the unit costs
	if (isset($_POST['unitCosts']))
	{
		$sql = '
			UPDATE dw_game
			SET unitcosts = '.util\mysql\sqlval($_POST['unitCosts']).'
		';
		util\mysql\query($sql);

		if ($_POST['unitCosts'])
		{
			bl\log\saveLog(29, $_SESSION['user']->getUID(), '', '');
			$message = $lang['enabledUnitCosts'];
		}
		else
		{
			bl\log\saveLog(30, $_SESSION['user']->getUID(), '', '');
			$message = $lang['disabledUnitCosts'];
		}
	}
	//is attacking allowed?
	if (isset($_POST['canAttack']))
	{
		$sql = '
			UPDATE dw_game
			SET canattack = '.util\mysql\sqlval($_POST['canAttack']).'
		';
		util\mysql\query($sql);

		if ($_POST['canAttack'])
		{
			bl\log\saveLog(31, $_SESSION['user']->getUID(), '', '');
			$message = $lang['enabledAttacking'];
		}
		else
		{
			bl\log\saveLog(32, $_SESSION['user']->getUID(), '', '');
			$message = $lang['disabledAttacking'];
		}
	}
	//change the games version
	if ($_POST['version'])
	{
		$sql = '
			UPDATE dw_game
			SET version = '.util\mysql\sqlval($_POST['version']).'
		';
		if (util\mysql\query($sql))
		{
			bl\log\saveLog(33, $_SESSION['user']->getUID(), '', $_POST['version']);
			$message = $lang['changedVersion'];
		}
	}

	if ($message)
		$smarty->assign('message', $message);

	//selection of the gameoptions
	$smarty->assign('gameOptions', bl\gameOptions\getGameOptions());
}
elseif ($_GET['gameOptionsSub'] == 'menu')
{
	if ($_POST['sort'] || $_POST['entries'])
	{
		bl\gameOptions\setAllMenuEntries($_POST['entries'], $_POST['sort'], $_POST['visible']);
		bl\log\saveLog(34, $_SESSION['user']->getUID(), '', '');
		bl\general\redirect('index.php?chose=acp&sub=gameoptions&gameOptionsSub=menu');
	}

	$acpMenuEntries = bl\gameOptions\getAllMenuEntries(false);
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