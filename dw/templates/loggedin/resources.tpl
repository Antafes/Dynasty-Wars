{include file="header.tpl"}
<form method="post" action="index.php?chose=ressources">
	<div class="heading">
		{$heading}
	</div>
	<div class="content">
		<table>
			<tr>
				<td width="140" class="table_tc">&nbsp;</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.food}<br/>
					{$textLevel} {$buildingLevels.paddy}
				</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.wood}<br/>
					{$textLevel} {$buildingLevels.lumberjack}
				</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.rock}<br/>
					{$textLevel} {$buildingLevels.quarry}
				</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.iron}<br/>
					{$textLevel} {$buildingLevels.ironmine}
				</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.paper}<br/>
					{$textLevel} {$buildingLevels.papermill}
				</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.koku}<br/>
					{$textLevel} {$buildingLevels.tradePostHarbour}
				</td>
			</tr>
			{foreach from=$ressourceList item=ressource}
			<tr>
				<td width="140" class="table_tc_red_border">{$ressource.title}</td>
				<td width="84" class="table_tc_red_border">{$ressource.food}</td>
				<td width="84" class="table_tc_red_border">{$ressource.wood}</td>
				<td width="84" class="table_tc_red_border">{$ressource.rock}</td>
				<td width="84" class="table_tc_red_border">{$ressource.iron}</td>
				<td width="84" class="table_tc_red_border">{$ressource.paper}</td>
				<td width="84" class="table_tc_red_border">{$ressource.koku}</td>
			</tr>
			{/foreach}
		</table>
		<div class="paperProduction">
			<div class="heading">{$textPaperProduction}</div>
			{if $textRateChanged}
			<div class="info">
				{$textRateChanged}
			</div>
			{/if}
			<div class="content">
				<div class="row">
					<div class="left">
						{$textWoodCosts}
					</div>
					<div class="right">
						{$woodCosts}
					</div>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="left">
						{$textProductionFactor}
					</div>
					<div class="right">
						{* this function creates the html for a dropdown field *}
						{html_options name=prodfactor options=$productionFactorList selected=$productionFactor}
					</div>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="both">
						<input type="submit" name="changeprod" value="{$textChange}" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</form>
{include file="footer.tpl"}