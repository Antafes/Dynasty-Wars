<div class="subheading">
	{$unitBuild}
</div>
{if !$check}
<div class="no_build">
	{$noBuild}
</div>
{else}
<div class="unit_list">
	{foreach from=$unitList item=unit}
	<form method="post" action="index.php?chose=units&amp;sub=build&amp;unit={$unit.kind}">
		<div class="left unit_image">
			<img src="pictures/units/{$unit.picture}.png" alt="{$unit.name}" />
		</div>
		<div class="left unit_description">
			<div class="unit_name">
				<strong>{$unit.name}</strong> ({$unit.count})
			</div>
			<div class="unit_description">
				{$unit.description|nl2br}
			</div>
			<div class="build">
				<div class=" left unit_price">
					<div class="left">{$ressources.food}: {$unit.price.food_formatted}</div>
					<div class="left">{$ressources.wood}: {$unit.price.wood_formatted}</div>
					<div class="left">{$ressources.rock}: {$unit.price.rock_formatted}</div>
					<div class="clear"></div>
					<div class="left">{$ressources.iron}: {$unit.price.iron_formatted}</div>
					<div class="left">{$ressources.paper}: {$unit.price.paper_formatted}</div>
					<div class="left">{$ressources.koku}: {$unit.price.koku_formatted}</div>
					<div class="clear"></div>
				</div>
				<div class=" left build_button">
					{if $trainCheck.ok}
						{if $unit.unitAmount}
							{$unit.unitAmount} {$lang.units}<br />
						{/if}
						<span id="{$unit.kind}"></span>
					{else}
					<input type="text" name="count" size="4" value="{$unit.maxBuildable}" />
					<input type="submit" name="build" value="{$build}"{if !$unit.check || $own_uid} disabled="disabled"{/if} /><br />
					<strong>{$buildTime}:</strong> {$unit.buildTime}
					{/if}
				</div>
				<div class="clear"></div>
			</div>
			<div class="unit_capacity">
				{$capacity}: {$unit.capacity}
			</div>
		</div>
		<div class="clear"></div>
	</form>
	{/foreach}
</div>
{/if}