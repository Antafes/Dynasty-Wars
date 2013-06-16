{include file="header.tpl"}
<div class="units{if $sub == 'build' xor !$sub} build{/if}">
	<div class="heading">
		{$lang.units}
	</div>
	<div class="topmenu">
		<a href="index.php?chose=units&amp;sub=build">{$lang.build}</a> |
		<a href="index.php?chose=units&amp;sub=move">{$lang.move}</a>
	</div>
	<div>
		{$unitContent}
	</div>
</div>
{include file="footer.tpl"}