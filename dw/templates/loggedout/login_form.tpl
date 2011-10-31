			<form method="post" action="index.php?chose=login">
				<div class="login">
					<div class="row">
						<div class="left">
							{$name}:
						</div>
						<div class="right">
							<input type="text" name="nick" />
						</div>
						<br class="clear" />
					</div>
					<div class="row">
						<div class="left">
							{$password}:
						</div>
						<div class="right">
							<input type="password" name="pw" />
						</div>
						<br class="clear" />
					</div>
					<div class="row">
						<div class="left"></div>
						<div class="right">
							<input type="checkbox" name="save_login" id="save_login" />
							<label for="save_login">&nbsp;{$remind_login}</label>
						</div>
						<br class="clear" />
					</div>
					<div class="row">
						<div class="both">
							<input type="submit" name="log" value="{$login_button}" />
							<input type="hidden" name="login" value="1" />
							<input type="hidden" name="log" value="1" />
						</div>
					</div>
					<div class="row">
						<div class="both">
							<a href="index.php?chose=lost_password">{$lost_password}</a>
						</div>
					</div>
				</div>
			</form>