<div class="send_message">
	<form method="post" action="index.php?chose={$smarty.get.chose}
	{foreach from=$smarty.get item=part key=param}
		{if $param != 'chose'}
			&amp;{$param}={$part}
		{/if}
	{/foreach}
	">
		{if $recipient}
		<div class="left">
			{$lang.recipient}:
		</div>
		<div class="right">
			<input type="text" name="recipient" value="{$message.recipient}" />
		</div>
		<div class="clear"></div>
		{/if}
		<div class="left">
			{$lang.title}:
		</div>
		<div class="right">
			<input type="text" name="title" value="{$message.title}" />
		</div>
		<div class="clear"></div>
		<div class="message">
			<textarea name="message" rows="15" cols="67">{$message.message}</textarea>
		</div>
		<div class="button">
			<input type="submit" value="{$lang.send}" />
			<input type="hidden" name="sent" value="1" />
		</div>
	</form>
</div>