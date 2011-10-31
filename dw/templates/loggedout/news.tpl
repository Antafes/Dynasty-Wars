{include file="header.tpl"}
			<div class="caption">
				{$heading}
			</div>
			{if $news}
			{foreach from=$news item=entry}
			{include file="../news.tpl"}
			{/foreach}
			<div class="news pages">
				{foreach from=$pages key=page item=link}
				<a href="{$link}">{$page}</a>
				{/foreach}
			</div>
			{else}
			<div class="no_news">
				{$no_news}
			</div>
			{/if}
{include file="footer.tpl"}