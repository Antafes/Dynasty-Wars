<script language="javascript" type="text/javascript">{literal}
	$(function() {
		$('#changePosition').click(function() {
			if ($(this).openWindow)
			{
				$(this).openWindow.close();
				$(this).openWindow = null;
			}

			$(this).openWindow = window.open(
				'acp/poschange.php?reguid={/literal}{$regUser->getUID()}{literal}',
				'popup',
				'dependent=yes,location=no,menubar=no,toolbar=no,status=no,resizable=no,scrollbars=yes,width=450,height=300'
			);
			$(this).openWindow.moveTo(10,50);
		});
	});
</script>{/literal}
<div class="userDetails">
	<table>
		<tr>
			<th>{$lang.userNumber|htmlentities}:</th>
			<td>{$regUser->getUID()}</td>
		</tr>
		<tr>
			<th>{$lang.name|htmlentities}:</th>
			<td>
				<a href="index.php?chose=usermap&amp;reguid={$regUser->getUID()}&amp;fromc={$fromc|cat:$regUser->getUID()}">{$regUser->getNick()|htmlentities}</a>
			</td>
		</tr>
		<tr>
			<th>{$lang.registrationDate|htmlentities}:</th>
			<td>
				{$regUser->getRegDate()|date:$lang.acptimeformat}
			</td>
		</tr>
		<tr>
			<th>{$lang.gameRank|htmlentities}:</th>
			<td>
				<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}">
					<select name="nregadmin"{if $smarty.session.user->getUID() == $regUser->getUID() || $smarty.session.user->getGameRank() == 1} disabled="disabled"{/if}>
						<option value="3"{if $regUser->getGameRank() == 3} selected="selected"{/if}>{$lang.gameRanks.judge|htmlentities}</option>
						<option value="2"{if $regUser->getGameRank() == 2} selected="selected"{/if}>{$lang.gameRanks.sadmin|htmlentities}</option>
						<option value="1"{if $regUser->getGameRank() == 1} selected="selected"{/if}>{$lang.gameRanks.admin|htmlentities}</option>
						<option value="0"{if $regUser->getGameRank() == 0} selected="selected"{/if}>{$lang.gameRanks.user|htmlentities}</option>
					</select>
					<input type="submit" value="{$lang.change|htmlentities}"{if $smarty.session.user->getUID() == $regUser->getUID() || $smarty.session.user->getGameRank() == 1} disabled="disabled"{/if} />
				</form>
			</td>
		</tr>
		<tr>
			<th>{$lang.email|htmlentities}:</th>
			<td>{$regUser->getEmail()|htmlentities}</td>
		</tr>
		<tr>
			<th>{$lang.blocking|htmlentities}:</th>
			<td>
				{if $regUser->getGameRank() || $smarty.session.user->getUID() == $regUser->getUID()}
					{$lang.noBlock|htmlentities}
				{else}
					<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}">
						<select name="blocked">
							<option value="1"{if $regUser->getBlocked()} selected="selected"{/if}>{$lang.blocked|htmlentities}</option>
							<option value="0"{if !$regUser->getBlocked()} selected="selected"{/if}>{$lang.notBlocked|htmlentities}</option>
						</select>
						<input type="submit" value="{$lang.change|htmlentities}" />
					</form>
				{/if}
			</td>
		</tr>
		{if isset($smarty.post.blocked)}
		<tr>
			<td colspan="2">
				{if $smarty.post.blocked == 1}
					{$lang.userBlocked|sprintf:$regUser->getNick()|htmlentities}
				{else}
					{$lang.userUnblocked|sprintf:$regUser->getNick()|htmlentities}
				{/if}
			</td>
		</tr>
		{/if}
		<tr>
			<th>{$lang.deactivation|htmlentities}:</th>
			<td>
				{if $regUser->getGameRank() || $smarty.session.user->getUID() == $regUser->getUID()}
					{$lang.noDeactivation|htmlentities}
				{else}
					<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}&amp;user=1">
						<input type="submit" value="{if $regUser->getDeactivated()}{$lang.reactivate|htmlentities}{else}{$lang.deactivate|htmlentities}{/if}"{if $regUser->getUID() == $smarty.session.user->getUID()} disabled="disabled"{/if} />
						<input type="hidden" name="deactivation" value="{if $regUser->getDeactivated()}0{else}1{/if}" />
					</form>
				{/if}
			</td>
		</tr>
		{if isset($smarty.post.deactivation)}
			<tr>
				<td colspan="2">
					{if $smarty.post.deactivation == 1}
						{$lang.deactivated|htmlentities}
					{else}
						{$lang.reactivated|htmlentities}
					{/if}
				</td>
			</tr>
		{/if}
		<tr>
			<th>{$lang.deletion|htmlentities}:</th>
			<td>
				{if $regUser->getGameRank() || $smarty.session.user->getUID() == $regUser->getUID()}
					{$lang.notDeletable|htmlentities}
				{else}
					<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}&amp;del=1">
						<input type="submit" name="delete"{if $smarty.session.user->getGameRank() <= 1} disabled="disabled"{/if} value="{$lang.delete|htmlentities}" />
					</form>
				{/if}
			</td>
		</tr>
		<tr>
			<th>{$lang.position|htmlentities}</th>
			<td>
				{assign var='position' value=$regUser->getMainCity()}
				<a href="index.php?chose=map&amp;x={$position.map_x}&amp;y={$position.map_x}">[{$position.map_x}<b>:</b>{$position.map_y}]</a>
				<a href="javascript:;" id="changePosition">{$lang.change|htmlentities}</a>
			</td>
		</tr>
		<tr>
			<th>{$lang.lastLogin|htmlentities}</th>
			<td>
				{$regUser->getLastLogin()|date:$lang.acptimeformat}
				<img src="pictures/{$regUser->checkLastLogin()}.gif" title="{$lang.loginCheck[$regUser->checkLastLogin()]|htmlentities}" />
			</td>
		</tr>
		<tr>
			<th>{$lang.activation|htmlentities}:</th>
			<td>
				<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}">
					<input type="submit" value="{$lang.activationMail|htmlentities}"{if !$regUser->getStatus()} disabled="disabled"{/if} />
					<input type="hidden" name="send" value="1" />
				</form>
				<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}" style="padding-top: 5px;">
					<input type="submit" value="{$lang.activate|htmlentities}"{if !$regUser->getStatus()} disabled="disabled"{/if} />
					<input type="hidden" name="free" value="1" />
				</form>
			</td>
		</tr>
		{if $smarty.post.free}
			<tr>
				<td colspan="2">{$lang.activated|sprintf:$regUser->getNick()|htmlentities}</td>
			</tr>
		{/if}
		<tr>
			<th>{$lang.switchUser|htmlentities}:</th>
			<td>
				<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}">
					<input type="submit" name="change_user" value="{$lang.switch|htmlentities}"{if $regUser->getUID() == $smarty.session.user->getUID()} disabled="disabled"{/if} />
				</form>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<a href="index.php?chose=acp&amp;sub=userlist">{$lang.back|htmlentities}</a>
			</td>
		</tr>
	</table>
</div>