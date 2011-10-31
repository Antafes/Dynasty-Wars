<form method="post" name="addform" action="index.php?chose=units&amp;sub=move&amp;mode=edit&amp;tid={$smarty.get.tid}&amp;do={$smarty.get.do}">
	<div class="subheading">
		{$heading|htmlentities}
	</div>
	{if $unitList}
	<div class="edit_unit add">
		<div class="row">
			<div class="left bold">
				{$units|htmlentities}
			</div>
			<div class="middle bold">
				{$quantity|htmlentities}
			</div>
			<div class="right bold">
				{$maxCount|htmlentities}
			</div>
			<div class="clear"></div>
		</div>
		{counter start=0 assign=i}
		{foreach from=$unitList item=unit}
		<div class="row">
			<div class="left">
				{$unit.name|htmlentities}
			</div>
			<div class="middle">
				<input type="text" name="amount{$i}" id="unit_amount{$i}" value="0" size="5" />
				<input type="hidden" name="unid{$i}" value="{$unit.unid}" />
			</div>
			<div class="right">
				<a style="text-decoration: none;" href="javascript:;" onclick="$('#unit_amount{$i}').val({$unit.count});">
					{$unit.count_formatted}
				</a>
			</div>
			<div class="clear"></div>
		</div>
		{counter}
		{/foreach}
		{if $changed}
		<div class="row">
			<div class="both">
				{$changed|htmlentities}
			</div>
		</div>
		{/if}
		<div class="row">
			<div class="both">
				<input type="submit" name="{$smarty.get.do}" value="{if $smarty.get.do == 'remove'}{$lang.remove|htmlentities}{else}{$lang.add|htmlentities}{/if}" />
				<input type="hidden" name="count" value="{$i}" />
			</div>
		</div>
	</div>
	{else}
	<div class="edit_unit add">
		<div class="row">
			<div class="both">
				{$noUnits|htmlentities}
			</div>
		</div>
	</div>
	{/if}
	<div class="row">
		<div class="both">
			<a href="index.php?chose=units&amp;sub=move&amp;mode=edit&amp;tid={$smarty.get.tid}">{$back|htmlentities}</a>
		</div>
	</div>
</form>