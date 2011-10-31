<?php
lib_bl_general_loadLanguageFile('log', 'acp');
$smarty->assign('lang', $lang);

$smarty->assign('log', lib_bl_log_prepareEntries($_GET['page'] ? $_GET['page'] : 1));
$smarty->assign('pages', ceil(lib_bl_log_getLogCount() / 20) + 1); //+ 1 needed for smarty's section

$smarty->assign('acpContent', $smarty->fetch('../acp/log.tpl'));