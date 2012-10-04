<?php
include('loggedout/header.php');
include('language/'.$lang['lang'].'/rest/imprint.php');

$smarty->assign('heading', $lang['imprint']);
$smarty->assign('coding', $lang['coding']);
$smarty->assign('graphics', $lang['graphics']);

include('loggedout/footer.php');

$smarty->display($smarty->template_dir[0].'../imprint.tpl');