{include file="header.tpl"}
<form method="post" action="index.php?chose=ressources">
	<div class="heading">
		{$heading|htmlentities}
	</div>
	<div class="content">
		<table>
			<tr>
				<td width="140" class="table_tc">&nbsp;</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.food|htmlentities}<br/>
					{$textLevel|htmlentities} {$buildingLevels.ricefield}
				</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.wood|htmlentities}<br/>
					{$textLevel|htmlentities} {$buildingLevels.lumberjack}
				</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.rock|htmlentities}<br/>
					{$textLevel|htmlentities} {$buildingLevels.quarry}
				</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.iron|htmlentities}<br/>
					{$textLevel|htmlentities} {$buildingLevels.ironmine}
				</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.paper|htmlentities}<br/>
					{$textLevel|htmlentities} {$buildingLevels.papermill}
				</td>
				<td width="84" class="table_tc_red_border">
					{$ressources.koku|htmlentities}<br/>
					{$textLevel|htmlentities} {$buildingLevels.tradePostHarbour}
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
			<div class="heading">{$textPaperProduction|htmlentities}</div>
			{if $textRateChanged}
			<div class="info">
				{$textRateChanged|htmlentities}
			</div>
			{/if}
			<div class="content">
				<div class="row">
					<div class="left">
						{$textWoodCosts|htmlentities}
					</div>
					<div class="right">
						{$woodCosts}
					</div>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="left">
						{$textProductionFactor|htmlentities}
					</div>
					<div class="right">
						{* this function creates the html for a dropdown field *}
						{html_options name=prodfactor options=$productionFactorList selected=$productionFactor}
					</div>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="both">
						<input type="submit" name="changeprod" value="{$textChange|htmlentities}" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</form>
{include file="footer.tpl"}