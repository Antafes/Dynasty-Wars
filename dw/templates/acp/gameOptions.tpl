<div class="gameOptions">
	<div class="subheading">{$lang.common|htmlentities}</div>
	{if $message}
		<div class="infoMessage">{$message|htmlentities}</div>
	{/if}
	<table>
		<form method="post" action="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=common">
			<tr>
				<th>{$lang.login|htmlentities}:</th>
				<td>
					<select name="login_closed">
						{html_options options=$lang.loginClosed selected=$gameOptions.login_closed}
					</select>
				</td>
				<td>
					<input type="submit" value="{$lang.change|htmlentities}" />
				</td>
			</tr>
		</form>
		<form method="post" action="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=common">
			<tr>
				<th>{$lang.registration|htmlentities}:</th>
				<td>
					<select name="reg_closed">
						{html_options options=$lang.registrationClosed selected=$gameOptions.reg_closed}
					</select>
				</td>
				<td>
					<input type="submit" value="{$lang.change|htmlentities}" />
				</td>
			</tr>
		</form>
		<form method="post" action="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=common">
			<tr>
				<th>{$lang.boardAddress|htmlentities}:</th>
				<td>
					<input type="text" name="board" value="{$gameOptions.board}" />
				</td>
				<td>
					<input type="submit" value="{$lang.change|htmlentities}" />
				</td>
			</tr>
		</form>
		<form method="post" action="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=common">
			<tr>
				<th>{$lang.gameReset|htmlentities}:</th>
				<td>
					<input type="checkbox" name="reset1" />
				</td>
				<td>
					<input type="submit" name="reset2" value="{$lang.reset|htmlentities}" />
				</td>
			</tr>
		</form>
		<form method="post" action="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=common">
			<tr>
				<th>{$lang.errorReporting|htmlentities}:</th>
				<td>
					{html_checkboxes name='errorReporting' options=$lang.errorReportingArray separator='<br />' selected=$errorCodes}
				</td>
				<td>
					<input type="submit" value="{$lang.change|htmlentities}" />
					<input type="hidden" name="errorReportingChanged" value="1" />
				</td>
			</tr>
		</form>
		<form method="post" action="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=common">
			<tr>
				<th>{$lang.unitCosts|htmlentities}:</th>
				<td colspan="2">
					<input type="submit" value="{if $gameOptions.unitcosts}{$lang.disable|htmlentities}{else}{$lang.enable|htmlentities}{/if}" />
					<input type="hidden" name="unitCosts" value="{if $gameOptions.unitcosts}0{else}1{/if}" />
				</td>
			</tr>
		</form>
		<form method="post" action="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=common">
			<tr>
				<th>{$lang.canAttack|htmlentities}:</th>
				<td colspan="2">
					<input type="submit" value="{if $gameOptions.canattack}{$lang.disable|htmlentities}{else}{$lang.enable|htmlentities}{/if}" />
					<input type="hidden" name="unitCosts" value="{if $gameOptions.canattack}0{else}1{/if}" />
				</td>
			</tr>
		</form>
		<form method="post" action="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=common">
			<tr>
				<th>{$lang.version|htmlentities}:</th>
				<td>
					<input type="text" name="version" value="{$gameOptions.version}" />
				</td>
				<td>
					<input type="submit" value="{$lang.change|htmlentities}" />
				</td>
			</tr>
		</form>
	</table>
</div>