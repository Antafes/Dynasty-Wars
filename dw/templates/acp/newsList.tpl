<div class="subheading">{$lang.changeNews|htmlentities}</div>
<div class="newsList">
	{foreach from=$newsEntries item=newsEntry}
		<div class="row">
			<a href="index.php?chose=acp&amp;sub=news&amp;nmode=1&amp;nid={$newsEntry.nid}">{$newsEntry.nick|htmlentities} - {$newsEntry.title|htmlentities}</a>
		</div>
	{foreachelse}
		{$lang.noEntries|htmlentities}
	{/foreach}
</div>