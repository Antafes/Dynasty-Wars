{include file="header.tpl"}
			<div class="heading">{$buildings}</div>
			<div class="buildings" style="background-image: url({$cityBackground})">
				{section name=buildplaces start=1 loop=$maxBuildplaces step=1}
				<div class="buildplace" style="top: {$buildingPositions[$smarty.section.buildplaces.index].top}px; left: {$buildingPositions[$smarty.section.buildplaces.index].left}px;">
					<a href="index.php?chose=buildings&amp;buildplace={$smarty.section.buildplaces.index}">
						{$buildingPictures[$smarty.section.buildplaces.index]}
					</a>
				</div>
				{/section}
			</div>
			{if $isBuilding}
			<div class="build_list">
				<div class="heading">
					{$buildList}
				</div>
				<div class="content">
					{foreach from=$isBuilding item=build}
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
			</div>
			{/if}
{include file="footer.tpl"}