<?php
session_start();
include_once('../config.php');

$con = @mysql_connect($server, $seruser, $serpw);
if ($con)
{
	mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

	include_once('../dal/general.ajax.inc.php');

	$item_list = lib_bl_ajax_buildArray($_GET['items']);
	$target = new UserCls();

	$_SESSION['user'] = new UserCls();
	$_SESSION['user']->loadByUID($_SESSION['user']->getUIDFromId($_SESSION['lid']));

	$lang['lang'] = $_SESSION['user']->getLanguage();
	lib_bl_general_loadLanguageFile('general', '', true);
	lib_bl_general_loadLanguageFile('units', 'loggedin', true);

	$error_text = '';
	$error_type = 0;

	foreach ($item_list['errors'] as $key => $value)
		$error_text .= $lang['errors'][$key]."\n";
	if (strlen($error_text) > 0)
	{
		$error_text = substr($error_text, 0, -2);
		$error_type = 1;
	}
	else
	{
		$points = $_SESSION['user']->getPoints();
		$target->loadByUID(lib_dal_user_getUIDFromMapPosition($item_list['target']['tx'], $item_list['target']['ty']));
		$tPoints = $target->getPoints();

		if (($tPoints['unit_points'] + $tPoints['building_points']) < (($tPoints['unit_points'] + $tPoints['building_points']) * .75))
		{
			$error_type = 2;
			$error_text = $lang['points_warning'];
		}
	}

	echo lib_bl_ajax_prepareOutput(array(
		'ok' => true,
		'type' => $error_type,
		'text' => $error_text,
	));
}