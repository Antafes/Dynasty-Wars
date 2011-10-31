<?php
include('loggedout/header.php');
include('language/'.$lang['lang'].'/rest/imprint.php');

$smarty->assign('heading', htmlentities($lang['imprint']));
$smarty->assign('coding', htmlentities($lang['coding']));
$smarty->assign('graphics', htmlentities($lang['graphics']));

include('loggedout/footer.php');

$smarty->display($smarty->template_dir[0].'../imprint.tpl');