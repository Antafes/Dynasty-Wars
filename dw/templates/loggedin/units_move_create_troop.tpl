<div class="subheading">
	{$create_troop|htmlentities}
</div>
{if $troopCreated}
<div class="info">
	{$troopCreated|htmlentities}
</div>
{/if}
<div class="create_troop">
	<div class="row">
		<div class="left bold">
			{$units|htmlentities}
		</div>
		<div class="right bold" style="width: 150px;">
			{$quantity|htmlentities}
		</div>
		<div class="clear"></div>
	</div>
	{if $positionList}
	{foreach from=$positionList item=position}
	<form method="post" name="createform" action="index.php?chose=units&amp;sub=move&amp;mode=create">
		<div class="row">
			<div class="left bold" style="text-align: left; padding-left: 20px;">
				{$position.x}:{$position.y}
				<input type="hidden" name="pos" value="{$position.x}:{$position.y}" />
			</div>
			<div class="clear"></div>
		</div>
		{foreach from=$position.units item=unit key=i}
		<div class="row">
			<div class="left">
				{$unit.name|htmlentities}
			</div>
			<div class="middle">
				<input type="text" name="id{$i}" size="5" value="0" />
				<input type="hidden" name="unid{$i}" value="{$unit.unid}" />
			</div>
			<div class="right">
				<a style="text-decoration: none;" href="javascript:;" onclick="$(this).parents('form').find('input[name=id{$i}]').val({$unit.count});">
					{$unit.count_formatted}
				</a>
			</div>
			<div class="clear"></div>
		</div>
		{/foreach}
		<div class="row">
			<div class="both">
				<input type="submit" name="create" value="{$create|htmlentities}" />
				<input type="hidden" name="ids" value="{$i + 1}" />
			</div>
		</div>
	</form>
	{/foreach}
	{else}
	<div class="row">
		<div class="both">
			{$noUnits|htmlentities}
		</div>
	</div>
	{/if}
	<div class="row">
		<div class="both">
			<a href="index.php?chose=units&amp;sub=move">{$back|htmlentities}</a>
		</div>
	</div>
</div>