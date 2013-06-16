{include file="header.tpl"}
<div class="heading">{$lang.profileFrom|sprintf:$registeredUser->getNick()}</div>
<div class="userDetails">
	<table>
		<tr>
			<th>{$lang.playsSince}:</th>
			<td>{$registeredUser->getRegDate()}</td>
		</tr>
		{assign var='mainCity' value=$registeredUser->getMainCity()}
		<tr>
			<th>{$lang.position}:</th>
			<td>
				<a href="index.php?chose=map&amp;x={$mainCity.map_x}&amp;y={$mainCity.map_y}">[{$mainCity.map_x}:{$mainCity.map_y}] {$mainCity.city}</a>
			</td>
		</tr>
		{assign var='points' value=$registeredUser->getPoints()}
		<tr>
			<th>{$lang.points}</th>
			<td>{$points.unit_points + $points.building_points}</td>
		</tr>
		{if $registeredUser->getCID()}
		<tr>
			<th>{$lang.clan}:</th>
			<td>
				<a href="index.php?chose=clan&amp;cid={$registeredUser->getCID()}">{$clan.clanname} [{$clan.clantag}]</a>
			</td>
		</tr>
		{/if}
		{if $registeredUser->getDescription()}
		<tr>
			<th style="vertical-align: top;">{$lang.description}:</th>
			<td>{$registeredUser->getDescription()|nl2br}</td>
		</tr>
		{/if}
	</table>
	<div class="backlink">
		<a href="index.php?chose={if $fromc}{$fromc}{else}home{/if}">{$lang.back}</a>
	</div>
</div>
{include file="footer.tpl"}