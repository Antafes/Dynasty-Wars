<?php
include ('loggedout/header.php');
lib_bl_general_loadLanguageFile('home', 'loggedout');
lib_bl_general_loadLanguageFile('news', 'loggedout');
$parser = new wikiparser();

$smarty->assign('heading', $lang['lastnews']);

$news_home = lib_util_mysqlQuery('SELECT * FROM dw_news ORDER BY nid DESC', true);
if ($news_home)
{
	$news = array();
	$news[0]['title'] = htmlentities($news_home[0]['title']);

	$sql = '
		SELECT
			nick,
			email
		FROM dw_user
		WHERE uid = '.$news_home[0]['uid'].'
	';
	$nicks = lib_util_mysqlQuery($sql);
	if (count($nicks) > 0)
	{
		$news[0]['nick'] = $nicks['nick'];
		$news[0]['email'] = $nicks['email'];
	}
	$news[0]['time'] = date($lang['acptimeformat'], $news_home[0]['date']);
	$news[0]['text'] = nl2br($parser->parseIt($news_home[0]['text']));

	if ($news_home[0]['changed'])
	{
		$changer = lib_util_mysqlQuery('SELECT nick FROM dw_user WHERE uid="'.$news_home[0]['changed_uid'].'"');
		if ($news_home[0]['changed'] > 1)
			$count = $news_home[0]['changed'];
		else
			$count = 'ein';
		$news[0]['changed'] = htmlentities(sprintf($lang['newschanged'], $count, date($lang['timeformat'], $news_home[0]['last_changed']), $changer));
	}
	$smarty->assign('news', $news);

	$smarty->assign('more_news', htmlentities($lang['morenews']));

	$smarty->assign('news_from', htmlentities($lang['from']));
}
else
	$smarty->assign('no_news', htmlentities($lang['nonews']));
include ('loggedout/footer.php');

$smarty->display($smarty->template_dir[0].'home.tpl');