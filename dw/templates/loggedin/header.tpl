<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$userInfos.lang}" lang="{$userInfos.lang}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="Marian Pollzien" />
<meta http-equiv="cache-control" content="no-cache" />
<meta name="copyright" content="&copy; 2005 - {$smarty.now|date_format:"%Y"} by Neithan" />
<title>{$title}</title>
<link rel="shortcut icon" type="image/x-icon" href="pictures/favicon.ico" />
{include_css}
{include_js}
</head>
<body>
	<div class="wrapper">
		<div class="top_border"></div>
		<div class="left_border left">&nbsp;</div>
		<div class="background">
			<div class="header">
				<img src="pictures/logo.png" alt="{$title}"/>
			</div>
			<div class="menu">
				<div class="fan"></div>
				<div class="no_content">
					<form method="post" action="index.php?chose={$chose}" name='cityform'>
						<select id="cityChange" name="citychange" class="s1">
							{foreach from=$cities item=city}
							<option value="{$city.coords}"{if $userInfos.city == $city.coords} selected="selected"{/if}>
								{$city.city} [{$city.coords}]
							</option>
							{/foreach}
						</select>
					</form>
				</div>
				{$menu}
			</div>
			<div class="content">
				{$special_line}
				<div class="res">
					<table class="no_content" cellspacing="1" cellpadding="0">
						<tr>
							<td class="res_pics">
								<img src="pictures/ressources/food.png" alt="{$ressources.food}" title="{$ressources.food_escaped}" />
							</td>
							<td class="res_pics">
								<img src="pictures/ressources/wood.png" alt="{$ressources.wood}" title="{$ressources.wood_escaped}" />
							</td>
							<td class="res_pics">
								<img src="pictures/ressources/rock.png" alt="{$ressources.rock}" title="{$ressources.rock_escaped}" />
							</td>
							<td class="res_pics">
								<img src="pictures/ressources/iron.png" alt="{$ressources.iron}" title="{$ressources.iron_escaped}" />
							</td>
							<td class="res_pics">
								<img src="pictures/ressources/paper.png" alt="{$ressources.paper}" class="pic_1" title="{$ressources.paper_escaped}" />
							</td>
							<td class="res_pics">
								<img src="pictures/ressources/koku.png" alt="{$ressources.koku}" title="{$ressources.koku_escaped}" />
							</td>
							<td class="storage">
								<img src="pictures/ressources/storage.gif" alt="{$ressources.storage}" title="{$ressources.storage_escaped}" />
							</td>
						</tr>
						<tr>
							<td class="res_pics">
								<span id="dfood"></span>
							</td>
							<td class="res_pics">
								<span id="dwood"></span>
							</td>
							<td class="res_pics">
								<span id="drock"></span>
							</td>
							<td class="res_pics">
								<span id="diron"></span>
							</td>
							<td class="res_pics">
								<span id="dpaper"></span>
							</td>
							<td class="res_pics">
								<span id="dkoku"></span>
							</td>
							<td class="storage">
								{$storage}
							</td>
						</tr>
					</table>
				</div>
<!--
				<table width="660" class="no_content">
					<tr>
						<td width="660" class="box_8">
							<img src="pictures/attack_ani.gif" /> <img src="pictures/defend.png" />
						</td>
					</tr>
				</table>
-->