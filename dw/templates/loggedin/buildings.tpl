{include file="header.tpl"}
			<div class="heading">{$buildings}</div>
			<div class="buildings" style="background-image: url({$cityBackground})">
				{section name=buildplaces start=1 loop=$maxBuildplaces step=1}
				<div class="buildplace" style="top: {$buildingPositions[$smarty.section.buildplaces.index].top}px; left: {$buildingPositions[$smarty.section.buildplaces.index].left}px;">
					<a id="place{$smarty.section.buildplaces.index}" href="index.php?chose=buildings&amp;buildplace={$smarty.section.buildplaces.index}">
						{$buildingPictures[$smarty.section.buildplaces.index]}
					</a>
				</div>
				{/section}
				<div id="build_dialog">
					<div class="heading"></div>
					<div class="building_pic left"></div>
					<div class="description right"></div>
					<div class="not_yet_buildable">
						{$notYetBuildable}
					</div>
					<form id="build_form" method="post" action="index.php?chose=buildings" name="build">
						<div class="res build">
							<strong>{$lvlup}</strong>:
							<table class="no_content" cellspacing="1" cellpadding="0">
								<tr>
									<td class="res_pic">
										<img class="res_pic" src="pictures/ressources/food.png" alt="{$ressources.food_escaped}" title="{$ressources.food_escaped}" />
									</td>
									<td class="res_pics food"></td>
									<td class="res_pic">
										<img class="res_pic" src="pictures/ressources/wood.png" alt="{$ressources.wood_escaped}" title="{$ressources.wood_escaped}" />
									</td>
									<td class="res_pics wood"></td>
									<td class="res_pic">
										<img class="res_pic" src="pictures/ressources/rock.png" alt="{$ressources.rock_escaped}" title="{$ressources.rock_escaped}" />
									</td>
									<td class="res_pics rock"></td>
								</tr>
								<tr>
									<td class="res_pic">
										<img class="res_pic" src="pictures/ressources/iron.png" alt="{$ressources.iron_escaped}" title="{$ressources.iron_escaped}" />
									</td>
									<td class="res_pics iron"></td>
									<td class="res_pic">
										<img class="res_pic pic_1" src="pictures/ressources/paper.png" alt="{$ressources.paper_escaped}" title="{$ressources.paper_escaped}" />
									</td>
									<td class="res_pics paper"></td>
									<td class="res_pic">
										<img class="res_pic" src="pictures/ressources/koku.png" alt="{$ressources.koku_escaped}" title="{$ressources.koku_escaped}" />
									</td>
									<td class="res_pics koku"></td>
								</tr>
							</table>
							<div class="build_button">
								<input type="submit" value="{$build}" />
							</div>
							<div class="build_time"></div>
						</div>
						<div class="res upgrade">
							<strong>{$lvlup}</strong>:
							<table class="no_content" cellspacing="1" cellpadding="0">
								<tr>
									<td class="res_pic">
										<img class="res_pic" src="pictures/ressources/food.png" alt="{$ressources.food_escaped}" title="{$ressources.food_escaped}" />
									</td>
									<td class="res_pics food"></td>
									<td class="res_pic">
										<img class="res_pic" src="pictures/ressources/wood.png" alt="{$ressources.wood_escaped}" title="{$ressources.wood_escaped}" />
									</td>
									<td class="res_pics wood"></td>
									<td class="res_pic">
										<img class="res_pic" src="pictures/ressources/rock.png" alt="{$ressources.rock_escaped}" title="{$ressources.rock_escaped}" />
									</td>
									<td class="res_pics rock"></td>
								</tr>
								<tr>
									<td class="res_pic">
										<img class="res_pic" src="pictures/ressources/iron.png" alt="{$ressources.iron_escaped}" title="{$ressources.iron_escaped}" />
									</td>
									<td class="res_pics iron"></td>
									<td class="res_pic">
										<img class="res_pic pic_1" src="pictures/ressources/paper.png" alt="{$ressources.paper_escaped}" title="{$ressources.paper_escaped}" />
									</td>
									<td class="res_pics paper"></td>
									<td class="res_pic">
										<img class="res_pic" src="pictures/ressources/koku.png" alt="{$ressources.koku_escaped}" title="{$ressources.koku_escaped}" />
									</td>
									<td class="res_pics koku"></td>
								</tr>
							</table>
							<div class="build_button">
								<input type="submit" value="{$build}" />
							</div>
							<div class="build_time"></div>
						</div>
					</form>
				</div>
			</div>
			{if $isBuilding}
			<div class="build_list">
				<div class="heading">
					{$buildList}
				</div>
				<div class="content">
					{foreach from=$isBuilding item='build'}
						{if ($build.kind >= 1 && $build.kind <= 6) || $build.kind == 22}
							{assign var="class" value="resource"}
						{elseif $build.kind >= 7 && $build.kind <= 21}
							{assign var="class" value="standard"}
						{elseif $build.kind >= 23 && $build.kind <= 25}
							{assign var="class" value="defense"}
						{/if}
					<div class="row {$class}">
						<a class="a1" href="index.php?chose=buildings&amp;buildplace={$build.position}">
							{$buildItems[$build.kind]}:
						</a>
						<strong><span id="b{$build.bid}"></span></strong>
					</div>
					{/foreach}
				</div>
				<div id="build_list_dummy" class="row">
					<a class="a1" href="index.php?chose=buildings&amp;buildplace="></a>
					<strong><span></span></strong>
				</div>
			</div>
			{/if}
{include file="footer.tpl"}