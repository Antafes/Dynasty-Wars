{include file="header.tpl"}
			<div class="building{if $buildablesCount <= 0} add_info{/if}">
				<div class="heading">
					{$newBuilding}
				</div>
				{if $buildablesCount > 0}
					{foreach from=$buildingsList item=building}
				<form method="post" action="index.php?chose=buildings" name="build_{$building.kind}">
					<div class="building_pic left">
						{$building.image}
					</div>
					<strong>{$building.name}</strong>
					<div class="res">
						<strong>{$lvlUp}</strong>:
						<table class="no_content" cellspacing="1" cellpadding="0">
							<tr>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/food.png" alt="{$ressources.food_escaped}" title="{$ressources.food_escaped}" />
								</td>
								<td class="res_pics">
									{$building.prices.food}
								</td>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/wood.png" alt="{$ressources.wood_escaped}" title="{$ressources.wood_escaped}" />
								</td>
								<td class="res_pics">
									{$building.prices.wood}
								</td>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/rock.png" alt="{$ressources.rock_escaped}" title="{$ressources.rock_escaped}" />
								</td>
								<td class="res_pics">
									{$building.prices.rock}
								</td>
							</tr>
							<tr>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/iron.png" alt="{$ressources.iron_escaped}" title="{$ressources.iron_escaped}" />
								</td>
								<td class="res_pics">
									{$building.prices.iron}
								</td>
								<td class="res_pic">
									<img class="res_pic pic_1" src="pictures/ressources/paper.png" alt="{$ressources.paper_escaped}" title="{$ressources.paper_escaped}" />
								</td>
								<td class="res_pics">
									{$building.prices.paper}
								</td>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/koku.png" alt="{$ressources.koku_escaped}" title="{$ressources.koku_escaped}" />
								</td>
								<td class="res_pics">
									{$building.prices.koku}
								</td>
							</tr>
						</table>
						<div class="build_button">
							<input type="submit" name="sub_build" value="{$build}"{if $building.canBuild == 0 || $building.freeBuildPosition == 0 || $own_uid} disabled="disabled"{/if} />
							<input type="hidden" name="kind" value="{$building.kind}" />
							<input type="hidden" name="buildplace" value="{$smarty.get.buildplace}" />
						</div>
						<div class="build_time">
							{$building.time}
						</div>
					</div>
					</form>
					{/foreach}
				{else}
					{$noBuilding}
				{/if}
				<div class="link_back">
					<a href="index.php?chose=buildings">{$back}</a>
				</div>
			</div>
{include file="footer.tpl"}