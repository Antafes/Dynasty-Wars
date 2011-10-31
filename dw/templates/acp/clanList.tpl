<div class="clanList">
	<table>
		<tr>
			<td class="clanNumber">{$lang.clanNumber|htmlentities}</td>
			<td class="clanName">{$lang.clanName|htmlentities} [{$lang.clanTag|htmlentities}]</td>
		</tr>
		{foreach from=$clanList item=clan}
			<tr>
				<td class="clanNumber">{$clan.cid}</td>
				<td class="clanName">
					<a href="index.php?chose=acp&amp;sub=clanlist&amp;cid={$clan.cid}">
						{$clan.clanname|htmlentities} [{$clan.clantag|htmlentities}]
					</a>
				</td>
			</tr>
		{foreachelse}
			<tr>
				<td colspan="2">{$lang.noClans|htmlentities}</td>
			</tr>
		{/foreach}
	</table>
</div>