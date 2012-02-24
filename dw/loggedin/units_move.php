<?php
$unitSmarty = new Smarty();

$unitSmarty->assign('lang', $lang);

$unitSmarty->assign('back', $lang['back']);
$unitSmarty->assign('units', $lang['units']);
$unitSmarty->assign('quantity', $lang['quantity']);
$unitSmarty->assign('maxCount', $lang['maxcount']);
$unitSmarty->assign('add', $lang['add']);
$unitSmarty->assign('remove', $lang['remove']);
$unitSmarty->assign('noUnits', $lang['no_units']);
$unitSmarty->assign('textSend', $lang['send']);

if ($_GET['mode'] == 'create')
{
	if ($_POST['create'])
	{
		$pos = explode(':', $_POST['pos']);
		$posx = intval($pos[0]);
		$posy = intval($pos[1]);
		for ($i = 0; $i < intval($_POST['ids']); $i++)
			if ($_POST['id'.$i] > 0)
				$unids[$i] = lib_bl_troops_checkComplete(intval($_POST['unid'.$i]), $posx, $posy, intval($_POST['id'.$i]), $_SESSION['user']->getUID());

		if (count($unids) > 0)
			$created = lib_bl_troops_createTroop($_SESSION['user']->getUID(), $posx, $posy, $unids, $lang['trooppre']);
	}

	$unitSmarty->assign('create_troop', $lang['create_troop']);

	if ($created)
		$unitSmarty->assign('troopCreated', $lang['troop_created']);

	$unitSmarty->assign('create', $lang['create']);
	$pos = lib_bl_troops_getPos($_SESSION['user']->getUID(), 'units');
	if ($pos)
	{
		$position_list = array();
		foreach ($pos as $part)
		{
			$atpos = lib_bl_troops_getAtPos($_SESSION['user']->getUID(), $part['x'], $part['y'], 'units');
			$unit_list = array();
			foreach ($atpos as $atpos_part)
			{
				if ($atpos_part['kind'] == 19)
					$name = $lang['daimyo'].' '.$nick;
				else
					$name = $lang['unit'][$atpos_part['kind']];

				$unit_list[] = array(
					'name' => $name,
					'unid' => $atpos_part['unid'],
					'count' => $atpos_part['count'],
					'count_formatted' => util\math\numberFormat($atpos_part['count'], 0),
				);
			}
			$position_list[] = array(
				'units' => $unit_list,
			) + $part;
		}

		$unitSmarty->assign('positionList', $position_list);
	}

	$template_file = 'units_move_create_troop.tpl';
}
elseif ($_GET['mode'] == 'edit')
{
	if ($_POST['change'])
		lib_bl_troops_rename($_GET['tid'], $_POST['tname']);

	$troop = lib_bl_troops_getTroop($_GET['tid']);
	if (!$_GET['do'])
	{
		$troopunits = lib_bl_troops_getTroopUnits(intval($_GET['tid']));
		$unitSmarty->assign('editTroop', sprintf($lang['edittroop'], $troop['name']));
		$unit_list = array();
		$sum = 0;
		foreach ($troopunits as $tu_part)
		{
			if ($tu_part['kind'] == 19)
				$name = $lang['daimyo'].' '.$nick;
			else
				$name = $lang['unit'][$tu_part['kind']];

			$sum += $tu_part['count'];
			$unit_list[] = array(
				'name' => $name,
				'count' => util\math\numberFormat((int)$tu_part['count'], 0),
			);
		}
		$unitSmarty->assign('unitList', $unit_list);
		$unitSmarty->assign('unitSum', util\math\numberFormat($sum, 0));
		$unitSmarty->assign('name', $lang['name']);
		$unitSmarty->assign('change', $lang['change']);
		$unitSmarty->assign('troop', $troop);
		$template_file = 'units_move_edit_troop.tpl';
	}
	elseif ($_GET['do'] == 'add')
	{
		if ($_POST['add'])
		{
			for ($i = 0; $i < intval($_POST['count']); $i++)
				if ($_POST['amount'.$i] > 0)
					$unids[$i] = lib_bl_troops_checkComplete(intval($_POST['unid'.$i]), $troop['pos_x'], $troop['pos_y'], intval($_POST['amount'.$i]), $_SESSION['user']->getUID());

			$added = lib_bl_troops_addNewUnits($unids, $_GET['tid']);
		}

		$atpos = lib_bl_troops_getAtPos($_SESSION['user']->getUID(), $troop['pos_x'], $troop['pos_y'], 'units');
		$unitSmarty->assign('heading', sprintf($lang['addto'], $troop['name']));

		if (is_array($atpos))
		{
			$unitList = array();
			foreach ($atpos as $atpos_part)
			{
				if ($atpos_part['kind'] == 19)
					$name = $lang['daimyo'].' '.$nick;
				else
					$name = $lang['unit'][$atpos_part['kind']];

				$unitList[] = array(
					'name' => $name,
					'unid' => $atpos_part['unid'],
					'count' => $atpos_part['count'],
					'count_formatted' => util\math\numberFormat($atpos_part['count'], 0),
				);
			}

			$unitSmarty->assign('unitList', $unitList);
		}
		if ($added)
			$unitSmarty->assign('changed', $lang['added']);

		$template_file = 'units_move_edit_troop_adjust.tpl';
	}
	elseif ($_GET['do'] == 'remove')
	{
$GLOBALS['firePHP']->log($_POST);
		if ($_POST['remove'])
		{
			for ($i = 0; $i < intval($_POST['count']); $i++)
				if ($_POST['amount'.$i] > 0)
					$unids[$i] = lib_bl_troops_checkComplete(intval($_POST['unid'.$i]), $troop['pos_x'], $troop['pos_y'], intval($_POST['amount'.$i]), $_SESSION['user']->getUID());

			$removed = true;
			foreach ($unids as $unid)
			{
				$r = lib_bl_troops_removeFromTroop($unid);
				if (!$r)
					$removed = false;
			}
		}
		$troopunits = lib_bl_troops_getTroopUnits($_GET['tid']);
		$unitSmarty->assign('heading', sprintf($lang['removefrom'], $troop['name']));

		if (is_array($troopunits))
		{
			$unitList = array();
			foreach ($troopunits as $tu_part)
			{
				if ($tu_part['kind'] == 19)
					$name = $lang['daimyo'].' '.$nick;
				else
					$name = $lang['unit'][$tu_part['kind']];

				$unitList[] = array(
					'name' => $name,
					'unid' => $tu_part['unid'],
					'count' => $tu_part['count'],
					'count_formatted' => util\math\numberFormat($tu_part['count'], 0),
				);
			}

			$unitSmarty->assign('unitList', $unitList);
		}
		if ($removed)
			$unitSmarty->assign('changed', $lang['removed']);

		$template_file = 'units_move_edit_troop_adjust.tpl';
	}
}
elseif ($_GET['mode'] == 'send' || $_GET['mode'] == 'goback')
{
	if ($_GET['mode'] == 'goback')
	{
		$cityexp = explode(':', $city);
		$load = lib_bl_troops_loaded($_GET['tid']);
		lib_bl_troops_sendTroop(intval($_GET['tid']), $cityexp[0], $cityexp[1], 5, $load['res'], $load['amount']);
		$unitSmarty->assign('sent', 1);
		$unitSmarty->assign('textSent', sprintf($lang['sentBack'], $city));
	}

	if ($_POST['send'])
	{
		$errors = array();
		$cityexp = explode(':', $city);
		$value = lib_bl_troops_checkTarget($_POST['tx'], $_POST['ty']);
		if ($value)
		{
			$inClan = lib_bl_troops_checkTargetClan($value, $_SESSION['user']->getCID(), $_POST['movekind']);
			if (!$inClan)
			{
				$capCheck = lib_bl_troops_checkCap(round($_POST['rescount']), $_GET['tid']);
				if ($capCheck)
				{
					if ($_POST['movekind'] == 2)
						lib_bl_resource_addToResources($_POST['resselect'], $_POST['rescount']*-1, $cityexp[0], $cityexp[1]);
					lib_bl_troops_sendTroop($_GET['tid'], $_POST['tx'], $_POST['ty'], $_POST['movekind'], $_POST['resselect'], round($_POST['rescount']));
					$unitSmarty->assign('sent', 1);
					$unitSmarty->assign('textSent', $lang['sent']);
				}
				else
					$errors['capacity'] = 1;
			}
			else
				$errors['clanMember'] = 1;
		}
		else
			$errors['noTarget'] = 1;
	}
	$troop = lib_bl_troops_getTroop(intval($_GET['tid']));
	$GLOBALS['firePHP']->log($troop, 'units_move.php->troop');
	$troopUnits = lib_bl_troops_getTroopUnits(intval($troop['tid']));
	$GLOBALS['firePHP']->log($troopUnits, 'units_move.php->troopUnits');
	$unitSmarty->assign('troop', $troop);
	$unitList = array();
	$sum = 0;
	foreach ($troopUnits as $tu_part)
	{
		if ($tu_part['kind'] == 19)
			$name = $lang['daimyo'].' '.$nick;
		else
			$name = $lang['unit'][$tu_part['kind']];

		$unitList[] = array(
			'name' => $name,
			'count_formatted' => util\math\numberFormat($tu_part['count'], 0),
		);
		$sum += $tu_part['count'];
	}
	$unitSmarty->assign('unitList', $unitList);
	$unitSmarty->assign('sum', util\math\numberFormat($sum, 0));
	$unitSmarty->assign('textCapacity', $lang['cap']);
	$unitSmarty->assign('maxCapacity', array(
		'plain' => lib_bl_troops_maxCapacity(intval($troop['tid']), false),
		'formatted' => lib_bl_troops_maxCapacity(intval($troop['tid']))
	));
	$unitSmarty->assign('textMoveOptions', $lang['moveoptions']);
	$unitSmarty->assign('textPosition', $lang['position']);
	$unitSmarty->assign('textTarget', $lang['target']);
	$unitSmarty->assign('textMoveKind', $lang['movekind']);
	$unitSmarty->assign('textMoveKindsArray', array(
		'defend' => $lang['defend'],
		'transport' => $lang['transport'],
		'attack' => $lang['attack'],
		'robbery' => $lang['robbery'],
	));
	$unitSmarty->assign('canAttack', lib_bl_troops_checkCanAttack());
	$unitSmarty->assign('ressources', array(
		'food' => $lang['food'],
		'wood' => $lang['wood'],
		'rock' => $lang['rock'],
		'iron' => $lang['iron'],
		'paper' => $lang['paper'],
		'koku' => $lang['koku'],
	));
	$unitSmarty->assign('textMax', $lang['max']);
	$unitSmarty->assign('errors', $errors);
	$unitSmarty->assign('textNoUser', $lang['no_user']);
	$unitSmarty->assign('textSameClan', $lang['same_clan']);
	$unitSmarty->assign('textOverCapacity', $lang['overcap']);

	$template_file = 'units_move.tpl';
}
else
{
	$unitSmarty->assign('textUnitMove', $lang['unitmove']);
	$unitSmarty->assign('textCreateTroop', $lang['create_troop']);
	$unitSmarty->assign('textTroops', $lang['troops']);

	if ($_GET['do'] == 'disband')
		lib_bl_troops_deleteTroop($_GET['tid'], $_SESSION['user']->getUID());
	elseif ($_GET['do'] == 'unload')
		$unloaded = lib_bl_troops_unload($_GET['tid'], $_SESSION['user']->getUID(), $lang['lang']);

	$pos = lib_bl_troops_getPos($_SESSION['user']->getUID());
	if (count($pos) > 0)
	{
		$unitSmarty->assign('textOnMoving', $lang['onmoving']);
		$unitSmarty->assign('textComingBack', $lang['comingback']);
		$unitSmarty->assign('textGoBack', sprintf($lang['goback'], $city));
		$unitSmarty->assign('textGoBackDisabled', $lang['goback_dis']);
		$unitSmarty->assign('textUnload', $lang['unload']);
		$unitSmarty->assign('textNoRessources', $lang['no_res']);
		$unitSmarty->assign('textEdit', $lang['edit']);
		$unitSmarty->assign('textDisband', $lang['disband']);
		$positionList = array();
		foreach ($pos as $part)
		{
			$atpos = lib_bl_troops_getAtPos($_SESSION['user']->getUID(), $part['x'], $part['y']);
			$troopList = array();
			foreach ($atpos as $atpos_part)
			{
				$onmoving = lib_bl_troops_checkTroop($atpos_part['tid']);
				$troopList[] = array(
					'tid' => $atpos_part['tid'],
					'onMoving' => $onmoving,
					'name' => $atpos_part['name'],
					'res' => $atpos_part['res'],
					'loaded' => sprintf($lang['loaded'], $atpos_part['amount'], $lang[$atpos_part['res']]),
					'count' => lib_bl_troops_countTroopUnits($atpos_part['tid']),
					'atHome' => ($part['x'].':'.$part['y'] != $city ? 0 : 1),
				);
			}
			$positionList[] = array(
				'x' => $part['x'],
				'y' => $part['y'],
				'troops' => $troopList,
			);
		}
		$unitSmarty->assign('positionList', $positionList);
	}
	else
		$unitSmarty->assign('textNoTroops', $lang['no_troops']);

		$template_file = 'units_move_list.tpl';
	}

$smarty->assign('unitContent', $unitSmarty->fetch($smarty->template_dir[0].$template_file));