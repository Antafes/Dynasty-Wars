{include file="header.tpl"}
<div class="heading">{$lang.profileFrom|sprintf:$registeredUser->getNick()|htmlentities}</div>
<div class="userDetails">
	<table>
		<tr>
			<th>{$lang.playsSince|htmlentities}:</th>
			<td>{$registeredUser->getRegDate()}</td>
		</tr>
		{assign var='mainCity' value=$registeredUser->getMainCity()}
		<tr>
			<th>{$lang.position|htmlentities}:</th>
			<td>
				<a href="index.php?chose=map&amp;x={$mainCity.map_x}&amp;y={$mainCity.map_y}">[{$mainCity.map_x}:{$mainCity.map_y}] {$mainCity.city|htmlentities}</a>
			</td>
		</tr>
		{assign var='points' value=$registeredUser->getPoints()}
		<tr>
			<th>{$lang.points|htmlentities}</th>
			<td>{$points.unit_points + $points.building_points}</td>
		</tr>
		{if $registeredUser->getCID()}
		<tr>
			<th>{$lang.clan|htmlentities}:</th>
			<td>
				<a href="index.php?chose=clan&amp;cid={$registeredUser->getCID()}">{$clan.clanname|htmlentities} [{$clan.clantag|htmlentities}]</a>
			</td>
		</tr>
		{/if}
		{if $registeredUser->getDescription()}
		<tr>
			<th style="vertical-align: top;">{$lang.description|htmlentities}:</th>
			<td>{$registeredUser->getDescription()|htmlentities|nl2br}</td>
		</tr>
		{/if}
	</table>
	<div class="backlink">
		<a href="index.php?chose={if $fromc}{$fromc}{else}home{/if}">{$lang.back|htmlentities}</a>
	</div>
</div>
{include file="footer.tpl"}