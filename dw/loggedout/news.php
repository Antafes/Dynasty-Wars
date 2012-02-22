<?php
include ('loggedout/header.php');
lib_bl_general_loadLanguageFile('news', 'loggedout');
//db: auslesen der news
$parser = new wikiparser();
$news_array = lib_util_mysqlQuery('
	SELECT
		n.*,
		u1.nick AS creator,
		u1.email AS creator_email,
		u2.nick AS changer
	FROM dw_news n
	LEFT JOIN dw_user u1 ON (u1.uid = n.uid)
	LEFT JOIN dw_user u2 ON (u2.uid = n.changed_uid)
	ORDER BY nid DESC
', true);

$smarty->assign('heading', htmlentities($lang['news']));

if ($news_array)
{
	$lines = count($news_array);
	$pages = ceil($lines/5);
	$page = $_GET['page'];
	if (!$page)
		$page = 1;
	$news = array();
	for ($i = 0, $n = 5 * $page - 5, $p = 5 * $page; $n < $p && $n < $lines; $n++, $i++)
	{
		$news[$i]['title'] = htmlentities($news_array[$n]['title']);

		if ($news_array[$n]['creator'])
		{
			$news[$i]['nick'] = $news_array[$n]['creator'];
			$news[$i]['email'] = $news_array[$n]['creator_email'];
		}

		$createDate = DWDateTime::createFromFormat('Y-m-d H:i:s', $news_array[$n]['create_datetime']);
		$news[$i]['time'] = $createDate->format($lang['acptimeformat']);
		$news[$i]['text'] = nl2br($parser->parseIt($news_array[$n]['text']));

		if ($news[$n]['changed'])
		{
			if ($news[$n]['changed'] > 1)
				$count = $news[$n]['changed'];
			else
				$count = 'ein';

			$dateChanged = DWDateTime::createFromFormat('Y-m-d H:i:s', $news_array[$n]['changed_datetime']);
			$news[$i]['changed'] = htmlentities(sprintf($lang['newschanged'], $count, $dateChanged->format($lang['timeformat']), $news_array[$n]['changer']));
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