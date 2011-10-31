{include file="header.tpl"}
			<div class="building{if $notBuildable} add_info{/if}">
				<div class="heading">
					{$buildingName} {$levelInfo} {$level}
				</div>
				<div class="building_pic left{if $buildPlace == 2} food{/if}">
					{$buildingPicture}
				</div>
				<div class="description right{if $buildPlace == 2} food{/if}">
					{$buildingDescription}
				</div>
				{if $notYetBuildable}
				<div class="not_yet_buildable">
					{$notYetBuildable}
				</div>
				{else}
				<form method="post" action="index.php?chose=buildings" name="build">
					<div class="res">
						<strong>{$lvlup}</strong>:
						<table class="no_content" cellspacing="1" cellpadding="0">
							<tr>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/food.png" alt="{$ressources.food_escaped}" title="{$ressources.food_escaped}" />
								</td>
								<td class="res_pics">
									{$buildingRessources.food}
								</td>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/wood.png" alt="{$ressources.wood_escaped}" title="{$ressources.wood_escaped}" />
								</td>
								<td class="res_pics">
									{$buildingRessources.wood}
								</td>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/rock.png" alt="{$ressources.rock_escaped}" title="{$ressources.rock_escaped}" />
								</td>
								<td class="res_pics">
									{$buildingRessources.rock}
								</td>
							</tr>
							<tr>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/iron.png" alt="{$ressources.iron_escaped}" title="{$ressources.iron_escaped}" />
								</td>
								<td class="res_pics">
									{$buildingRessources.iron}
								</td>
								<td class="res_pic">
									<img class="res_pic pic_1" src="pictures/ressources/paper.png" alt="{$ressources.paper_escaped}" title="{$ressources.paper_escaped}" />
								</td>
								<td class="res_pics">
									{$buildingRessources.paper}
								</td>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/koku.png" alt="{$ressources.koku_escaped}" title="{$ressources.koku_escaped}" />
								</td>
								<td class="res_pics">
									{$buildingRessources.koku}
								</td>
							</tr>
						</table>
						<div class="build_button">
							<input type="submit" name="sub_build" value="{$build}"{if $canBuild == 0 || $freeBuildPosition == 0 || $own_uid} disabled="disabled"{/if} />
							<input type="hidden" name="buildplace" value="{$buildPlace}" />
						</div>
						<div class="build_time">
							{$buildTime}
						</div>
					</div>
				</form>
					{if $hasUpgrades}
				<form method="post" action="index.php?chose=buildings" name="upgrade">
					<div class="res">
						<strong>{$upgrade}</strong>:
						<table class="no_content" cellspacing="1" cellpadding="0">
							<tr>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/food.png" alt="{$ressources.food_escaped}" title="{$ressources.food_escaped}" />
								</td>
								<td class="res_pics">
									{$upgradePrices.food}
								</td>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/wood.png" alt="{$ressources.wood_escaped}" title="{$ressources.wood_escaped}" />
								</td>
								<td class="res_pics">
									{$upgradePrices.wood}
								</td>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/rock.png" alt="{$ressources.rock_escaped}" title="{$ressources.rock_escaped}" />
								</td>
								<td class="res_pics">
									{$upgradePrices.rock}
								</td>
							</tr>
							<tr>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/iron.png" alt="{$ressources.iron_escaped}" title="{$ressources.iron_escaped}" />
								</td>
								<td class="res_pics">
									{$upgradePrices.iron}
								</td>
								<td class="res_pic">
									<img class="res_pic pic_1" src="pictures/ressources/paper.png" alt="{$ressources.paper_escaped}" title="{$ressources.paper_escaped}" />
								</td>
								<td class="res_pics">
									{$upgradePrices.paper}
								</td>
								<td class="res_pic">
									<img class="res_pic" src="pictures/ressources/koku.png" alt="{$ressources.koku_escaped}" title="{$ressources.koku_escaped}" />
								</td>
								<td class="res_pics">
									{$upgradePrices.koku}
								</td>
							</tr>
						</table>
						<div class="build_button">
							<input type="submit" name="sub_upgrade" value="{$upgrade}"{if $canUpgrade.resCheck == 0 || $canUpgrade.upgradeCheck == 0 || $freeBuildPosition == 0 || $own_uid} disabled="disabled"{/if} />
							<input type="hidden" name="buildplace" value="{$buildPlace}" />
						</div>
						<div class="build_time">
							{$upgradeTime}
						</div>
					</div>
				</form>
					{/if}
					{if $showDefenseBuildings}
				<div class="defense_buildings">
					<strong>{$defense}</strong>:<br />
						{if $upgradeLevel >= 2}
					<a href="index.php?chose=buildings&amp;buildplace=23">
						{$defensePictures.d23}
					</a>
					<a href="index.php?chose=buildings&amp;buildplace=24">
						{$defensePictures.d24}
					</a>
							{if $upgradeLevel >= 3}
					<a href="index.php?chose=buildings&amp;buildplace=25">
						{$defensePictures.d25}
					</a>
							{/if}
						{else}
					{$noBuild}
						{/if}
				</div>
					{/if}
				{/if}
				<div class="link_back">
					<a href="index.php?chose=buildings">{$back}</a>
				</div>
			</div>
{include file="footer.tpl"}