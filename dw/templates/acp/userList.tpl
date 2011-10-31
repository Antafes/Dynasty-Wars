<div class="userList">
	<table>
		<tr>
			<th class="userID">{$lang.userNumber|htmlentities}</th>
			<th class="userName">{$lang.name|htmlentities}</th>
			<th class="userStatus">{$lang.status|htmlentities}</th>
		</tr>
		{foreach from=$users item='user'}
			<tr>
				<td class="userID">{$user.uid}</td>
				<td class="userName"><a href="index.php?chose=acp&amp;sub=userlist&amp;reguid={$user.uid}">{$user.nick|htmlentities}</a></td>
				<td class="userStatus">
					<img src="pictures/{if $user.blocked}red{else}green{/if}.gif" title="{if $user.blocked}{$lang.blocked|htmlentities}{else}{$lang.notBlocked|htmlentities}{/if}" />{if $user.game_rank == 1} (a){elseif $user.game_rank == 2} (sa){/if}
				</td>
			</tr>
		{/foreach}
	</table>
	{if $smarty.get.del}
		{$lang.userDeleted|htmlentities}
	{/if}
</div>