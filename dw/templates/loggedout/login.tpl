{include file="header.tpl"}
			<div class="caption">
				{$heading}
			</div>
			{if $login == 1}
			{include file="login_result.tpl"}
			{else}
			{include file="login_form.tpl"}
			{/if}
{include file="footer.tpl"}