{include file="header.tpl"}
<div class="options">
	<div class="heading">{$lang.options}</div>
	{if $infoMessage}
		<div class="infoMessage">{$infoMessage|nl2br}</div>
	{/if}
	<div class="optionsContent">
		<div class="option">
			<div class="subheading">{$lang.changePassword}</div>
			<form name="form1" method="post" action="index.php?chose=options">
				<table>
					<tr>
						<th class="title">{$lang.oldPassword}:</th>
						<td class="field">
							<input type="password" name="oldpw" />
						</td>
					</tr>
					<tr>
						<th class="title">{$lang.newPassword}:</th>
						<td class="field">
							<input type="password" name="newpw" />
						</td>
					</tr>
					<tr>
						<th class="title">{$lang.repeatPassword}:</th>
						<td class="field">
							<input type="password" name="newpww" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;">
							<input type="submit" value="{$lang.change}" />
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div class="option">
			<div class="subheading">{$lang.changeEmail}</div>
			<form method="post" action="index.php?chose=options">
				<table>
					<tr>
						<th class="title">{$lang.email}:</th>
						<td class="field">
							<input type="text" name="email" value="{$smarty.session.user->getEmail()}" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;">
							<input type="submit" value="{$lang.change}" />
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div class="option">
			<div class="subheading">{$lang.description}</div>
			<form method="post" action="index.php?chose=options&amp;textchange=1">
				<table>
					<tr>
						<td style="text-align: center;">
							<textarea name="description" cols="50" rows="10">{$smarty.session.user->getDescription()}</textarea>
						</td>
					</tr>
					<tr>
						<td style="text-align: center;">
							<select name="language">
								{html_options options=$languages selected=$smarty.session.user->getLanguage()}
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align: center;">
							<input type="submit" value="{$lang.change}" />
						</td>
					</tr>
				</table>
			</form>
		</div>
		{if $smarty.session.user->getCID() && !$own_uid}
			<div class="option">
				<div class="subheading">{$lang.clan}</div>
				<div style="text-align: center;">
					{if !$smarty.get.leave}
						<a href="index.php?chose=options&amp;leave=1">{$lang.clanLeave}</a>
					{else}
						{$lang.reallyLeave}<br />
						<a href="index.php?chose=options&amp;leave=2">{$lang.yes}</a>&nbsp;/&nbsp;<a href="index.php?chose=options">{$lang.no}</a>
					{/if}
				</div>
			</div>
		{/if}
		<div class="option">
			<form method="post" action="index.php?chose=options&amp;del=1">
				<div class="subheading">{$lang.deleteAccount}</div>
				<div style="text-align: center;">
					<input type="checkbox" name="delcheck" />
					<input type="submit" value="{$lang.delete}" />
				</div>
			</form>
		</div>
	</div>
</div>
{include file="footer.tpl"}