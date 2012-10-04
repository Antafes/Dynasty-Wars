{if !$smarty.session.user->getGameRank()}
	{$lang.noadmin|nl2br}
{else}
	{if !$smarty.get.cid}
		{include file='../acp/clanList.tpl'}
	{else}
		{include file='../acp/clanDetails.tpl'}
	{/if}
{/if}