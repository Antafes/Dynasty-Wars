<?php
include("loggedin/header.php");

bl\general\loadLanguageFile('missionary');

$smarty->assign('lang', $lang);

$religion = $_GET["religion"];
$ruid = $_GET["uid"];
if (!$ruid || !$religion)
	bl\general\redirect('index.php?chose=home');
else
{
	if ($religion == "accept")
		$_SESSION['user']->setReligion();

	$sql = '
		DELETE FROM dw_missionary
		WHERE uid='.\util\mysql\sqlval($_SESSION['user']->getUID()).'
	';
	util\mysql\query($sql);
}

include("loggedin/footer.php");

$smarty->display('missionary.tpl');