<?php
include('loggedout/header.php');
bl\general\loadLanguageFile('register', 'loggedout');

unset($err);
//einfuegen der registrierungsfunktionen
include ('lib/bl/register.inc.php');
//regsperre
$closed = mysql_query('SELECT reg_closed FROM dw_game', $con);
if ($closed)
	$reg_closed = mysql_result($closed, 0);

$smarty->assign('heading', htmlentities($lang['registration']));

if (!$reg_closed)
{
//recaptcha
	require_once('lib/bl/recaptchalib.php');
	$publickey = '6LdiZ7sSAAAAABdhMAllJskJ9jutqDx-Zp8KjCsy '; // you got this from the signup page
	$recaptcha_html = recaptcha_get_html($publickey);
//abfrage GET, POST variablen
	$register = $_POST['reg'];
	$nick = $_POST['regnick'];
	$pw = $_POST['regpw'];
	$pww = $_POST['regpww'];
	$email = $_POST['regemail'];
	$city = $_POST['regcity'];
	$regdat = $_POST['regdat'];
	$c = 0;
	if ($nick)
	{
		if (bl\register\checkName($nick, $lang['notusable']))
		{
			$err['name'] = 1;
			unset($nick);
		}
	}
	if ($register)
	{
		$privatekey = '	6LdiZ7sSAAAAAAD1GVsaBKPUGL-qGKI8-qkxNfQZ ';
		$resp = recaptcha_check_answer(
			$privatekey,
			$_SERVER['REMOTE_ADDR'],
			$_POST['recaptcha_challenge_field'],
			$_POST['recaptcha_response_field']
		);

		if ($resp->is_valid)
		{
			$check_email = bl\register\checkMail($email);
			if (!$check_email)
				$err['email'] = 1;
			else
			{
				if ((!$nick && !$err['name']) || !$pw || !$pww || !$email || !$city)
				{
					if (!$nick)
						$err['noname'] = 1;
					if (!$pw)
						$err['nopw'] = 1;
					if (!$pww)
						$err['nopww'] = 1;
					if (!$email)
						$err['noemail'] = 1;
					if (!$city)
						$err['nocity'] = 1;
				}
				else
				{
					$sql = 'SELECT count(*) FROM dw_map WHERE city='.util\mysql\sqlval($city).'';
					$c = util\mysql\query($sql);

					if ($c)
					{
						$err['city'] = 1;
						unset($city);
					}

					if ($pw === $pww)
					{
						$pws = md5($pw);
						$sql = 'SELECT nick FROM dw_user';
						$registeredNicks = util\mysql\query($sql, true);

						foreach ($registeredNicks as $registeredNick)
						{
							if (strcasecmp($nick, $registeredNick) == 0)
							{
								$err['doublename'] = 1;
								unset($nick);
							}

						}

						if ($nick && $pw && $pww && $email && $city)
							$err['registration'] = bl\register\createNewUser($nick, $pws, $email, $city);
					}
					else
						$err['pw<>pww'] = 1;
				}
			}
		}
		else
			$err['wrong_captcha'] = 1;
	}

	if ($err['name'] || $err['city'] || $err['email'] || $err['noname'] || $err['nopw'] || $err['nopww'] || $err['noemail']
			|| $err['nocity'] || $err['doublename'] || $err['registration'] || $err['pw<>pww'] || $err['wrong_captcha'])
	{
		if ($err['noname'] || $err['nopw'] || $err['nopww'] || $err['noemail'] || $err['nocity'])
		{
			$err['missing'] = $lang['missing'];
			if ($err['noname'])
			{
				$err['missing'] = $err['missing'].' '.$lang['name'];
				if ($err['nopw'] || $err['nopww'] || $err['noemail'] || $err['nocity'])
					$err['missing'] = $err['missing'].',';
			}
			if ($err['nopw'])
			{
				$err['missing'] = $err['missing'].' '.$lang['password'];
				if ($err['nopww'] || $err['noemail'] || $err['nocity'])
					$err['missing'] = $err['missing'].',';
			}
			if ($err['nopww'])
			{
				$err['missing'] = $err['missing'].' '.$lang['rep_password'];
				if ($err['noemail'] || $err['nocity'])
					$err['missing'] = $err['missing'].',';
			}
			if ($err['noemail'])
			{
				$err['missing'] = $err['missing'].' '.$lang['email'];
				if ($err['nocity'])
					$err['missing'] = $err['missing'].',';
			}
			if ($err['nocity'])
				$err['missing'] = $err['missing'].' '.$lang['city'];
		}

		$errors = array();

		if ($err['name'])
			$errors['name_blocked'] = htmlentities($lang['nameblocked']);

		if ($err['city'])
			$errors['city_doubled'] = htmlentities($lang['doublecity']);

		if ($err['email'])
			$errors['wrong_mailformat'] = htmlentities($lang['mailformat']);

		if ($err['missing'])
			$errors['missing'] = nl2br(htmlentities($err['missing']));

		if ($err['doublename'])
			$errors['name_doubled'] = htmlentities($lang['doublename']);

		if ($err['pw<>pww'])
			$errors['pw<>pww'] = htmlentities($lang['pw<>pww']);

		if ($err['wrong_captcha'])
			$errors['captcha_wrong'] = htmlentities($lang['captcha_wrong']);

		if ($err['registration'] == 1)
			$errors['registration'] = nl2br(htmlentities($lang['registerok']));
		else
			$errors['registration'] = htmlentities($lang['registerfailed']);

		$smarty->assign('errors', $errors);
	}

	$smarty->assign('name', htmlentities($lang['name']));
	$smarty->assign('entered_nick', htmlentities($nick));
	$smarty->assign('max_length_nick', htmlentities($lang['max20']));
	$smarty->assign('password', htmlentities($lang['password']));
	$smarty->assign('repeat_password', htmlentities($lang['rep_password']));
	$smarty->assign('email', htmlentities($lang['email']));
	$smarty->assign('entered_email', htmlentities($email));
	$smarty->assign('city', htmlentities($lang['city']));
	$smarty->assign('help_alt', htmlentities($lang['help']));
	$smarty->assign('city_description', htmlentities($lang['citydesc']));
	$smarty->assign('entered_city', htmlentities($city));
	$smarty->assign('max_length_city', htmlentities($lang['max20']));
	$smarty->assign('recaptcha', $recaptcha_html);
	$smarty->assign('button_register', htmlentities($lang['register']));
}
elseif ($reg_closed == 1)
	$smarty->assign('reg_closed', htmlentities($lang['noreg']));

include('loggedout/footer.php');

$smarty->display($smarty->template_dir[0].'register.tpl');