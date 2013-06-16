<div class="clanList">
	<table>
		<tr>
			<td class="clanNumber">{$lang.clanNumber}</td>
			<td class="clanName">{$lang.clanName} [{$lang.clanTag}]</td>
		</tr>
		{foreach from=$clanList item=clan}
			<tr>
				<td class="clanNumber">{$clan.cid}</td>
				<td class="clanName">
					<a href="index.php?chose=acp&amp;sub=clanlist&amp;cid={$clan.cid}">
						{$clan.clanname} [{$clan.clantag}]
					</a>
				</td>
			</tr>
		{foreachelse}
			<tr>
				<td colspan="2">{$lang.noClans}</td>
			</tr>
		{/foreach}
	</table>
</div>