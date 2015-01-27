{include file="header.tpl"}
	<div class="caption">
		{$heading}
	</div>
	{if $news}
		{foreach from=$news item=entry}
			{include file="../news.tpl"}
		{/foreach}
		<div class="text_center_under">
			<a href="index.php?chose=news">{$more_news}</a>
		</div>
	{else}
		<div class="no_news">
			{$no_news}
		</div>
	{/if}
{include file="footer.tpl"}