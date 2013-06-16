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
			<th>{$lang.userNumber}:</th>
			<td>{$regUser->getUID()}</td>
		</tr>
		<tr>
			<th>{$lang.name}:</th>
			<td>
				<a href="index.php?chose=usermap&amp;reguid={$regUser->getUID()}&amp;fromc={$fromc|cat:$regUser->getUID()}">{$regUser->getNick()}</a>
			</td>
		</tr>
		<tr>
			<th>{$lang.registrationDate}:</th>
			<td>
				{$regUser->getRegDate()}
			</td>
		</tr>
		<tr>
			<th>{$lang.gameRank}:</th>
			<td>
				<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}">
					<select name="nregadmin"{if $smarty.session.user->getUID() == $regUser->getUID() || $smarty.session.user->getGameRank() == 1} disabled="disabled"{/if}>
						<option value="3"{if $regUser->getGameRank() == 3} selected="selected"{/if}>{$lang.gameRanks.judge}</option>
						<option value="2"{if $regUser->getGameRank() == 2} selected="selected"{/if}>{$lang.gameRanks.sadmin}</option>
						<option value="1"{if $regUser->getGameRank() == 1} selected="selected"{/if}>{$lang.gameRanks.admin}</option>
						<option value="0"{if $regUser->getGameRank() == 0} selected="selected"{/if}>{$lang.gameRanks.user}</option>
					</select>
					<input type="submit" value="{$lang.change}"{if $smarty.session.user->getUID() == $regUser->getUID() || $smarty.session.user->getGameRank() == 1} disabled="disabled"{/if} />
				</form>
			</td>
		</tr>
		<tr>
			<th>{$lang.email}:</th>
			<td>{$regUser->getEmail()}</td>
		</tr>
		<tr>
			<th>{$lang.blocking}:</th>
			<td>
				{if $regUser->getGameRank() || $smarty.session.user->getUID() == $regUser->getUID()}
					{$lang.noBlock}
				{else}
					<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}">
						<select name="blocked">
							<option value="1"{if $regUser->getBlocked()} selected="selected"{/if}>{$lang.blocked}</option>
							<option value="0"{if !$regUser->getBlocked()} selected="selected"{/if}>{$lang.notBlocked}</option>
						</select>
						<input type="submit" value="{$lang.change}" />
					</form>
				{/if}
			</td>
		</tr>
		{if isset($smarty.post.blocked)}
		<tr>
			<td colspan="2">
				{if $smarty.post.blocked == 1}
					{$lang.userBlocked|sprintf:$regUser->getNick()}
				{else}
					{$lang.userUnblocked|sprintf:$regUser->getNick()}
				{/if}
			</td>
		</tr>
		{/if}
		<tr>
			<th>{$lang.deactivation}:</th>
			<td>
				{if $regUser->getGameRank() || $smarty.session.user->getUID() == $regUser->getUID()}
					{$lang.noDeactivation}
				{else}
					<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}&amp;user=1">
						<input type="submit" value="{if $regUser->getDeactivated()}{$lang.reactivate}{else}{$lang.deactivate}{/if}"{if $regUser->getUID() == $smarty.session.user->getUID()} disabled="disabled"{/if} />
						<input type="hidden" name="deactivation" value="{if $regUser->getDeactivated()}0{else}1{/if}" />
					</form>
				{/if}
			</td>
		</tr>
		{if isset($smarty.post.deactivation)}
			<tr>
				<td colspan="2">
					{if $smarty.post.deactivation == 1}
						{$lang.deactivated}
					{else}
						{$lang.reactivated}
					{/if}
				</td>
			</tr>
		{/if}
		<tr>
			<th>{$lang.deletion}:</th>
			<td>
				{if $regUser->getGameRank() || $smarty.session.user->getUID() == $regUser->getUID()}
					{$lang.notDeletable}
				{else}
					<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}&amp;del=1">
						<input type="submit" name="delete"{if $smarty.session.user->getGameRank() <= 1} disabled="disabled"{/if} value="{$lang.delete}" />
					</form>
				{/if}
			</td>
		</tr>
		<tr>
			<th>{$lang.position}</th>
			<td>
				{assign var='position' value=$regUser->getMainCity()}
				<a href="index.php?chose=map&amp;x={$position.map_x}&amp;y={$position.map_x}">[{$position.map_x}<b>:</b>{$position.map_y}]</a>
				<a href="javascript:;" id="changePosition">{$lang.change}</a>
			</td>
		</tr>
		<tr>
			<th>{$lang.lastLogin}</th>
			<td>
				{$regUser->getLastLogin()}
				<img src="pictures/{$regUser->checkLastLogin()}.gif" title="{$lang.loginCheck[$regUser->checkLastLogin()]}" />
			</td>
		</tr>
		<tr>
			<th>{$lang.activation}:</th>
			<td>
				<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}">
					<input type="submit" value="{$lang.activationMail}"{if !$regUser->getStatus()} disabled="disabled"{/if} />
					<input type="hidden" name="send" value="1" />
				</form>
				<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}" style="padding-top: 5px;">
					<input type="submit" value="{$lang.activate}"{if !$regUser->getStatus()} disabled="disabled"{/if} />
					<input type="hidden" name="free" value="1" />
				</form>
			</td>
		</tr>
		{if $smarty.post.free}
			<tr>
				<td colspan="2">{$lang.activated|sprintf:$regUser->getNick()}</td>
			</tr>
		{/if}
		<tr>
			<th>{$lang.switchUser}:</th>
			<td>
				<form method="post" action="index.php?chose=acp&amp;sub=userlist&amp;reguid={$regUser->getUID()}">
					<input type="submit" name="change_user" value="{$lang.switch}"{if $regUser->getUID() == $smarty.session.user->getUID()} disabled="disabled"{/if} />
				</form>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<a href="index.php?chose=acp&amp;sub=userlist">{$lang.back}</a>
			</td>
		</tr>
	</table>
</div>