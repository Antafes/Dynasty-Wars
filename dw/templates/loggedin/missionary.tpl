{include file="header.tpl"}
<div class="missionary">
	{if $smarty.get.religion == 'accept'}
		{$lang.acceptmsg|htmlentities}
	{else}
		{$lang.declinemsg|htmlentities}
	{/if}
</div>
{include file="footer.tpl"}