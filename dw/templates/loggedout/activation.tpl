{include file="header.tpl"}
<div class="activation">
	{if $errors}
	{foreach from=$errors key=langKey item=value}
	{$lang.$langkey}<br />
	{/foreach}
	{/if}
	{if !$smarty.request.id || $errors}
	<form method="post" action="index.php?chose=activation">
		{$lang.enterid}<br />
		<input type="text" name="id" maxlength="20" />
		<input type="submit" value="{$lang.activate}" />
	</form>
	{/if}
</div>
{include file="footer.tpl"}