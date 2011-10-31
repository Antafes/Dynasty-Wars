{include file="header.tpl"}
			<form name="lost_password" method="post" action="{$action}">
				<div class="lost_password">
					<div class="caption">
						{$heading}
					</div>
					{if $id}
						{if $error || $wrong_id}
					<div class="error">
						{$error}
					</div>
						{/if}
						{if !$wrong_id}
					<div class="row">
						<div class="left">
							{$new_password}:
						</div>
						<div class="right">
							<input name="newpw" type="password" id="newpw" />
						</div>
						<br class="clear" />
					</div>
					<div class="row">
						<div class="left">
							{$repeat_password}:
						</div>
						<div class="right">
							<input name="newpww" type="password" id="newpww" />
						</div>
						<br class="clear" />
					</div>
					<div class="row">
						<input name="lostpwsub" type="submit" value="{$change}" />
					</div>
						{/if}
					{else}
						{if $sent}
					<div class="sent">
						{$sent}
					</div>
						{/if}
					<div class="row">
						<div class="left">
							{$email}:
						</div>
						<div class="right">
							<input name="email" type="text" />
						</div>
					</div>
					<div class="row">
						<div class="both">
							<input name="lp_sub" type="submit" value="{$button_send}" />
						</div>
					</div>
					{/if}
				</div>
			</form>
{include file="footer.tpl"}