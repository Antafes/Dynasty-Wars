{include file="header.tpl"}
			<div class="heading">{$buildings}</div>
			<div class="buildings" style="background-image: url({$cityBackground})">
				{section name=buildplaces start=1 loop=$maxBuildplaces step=1}
				<div class="buildplace" style="top: {$buildingPositions[$smarty.section.buildplaces.index].top}px; left: {$buildingPositions[$smarty.section.buildplaces.index].left}px;">
					<a id="place{$smarty.section.buildplaces.index}" href="javascript:;">
						{$buildingPictures[$smarty.section.buildplaces.index]}
					</a>
				</div>
				{/section}
				<div id="build_dialog" class="build_dialog">
					<div class="close"><a href="javascript:;">{$lang.close}</a></div>
					<div class="heading"></div>
					<div class="not_yet_buildable">
						{$lang.notYetBuildable}
					</div>
					<div class="building_pic left"></div>
					<div class="description right"></div>
					<div class="clear"></div>
					<form method="post" action="index.php?chose=buildings" name="build">
						<div class="res build">
							<strong>{$lang.levelUp}</strong>:
							<table class="no_content" cellspacing="1" cellpadding="0">
								<tbody>
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
								</tbody>
							</table>
							<div class="build_button">
								<input type="submit" value="{$lang.build}" />
							</div>
							<div class="build_time"></div>
						</div>
						<div class="res upgrade">
							<strong>{$lang.upgrade}</strong>:
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
								<input type="submit" value="{$lang.build}" />
							</div>
							<div class="build_time"></div>
						</div>
					</form>
					<div class="defense_buildings">
						<strong>{$lang.defense}</strong>:<br />
					</div>
				</div>
			</div>
			<div class="build_list"{if $buildItems} style="display: block;"{/if}>
				<div class="heading">
					{$lang.buildList}
				</div>
				<div class="content">
					{foreach from=$buildItems item='build' key='kind'}
						{if ($kind >= 1 && $kind <= 6) || $kind == 22}
							{assign var="class" value="resource"}
						{elseif $kind >= 7 && $kind <= 21}
							{assign var="class" value="standard"}
						{elseif $kind >= 23 && $kind <= 25}
							{assign var="class" value="defense"}
						{/if}
					<div class="row {$class}">
						<span>{$build.name}:</span>
						<strong><span id="b{$build.bid}"></span></strong>
					</div>
					{/foreach}
				</div>
				<div id="build_list_dummy" class="row">
					<span></span>
					<strong><span></span></strong>
				</div>
			</div>
{include file="footer.tpl"}