<?php
include("loggedin/header.php");

lib_bl_general_loadLanguageFile('userDetails');
$smarty->assign('lang', $lang);

if ($_GET["fromc"])
{
	$fromc = $_GET["fromc"];
	$fromc = urldecode($fromc);
	$parts = explode("§", $fromc);

	unset($fromc);
	for ($n = 0; $n < count($parts); $n++)
	{
		$fromc .= $parts[$n];
		if ($n < count($parts) - 1)
			$fromc .= '&amp;';
	}

	$smarty->assign('fromc', $fromc);
}

$registeredUser = new UserCls();
$registeredUser->loadByUID($_GET['reguid']);
$smarty->assign('registeredUser', $registeredUser);
$sql = '
	SELECT
		clanname,
		clantag
	FROM dw_clan
	WHERE cid = '.mysql_real_escape_string($registeredUser->getCID()).'
';
$smarty->assign('clan', util\mysql\query($sql));

include("loggedin/footer.php");

$smarty->display('userDetails.tpl');