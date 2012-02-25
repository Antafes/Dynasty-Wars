<?php
//session: start
session_start();
//db zugangsdaten
require_once('lib/config.php');
//funktionen
require_once('lib/bl/login.inc.php');
require_once('lib/bl/general.inc.php');
require_once('lib/bl/gameoptions.inc.php');

$smarty = new Smarty();
$GLOBALS['firePHP'] = FirePHP::getInstance(true);

$smarty->debugging = $smarty_debug;

if (!$debug || !$firePHP_debug)
	$GLOBALS['firePHP']->setEnabled(false);
else
{
	$GLOBALS['firePHP']->setEnabled(true);
	$GLOBALS['firePHP']->registerErrorHandler($throwErrorExceptions=true);
	$GLOBALS['firePHP']->registerExceptionHandler();
	$GLOBALS['firePHP']->registerAssertionHandler($convertAssertionErrorsToExceptions=true, $throwAssertionExceptions=false);
}

$smarty->assign('title', 'Dynasty Wars');

//db: connect to database
$con = @mysql_connect($server, $seruser, $serpw);
$lang['lang'] = bl\general\getLanguage();

if (!$con)
{
	bl\general\loadLanguageFile('errors', 'rest');
	require_once('loggedout/header.php');
	echo htmlentities($lang['nodb']).'<br /><a href=\"mailto:admin@dynasty-wars.de\">admin@dynasty-wars.de</a>';
	require_once('loggedout/footer.php');
	exit;
}
elseif ($con)
{
	mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

	$user = new bl\user\UserCls();

	$error = util\mysql\query('SELECT error_report FROM dw_game');
	error_reporting($error);
//abfrage GET, POST variablen
	if ($_SESSION['lid'])
	{
		$user->loadByUID(bl\login\getUIDFromID($_SESSION['lid']));
		$checkid = bl\login\checkID($_SESSION['lid']);
	}
	elseif ($_COOKIE['lid'])
	{
		$user->loadByUID(bl\login\getUIDFromID($_COOKIE['lid']));
		$checkid = bl\login\checkID($_COOKIE['lid']);
	}

	$smarty->assign('chose', $_GET['chose']);
	if ($_POST['language'])
		$lang['lang'] = $_POST['language'];
	$sql = 'SELECT login_closed FROM dw_game';
	$login_closed = util\mysql\query($sql);

	if ($user->getUID() && (($login_closed == 1 && $user->getGameRank() < 1) xor ($login_closed == 2 && $user->getGameRank() != 2) xor !$checkid))
	{
		setcookie('lid', $_COOKIE['lid'], time()-100, '', '.dynasty-wars.de');
		setcookie('city', $city, time()-100, '', '.dynasty-wars.de');
		setcookie('language', $lang['lang'], time()-100, '', '.dynasty-wars.de');
		session_destroy();
	}

	if ($user->getUID())
	{
		$_SESSION['user'] = $user;
		$smarty->setTemplateDir('templates/loggedin/');
		setcookie('lid', $_COOKIE['lid'], time()+604800, '', '.dynasty-wars.de');
		setcookie('city', $_COOKIE['city'], time()+604800, '', '.dynasty-wars.de');
		setcookie('language', $_COOKIE['language'], time()+604800, '', '.dynasty-wars.de');

		if ($_SESSION['own_id'])
			$own_uid = bl\login\getUIDFromID($_SESSION['own_id']);
		elseif ($_COOKIE['own_id'])
			$own_uid = bl\login\getUIDFromID($_COOKIE['own_id']);

		$smarty->assign('own_uid', $own_uid);

		if ($_GET['change'] == 'back')
		{

			$r_city = bl\login\getMainCity($own_uid);

			if ($_COOKIE['lid'])
			{
				setcookie('lid', $_COOKIE['own_id'], time()+604800, '', '.dynasty-wars.de');
				setcookie('city', $r_city, time()+604800, '', '.dynasty-wars.de');
				setcookie('own_id', $own_uid, time()-100, '', '.dynasty-wars.de');
			}
			elseif ($_SESSION['lid'])
			{
				$_SESSION['lid'] = $_SESSION['own_id'];
				$_SESSION['city'] = $r_city;
				unset($_SESSION['own_id']);
			}

			unset($_SESSION['user']);
			bl\general\redirect(util\html\createLink(array('chose' => 'acp'), true));
		}

		if ($_POST['change_user'])
		{

			$r_city = bl\login\getMainCity($_GET['reguid']);
 			$id = bl\login\createID($_GET['reguid']);

			if ($_COOKIE['lid'])
			{
				setcookie('own_id', $_COOKIE['lid'], time()+604800, '', '.dynasty-wars.de');
				setcookie('lid', $id, time()+604800, '', '.dynasty-wars.de');
				setcookie('city', $r_city, time()+604800, '', '.dynasty-wars.de');
			}
			elseif ($_SESSION['lid'])
			{
				$_SESSION['own_id'] = $_SESSION['lid'];
				$_SESSION['lid'] = $id;
				$_SESSION['city'] = $r_city;
			}

			unset($_SESSION['user']);
			bl\general\redirect(util\html\createLink(array('chose' => 'home'), true));
		}

		if ($_POST['language'] && $_SESSION['language'])
		{
			$_SESSION['language'] = $_POST['language'];
			bl\general\redirect(util\html\createLink(array('chose' => 'options'), true));
		}
		elseif ($_POST['language'] && $_COOKIE['language'])
		{
			setcookie('language', $_POST['language'], time()+604800, '', '.dynasty-wars.de');
			bl\general\redirect(util\html\createLink(array('chose' => 'options'), true));
		}

		if ($_GET['chose'] == 'logout')
			require_once ('loggedin/logout.php');
		else
		{
			switch ($_GET['chose'])
			{
				case 'home':
				default:
					if (bl\general\checkMenuEntry('home'))
					{
						require_once ('loggedin/home.php');
						break;
					}
				case 'buildings':
					if (bl\general\checkMenuEntry('buildings'))
					{
						require_once ('loggedin/buildings.php');
						break;
					}
				case 'units':
					if (bl\general\checkMenuEntry('units'))
					{
						require_once ('loggedin/units.php');
						break;
					}
				case 'palace':
					if (bl\general\checkMenuEntry('palace'))
					{
						require_once ('loggedin/palace.php');
						break;
					}
				case 'daimyo':
					if (bl\general\checkMenuEntry('daimyo'))
					{
						require_once ('loggedin/daimyo.php');
						break;
					}
				case 'blacksmith':
					if (bl\general\checkMenuEntry('blacksmith'))
					{
						require_once ('loggedin/blacksmith.php');
						break;
					}
				case 'ressources':
					if (bl\general\checkMenuEntry('ressources'))
					{
						require_once ('loggedin/res.php');
						break;
					}
				case 'market':
					if (bl\general\checkMenuEntry('market'))
					{
						require_once ('loggedin/market.php');
						break;
					}
				case 'clan':
					if (bl\general\checkMenuEntry('clan'))
					{
						require_once ('loggedin/clan.php');
						break;
					}
				case 'map':
					if (bl\general\checkMenuEntry('map'))
					{
						require_once ('loggedin/map.php');
						break;
					}
				case 'messages':
					if (bl\general\checkMenuEntry('messages'))
					{
						require_once ('loggedin/messages.php');
						break;
					}
				case 'ranking':
					if (bl\general\checkMenuEntry('ranking'))
					{
						require_once ('loggedin/ranking.php');
						break;
					}
				case 'tribunal':
					if (bl\general\checkMenuEntry('tribunal'))
					{
						require_once ('loggedin/tribunal.php');
						break;
					}
				case 'news':
					if (bl\general\checkMenuEntry('news'))
					{
						require_once ('loggedin/news.php');
						break;
					}
				case 'options':
					if (bl\general\checkMenuEntry('options'))
					{
						require_once ('loggedin/options.php');
						break;
					}
				case 'acp':
					if (bl\general\checkMenuEntry('acp'))
					{
						require_once ('loggedin/acp.php');
						break;
					}
				case 'usermap':
					if (bl\general\checkMenuEntry('usermap'))
					{
						require_once ('loggedin/userDetails.php');
						break;
					}
				case 'missionary':
					require_once ('loggedin/missionary.php');
					break;
				case 'worldmap':
					require_once ('loggedin/worldmap.php');
					break;
			}
		}
	}
	else
	{
		$smarty->setTemplateDir('templates/loggedout/');
		switch ($_GET['chose']){
			case 'home':
			default:
				require_once ('loggedout/home.php');
				break;
			case 'news':
				require_once ('loggedout/news.php');
				break;
			case 'login':
				require_once ('loggedout/login.php');
				break;
			case 'registration':
				require_once ('loggedout/register.php');
				break;
			case 'imprint':
				require_once ('imprint.php');
				break;
			case 'deluser':
				require_once ('loggedout/del_user.php');
				break;
			case 'activation':
				require_once ('loggedout/activation.php');
				break;
			case 'lost_password':
				require_once ('loggedout/lost_password.php');
				break;
		}
	}
}