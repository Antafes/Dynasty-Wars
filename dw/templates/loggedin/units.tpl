{include file="header.tpl"}
<div class="units{if $sub == 'build' xor !$sub} build{/if}">
	<div class="heading">
		{$lang.units|htmlentities}
	</div>
	<div class="topmenu">
		<a href="index.php?chose=units&amp;sub=build">{$lang.build|htmlentities}</a> |
		<a href="index.php?chose=units&amp;sub=move">{$lang.move|htmlentities}</a>
	</div>
	<div>
		{$unitContent}
	</div>
</div>
{include file="footer.tpl"}