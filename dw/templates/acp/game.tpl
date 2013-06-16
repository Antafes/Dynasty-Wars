{if $smarty.session.user->getGameRank() != 2}
	{$lang.noadmin|nl2br}
{else}
	<div class="subheading">{$lang.gameOptions}</div>
	<div class="submenu">
		<a href="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=common">{$lang.common}</a>
		<a href="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=menu">{$lang.gameMenu}</a>
	</div>
	{if $smarty.get.gameOptionsSub == 'common' || !$smarty.get.gameOptionsSub}
		{include file='../acp/gameOptions.tpl'}
	{elseif $smarty.get.gameOptionsSub == 'menu'}
		{include file='../acp/gameMenu.tpl'}
	{/if}
{/if}