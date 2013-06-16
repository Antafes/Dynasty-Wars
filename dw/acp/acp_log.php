<?php
bl\general\loadLanguageFile('log', 'acp');
$smarty->assign('lang', $lang);

$smarty->assign('log', bl\log\prepareEntries($_GET['page'] ? $_GET['page'] : 1));
$smarty->assign('pages', ceil(bl\log\getLogCount() / 20) + 1); //+ 1 needed for smarty's section

$smarty->assign('acpContent', $smarty->fetch('../acp/log.tpl'));