{include file="header.tpl"}
			<form method="post" action="index.php?chose=registration">
				<div class="caption">
					{$heading}
				</div>
				{if !$reg_closed}
				<div class="register">
					{if $errors}
					<div class="errors">
						{foreach from=$errors item=error}
						<div class="error">
							{$error}
						</div>
						{/foreach}
					</div>
					{/if}
					<div class="row">
						<div class="left">
							{$name}:
						</div>
						<div class="right max">
							<input name="regnick" type="text" maxlength="20" value="{$entered_nick}" />
							({$max_length_nick})
						</div>
						<br class="clear" />
					</div>
					<div class="row">
						<div class="left">
							{$password}:
						</div>
						<div class="right">
							<input type="password" name="regpw" />
						</div>
						<br class="clear" />
					</div>
					<div class="row">
						<div class="left">
							{$repeat_password}:
						</div>
						<div class="right">
							<input type="password" name="regpww" />
						</div>
						<br class="clear" />
					</div>
					<div class="row">
						<div class="left">
							{$email}
						</div>
						<div class="right">
							<input type="text" name="regemail" value="{$entered_email}" />
						</div>
						<br class="clear" />
					</div>
					<div class="row">
						<div class="left" title="{$city_description}">
							{$city} <img src="pictures/help.gif" alt="{$help_alt}" />
						</div>
						<div class="right max">
							<input name="regcity" type="text" maxlength="20" value="{$entered_city}" />
							({$max_length_city})
						</div>
						<br class="clear" />
					</div>
					<div class="row">
						<div class="both recaptcha">
							{$recaptcha}
						</div>
					</div>
					<div class="row">
						<div class="both">
							<input type="submit" name="sub" value="{$button_register}"/>
							<input type="hidden" name="reg" value="1"/>
						</div>
					</div>
				</div>
				{else}
				<div class="register closed">
					{$reg_closed}
				</div>
				{/if}
			</form>
{include file="footer.tpl"}