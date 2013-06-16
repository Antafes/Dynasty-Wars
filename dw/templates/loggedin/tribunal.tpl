{include file="header.tpl"}
<div class="tribunal">
	<div class="heading">{$lang.tribunal}</div>
	<div class="submenu">
		<a href="index.php?chose=tribunal&amp;sub=hearings">{$lang.hearings}</a> |
		<a href="index.php?chose=tribunal&amp;sub=newhearing">{$lang.new_hearing}</a> |
		<a href="index.php?chose=tribunal&amp;sub=rules">{$lang.rules}</a>
	</div>
	<div class="inner_content">
		{if !$smarty.get.sub || $smarty.get.sub == 'hearings'}
			{if !$smarty.get.id}
				{include file='tribunalHearings.tpl'}
			{else}
				{include file='tribunalHearing.tpl'}
			{/if}
		{elseif $smarty.get.sub == 'newhearing'}
			{include file='tribunalNewHearing.tpl'}
		{elseif $smarty.get.sub == 'rules'}
			<div class="rules" id="rules">
				<div class="subheading">{$lang.rules}</div>
				{if ($smarty.session.user->getGameRank() == 2 || $smarty.session.user->getGameRank() == 3) && !$own_uid}
					<div class="sub_menu">
						<a href="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=show{if $smarty.get.languages}&amp;languages={$smarty.get.languages}{/if}">{$lang.show_rules}</a> |
						<a href="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change{if $smarty.get.languages}&amp;languages={$smarty.get.languages}{/if}">{$lang.change_rules}</a>
						<br />
						<form method="get" action="index.php" name="change_lang">
							<input type="hidden" name="chose" value="{$smarty.get.chose}" />
							<input type="hidden" name="sub" value="{$smarty.get.sub}" />
							<input type="hidden" name="rules_sub" value="{$smarty.get.rules_sub}" />
							{$lang.languages}:
							<select name="languages" onchange="form.submit();">
								{html_options options=$languages selected=$smarty.get.languages}
							</select>
						</form>
					</div>
				{/if}
				{if !$smarty.get.rules_sub || $smarty.get.rules_sub == 'show'}
					{include file='tribunalRuleList.tpl'}
				{else}
					{include file='tribunalRuleChange.tpl'}
				{/if}
			</div>
		{/if}
	</div>
</div>
{include file="footer.tpl"}