<?php
include ('loggedout/header.php');
lib_bl_general_loadLanguageFile('news', 'loggedout');
//db: auslesen der news
$parser = new wikiparser();
$news_array = lib_util_mysqlQuery('SELECT * FROM dw_news ORDER BY nid DESC', true);

$smarty->assign('heading', htmlentities($lang['news']));

if ($news_array)
{
	$lines = count($news_array);
	$pages = ceil($lines/5);
	$page = $_GET['page'];
	if (!$page)
		$page = 1;
	$news = array();
	for ($i = 0, $n = 5 * $page - 5, $p = 5 * $page; $n < $p and $n < $lines; $n++, $i++)
	{
		$news[$i]['title'] = htmlentities($news_array[$n]['title']);

		//auslesen des nicks und der email des verfassers
		$sql = '
			SELECT
				nick,
				email
			FROM dw_user
			WHERE uid = '.mysql_real_escape_string($news_array[$n]['uid']).'
		';
		$nicks = lib_util_mysqlQuery($sql, true);
		if (count($nicks) > 0)
		{
			$news[$i]['nick'] = $nicks['nick'];
			$news[$i]['email'] = $nicks['email'];
		}
		$news[$i]['time'] = date($lang['acptimeformat'], $news_array[$n]['date']);
		$news[$i]['text'] = nl2br($parser->parseIt($news_array[$n]['text']));

		if ($news[$n]['changed'])
		{
			$changer = lib_util_mysqlQuery('SELECT nick FROM dw_user WHERE uid='.mysql_real_escape_string($news_array[$n]['changed_uid']).'');
			if ($news[$n]['changed'] > 1)
				$count = $news[$n]['changed'];
			else
				$count = 'ein';
			$news[$i]['changed'] = htmlentities(sprintf($lang['newschanged'], $count, date($lang['timeformat'], $news_array[$n]['last_changed']), $changer));
		}
	}

	$pages_array = array();        
	for ($m = 1; $m <= $pages; $m++)
		$pages_array[$m] = 'index.php?chose=news&amp;page='.$m.'';

	$smarty->assign('news_from', $lang['from']);
	$smarty->assign('news', $news);
        if (count($pages_array) > 1) 
            $smarty->assign('pages', $pages_array);
}
else
	$smarty->assign('no_news', htmlentities($lang['nonews']));

include ('loggedout/footer.php');
$smarty->display($smarty->template_dir[0].'news.tpl');