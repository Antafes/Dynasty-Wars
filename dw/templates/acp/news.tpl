{if !$smarty.session.user->getGameRank()}
	{$lang.noadmin|htmlentities|nl2br}
{else}
	<div class="subheading">{$lang.news|htmlentities}</div>
	<div class="submenu">
		<a href="index.php?chose=acp&amp;sub=news&amp;nmode=1">{$lang.change|htmlentities}</a> |
		<a href="index.php?chose=acp&amp;sub=news&amp;nmode=2">{$lang.write|htmlentities}</a>
	</div>
	{if !$smarty.get.nmode == 1 || ($smarty.get.nmode == 1 && !$smarty.get.nid)}
		{include file='../acp/newsList.tpl'}
	{else}
		{include file='../acp/newsChange.tpl'}
	{/if}
{/if}