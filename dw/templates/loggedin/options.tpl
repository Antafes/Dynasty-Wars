{include file="header.tpl"}
<div class="options">
	<div class="heading">{$lang.options|htmlentities}</div>
	{if $infoMessage}
		<div class="infoMessage">{$infoMessage|htmlentities|nl2br}</div>
	{/if}
	<div class="optionsContent">
		<div class="option">
			<div class="subheading">{$lang.changePassword|htmlentities}</div>
			<form name="form1" method="post" action="index.php?chose=options">
				<table>
					<tr>
						<th class="title">{$lang.oldPassword|htmlentities}:</th>
						<td class="field">
							<input type="password" name="oldpw" />
						</td>
					</tr>
					<tr>
						<th class="title">{$lang.newPassword|htmlentities}:</th>
						<td class="field">
							<input type="password" name="newpw" />
						</td>
					</tr>
					<tr>
						<th class="title">{$lang.repeatPassword|htmlentities}:</th>
						<td class="field">
							<input type="password" name="newpww" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;">
							<input type="submit" value="{$lang.change|htmlentities}" />
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div class="option">
			<div class="subheading">{$lang.changeEmail|htmlentities}</div>
			<form method="post" action="index.php?chose=options">
				<table>
					<tr>
						<th class="title">{$lang.email|htmlentities}:</th>
						<td class="field">
							<input type="text" name="email" value="{$smarty.session.user->getEmail()|htmlentities}" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;">
							<input type="submit" value="{$lang.change|htmlentities}" />
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div class="option">
			<div class="subheading">{$lang.description|htmlentities}</div>
			<form method="post" action="index.php?chose=options&amp;textchange=1">
				<table>
					<tr>
						<td style="text-align: center;">
							<textarea name="description" cols="50" rows="10">{$smarty.session.user->getDescription()|htmlentities}</textarea>
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
							<input type="submit" value="{$lang.change|htmlentities}" />
						</td>
					</tr>
				</table>
			</form>
		</div>
		{if $smarty.session.user->getCID() && !$own_uid}
			<div class="option">
				<div class="subheading">{$lang.clan|htmlentities}</div>
				<div style="text-align: center;">
					{if !$smarty.get.leave}
						<a href="index.php?chose=options&amp;leave=1">{$lang.clanLeave|htmlentities}</a>
					{else}
						{$lang.reallyLeave|htmlentities}<br />
						<a href="index.php?chose=options&amp;leave=2">{$lang.yes|htmlentities}</a>&nbsp;/&nbsp;<a href="index.php?chose=options">{$lang.no|htmlentities}</a>
					{/if}
				</div>
			</div>
		{/if}
		<div class="option">
			<form method="post" action="index.php?chose=options&amp;del=1">
				<div class="subheading">{$lang.deleteAccount|htmlentities}</div>
				<div style="text-align: center;">
					<input type="checkbox" name="delcheck" />
					<input type="submit" value="{$lang.delete|htmlentities}" />
				</div>
			</form>
		</div>
	</div>
</div>
{include file="footer.tpl"}