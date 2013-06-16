<div class="clanDetails">
{if $smarty.get.umode}
	<div class="subheading">{$lang.memberList}</div>
	<table>
		<tr>
			<th></th>
			<th>{$lang.name}</th>
			<th>{$lang.rank}</th>
		</tr>
		{foreach from=$memberList item=member name=feMemberList}
			<tr>
				<td>{$smarty.foreach.feMemberList.iteration}</td>
				<td>
					<a href="index.php?chose=acp&amp;sub=userlist&amp;reguid={$member.uid}">{$member.nick}</a>
					{if $member.blocked}(g){/if}
				</td>
				<td>{$member.rankname}</td>
			</tr>
		{/foreach}
	</table>
	<div class="backlink">
		<a href="index.php?chose=acp&amp;sub=clanlist&amp;cid={$smarty.get.cid}">{$lang.back}</a>
	</div>
{else}
	<div class="subheading">{$clan.clanname} [{$clan.clantag}]</div>
	<table>
		<tr>
			<th>{$lang.clanFounder}:</th>
			<td>{$clan.founder}</td>
			<td>
				<a href="index.php?chose=acp&amp;sub=clanlist&amp;cid={$smarty.get.cid}&amp;umode=1">{$lang.memberList}</a>
			</td>
		</tr>
		<tr>
			<th>{$lang.members}:</th>
			<td colspan="2">{$clan.memberCount}</td>
		</tr>
		<tr>
			<th>{$lang.points}:</th>
			<td>{$clan.points|number_format:0:',':'.'}</td>
		</tr>
	</table>
	<div class="clanDescription">
		<strong>{$lang.clanDescription}</strong><br />
		{$clan.public_text|nl2br}
	</div>
	<div class="internalDescription">
		<strong>{$lang.internalDescription}</strong><br />
		{$clan.internal_text|nl2br}
	</div>
	<div class="backlink">
		<a href="index.php?chose=acp&amp;sub=clanlist">{$lang.back}</a>
	</div>
{/if}
</div>