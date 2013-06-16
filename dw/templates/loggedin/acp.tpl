{include file="header.tpl"}
<div class="heading">{$lang.acp}</div>
<div class="content">
	{if !$own_uid}
	<div class="acp_menu">
		<a href="index.php?chose=acp&amp;sub=userlist" class="a2">{$lang.userlist} ({$userCount})</a> |
		<a href="index.php?chose=acp&amp;sub=clanlist" class="a2">{$lang.clanlist} ({$clanCount})</a>{if $smarty.session.user->getGameRank() == 2} |
		<a href="index.php?chose=acp&amp;sub=log" class="a2">{$lang.actionslog}</a> |
		<a href="index.php?chose=acp&amp;sub=gameoptions" class="a2">{$lang.gameoptions}</a>{/if} |
		<a href="index.php?chose=acp&amp;sub=news" class="a2">{$lang.news}</a>
	</div>
	<div class="acp_content">
		{$acpContent}
	</div>
	{else}
		{$lang.notOwnUID|nl2br}
	{/if}
</div>
{include file="footer.tpl"}