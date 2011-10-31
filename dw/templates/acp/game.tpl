{if $smarty.session.user->getGameRank() != 2}
	{$lang.noadmin|htmlentities|nl2br}
{else}
	<div class="subheading">{$lang.gameOptions|htmlentities}</div>
	<div class="submenu">
		<a href="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=common">{$lang.common|htmlentities}</a>
		<a href="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=menu">{$lang.gameMenu|htmlentities}</a>
	</div>
	{if $smarty.get.gameOptionsSub == 'common' || !$smarty.get.gameOptionsSub}
		{include file='../acp/gameOptions.tpl'}
	{elseif $smarty.get.gameOptionsSub == 'menu'}
		{include file='../acp/gameMenu.tpl'}
	{/if}
{/if}