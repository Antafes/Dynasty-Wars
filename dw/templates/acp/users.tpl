{if !$smarty.session.user->getGameRank()}
	{$lang.noadmin|nl2br}
{else}
	{if !$smarty.get.reguid}
		{include file='../acp/userList.tpl'}
	{else}
		{include file='../acp/userDetails.tpl'}
	{/if}
{/if}