{include file='header.tpl'}
<div class="clan">
	<div class="heading">{$lang.clan}</div>
	{if $clanPage}
	{$clanPage}
	{else}
	<div class="search">
		{if $smarty.get.searchclan}
		{$lang.clansearch}
		<form method="post" action="index.php?chose=clan&amp;searchclan=1">
			<table class="search">
				<tr>
					<td>{$lang.clan}:</td>
					<td>
						<input type="text" name="clan" />
					</td>
				</tr>
				<tr>
					<td>{$lang.tag}:</td>
					<td>
						<input name="clantag" type="text" size="5" maxlength="5" />
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;">
						<input type="submit" value="{$lang.search}" />
					</td>
				</tr>
			</table>
		</form>
		{if $clanData}
		<table class="clan_list">
			<tr>
				<td class="clan">{$lang.clan}</td>
				<td class="members">{$lang.members}</td>
			</tr>
			{foreach from=$clanData item=clan}
			<tr>
				<td class="clan">
					<a href="index.php?chose=clan&amp;cid={$clan.cid}">{$clan.clanname} [{$clan.clantag}]</a>
				</td>
				<td class="members">{$clan.users}</td>
			</tr>
			{/foreach}
		</table>
		{elseif !$clanData && $smarty.post.clan || $smarty.post.clantag}
		{$lang.nonefound}
		{/if}
		{elseif $smarty.get.newclan}
		{$lang.foundclan}
		<form method="post" action="index.php?chose=clan&amp;newclan=1">
			<table class="search">
				<tr>
					<td>{$lang.clan}:</td>
					<td>
						<input type="text" name="clan" />
					</td>
				</tr>
				<tr>
					<td>{$lang.tag}:</td>
					<td>
						<input name="clantag" type="text" size="5" maxlength="5" />
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;">
						<input type="submit" value="{$lang.found}" />
					</td>
				</tr>
			</table>
		</form>
		{else}
		{$lang.notinclan}<br />
		<a href="index.php?chose=clan&amp;searchclan=1">{$lang.searchclan}</a>
		{if $gardenLvl > 0}
		<a href="index.php?chose=clan&amp;newclan=1">{$lang.foundclan}</a>
		{else}
		{$lang.foundclan}<br />
		{$lang.foundationinfo}
		{/if}
		{/if}
	</div>
	{/if}
</div>
{include file='footer.tpl'}