{include file="header.tpl"}
<div class="missionary">
	{if $smarty.get.religion == 'accept'}
		{$lang.acceptmsg}
	{else}
		{$lang.declinemsg}
	{/if}
</div>
{include file="footer.tpl"}