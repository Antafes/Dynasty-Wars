<div class="clanDetails">
{if $smarty.get.umode}
	<div class="subheading">{$lang.memberList|htmlentities}</div>
	<table>
		<tr>
			<th></th>
			<th>{$lang.name|htmlentities}</th>
			<th>{$lang.rank|htmlentities}</th>
		</tr>
		{foreach from=$memberList item=member name=feMemberList}
			<tr>
				<td>{$smarty.foreach.feMemberList.iteration}</td>
				<td>
					<a href="index.php?chose=acp&amp;sub=userlist&amp;reguid={$member.uid}">{$member.nick|htmlentities}</a>
					{if $member.blocked}(g){/if}
				</td>
				<td>{$member.rankname|htmlentities}</td>
			</tr>
		{/foreach}
	</table>
	<div class="backlink">
		<a href="index.php?chose=acp&amp;sub=clanlist&amp;cid={$smarty.get.cid}">{$lang.back|htmlentities}</a>
	</div>
{else}
	<div class="subheading">{$clan.clanname|htmlentities} [{$clan.clantag|htmlentities}]</div>
	<table>
		<tr>
			<th>{$lang.clanFounder|htmlentities}:</th>
			<td>{$clan.founder|htmlentities}</td>
			<td>
				<a href="index.php?chose=acp&amp;sub=clanlist&amp;cid={$smarty.get.cid}&amp;umode=1">{$lang.memberList|htmlentities}</a>
			</td>
		</tr>
		<tr>
			<th>{$lang.members|htmlentities}:</th>
			<td colspan="2">{$clan.memberCount}</td>
		</tr>
		<tr>
			<th>{$lang.points|htmlentities}:</th>
			<td>{$clan.points|number_format:0:',':'.'}</td>
		</tr>
	</table>
	<div class="clanDescription">
		<strong>{$lang.clanDescription|htmlentities}</strong><br />
		{$clan.public_text|htmlentities|nl2br}
	</div>
	<div class="internalDescription">
		<strong>{$lang.internalDescription|htmlentities}</strong><br />
		{$clan.internal_text|htmlentities|nl2br}
	</div>
	<div class="backlink">
		<a href="index.php?chose=acp&amp;sub=clanlist">{$lang.back|htmlentities}</a>
	</div>
{/if}
</div>