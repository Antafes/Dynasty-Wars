<?php
session_start();
include_once('../config.php');
include_once('../bl/general.ajax.inc.php');
include_once('../bl/login.php');

header('Content-type: text/html');

$con = @mysql_connect($server, $seruser, $serpw);
if ($con)
{
	mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

	$firePHP = FirePHP::getInstance(true);

	if (!$debug || !$firePHP_debug)
		$firePHP->setEnabled(false);
	else
	{
		$firePHP->setEnabled(true);
		$firePHP->registerErrorHandler($throwErrorExceptions=true);
		$firePHP->registerExceptionHandler();
		$firePHP->registerAssertionHandler($convertAssertionErrorsToExceptions=true, $throwAssertionExceptions=false);
	}


	$_SESSION['user'] = new bl\user\UserCls();
	$_SESSION['user']->loadByUID($_SESSION['user']->getUIDFromId($_SESSION['lid']));

	$lang['lang'] = $_SESSION['user']->getLanguage();
	bl\general\loadLanguageFile('map', 'loggedin', true);

	$sql = '
		SELECT
			m.city,
			u.nick,
			m.map_x,
			m.map_y,
			c.clanname,
			c.clantag
		FROM dw_map m
		JOIN dw_user u USING (uid)
		LEFT JOIN dw_clan c USING (cid)
		WHERE m.uid = '.util\mysql\sqlval($_GET['uid']).'
	';
	$data = util\mysql\query($sql);

	$html = '<div style="text-align: center">'.$data['city'].' ['.$data['map_x'].':'.$data['map_y'].']</div>';
	$html .= '<table><tbody><tr>';
	$html .= '<td>'.$lang['owner'].':</td>';
	$html .= '<td>'.$data['nick'].'</td>';
	$html .= '</tr><tr>';
	$html .= '<td>'.$lang['clan'].':</td>';
	$html .= '<td>'.($data['clanname'] ? $data['clanname'].' ['.$data['clantag'].']' : '').'</td>';
	$html .= '</tr></tbody></table>';
}

echo $html;