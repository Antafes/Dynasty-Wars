{include file='header.tpl'}
<div class="clan">
	<div class="heading">{$lang.clan|htmlentities}</div>
	{if $clanPage}
	{$clanPage}
	{else}
	<div class="search">
		{if $smarty.get.searchclan}
		{$lang.clansearch|htmlentities}
		<form method="post" action="index.php?chose=clan&amp;searchclan=1">
			<table class="search">
				<tr>
					<td>{$lang.clan|htmlentities}:</td>
					<td>
						<input type="text" name="clan" />
					</td>
				</tr>
				<tr>
					<td>{$lang.tag|htmlentities}:</td>
					<td>
						<input name="clantag" type="text" size="5" maxlength="5" />
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;">
						<input type="submit" value="{$lang.search|htmlentities}" />
					</td>
				</tr>
			</table>
		</form>
		{if $clanData}
		<table class="clan_list">
			<tr>
				<td class="clan">{$lang.clan|htmlentities}</td>
				<td class="members">{$lang.members|htmlentities}</td>
			</tr>
			{foreach from=$clanData item=clan}
			<tr>
				<td class="clan">
					<a href="index.php?chose=clan&amp;cid={$clan.cid}">{$clan.clanname|htmlentities} [{$clan.clantag|htmlentities}]</a>
				</td>
				<td class="members">{$clan.users}</td>
			</tr>
			{/foreach}
		</table>
		{elseif !$clanData && $smarty.post.clan || $smarty.post.clantag}
		{$lang.nonefound|htmlentities}
		{/if}
		{elseif $smarty.get.newclan}
		{$lang.foundclan|htmlentities}
		<form method="post" action="index.php?chose=clan&amp;newclan=1">
			<table class="search">
				<tr>
					<td>{$lang.clan|htmlentities}:</td>
					<td>
						<input type="text" name="clan" />
					</td>
				</tr>
				<tr>
					<td>{$lang.tag|htmlentities}:</td>
					<td>
						<input name="clantag" type="text" size="5" maxlength="5" />
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;">
						<input type="submit" value="{$lang.found|htmlentities}" />
					</td>
				</tr>
			</table>
		</form>
		{else}
		{$lang.notinclan|htmlentities}<br />
		<a href="index.php?chose=clan&amp;searchclan=1">{$lang.searchclan|htmlentities}</a>
		{if $gardenLvl > 0}
		<a href="index.php?chose=clan&amp;newclan=1">{$lang.foundclan|htmlentities}</a>
		{else}
		{$lang.foundclan|htmlentities}<br />
		{$lang.foundationinfo|htmlentities}
		{/if}
		{/if}
	</div>
	{/if}
</div>
{include file='footer.tpl'}