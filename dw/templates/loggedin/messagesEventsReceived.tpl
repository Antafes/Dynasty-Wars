<div class="receivedEventMessages">
	<div class="subheading">{$lang.events|htmlentities}</div>
	<div class="messageList">
		{if $infoMessage}
			<div class="infoMessage">{$infoMessage|htmlentities}</div>
		{/if}
		<form method="post" action="index.php?chose=messages&amp;mmode=received">
			<table>
				<tr>
					<th class="message">{$lang.message|htmlentities}</th>
					<th class="sender">{$lang.sender|htmlentities}</th>
					<td class="delete">
						<input type="submit" value="{$lang.delete|htmlentities}" />
						<input type="hidden" name="deleting" value="1" />
					</td>
				</tr>
				{foreach from=$messages item=message}
					<tr>
						<td class="message">
							<a href="index.php?chose=messages&amp;mmode=event&amp;msgid={$message.msgid}">
								<img src="pictures/msg_{if $message.unread}un{/if}read.gif" alt="{$lang.unread|htmlentities}" title="{$lang.unread|htmlentities}" border="0"/>
								{$message.title|htmlentities}
							</a>
						</td>
						<td class="sender">{$message.sender|htmlentities}</td>
						<td class="delete">
							<input type="checkbox" name="delete[]" value="{$message.msgid}" />
						</td>
					</tr>
				{foreachelse}
					<tr>
						<td colspan="3">{$lang.noMessages|htmlentities}</td>
					</tr>
				{/foreach}
				<tr>
					<td>&nbsp;</td>
					<td colspan="2" style="text-align: right;">
						<label for="markall">{$lang.markAll|htmlentities}&nbsp;</label><input id="markall" type="checkbox" />
					</td>
				</tr>
			</table>
		</form>
		<div class="backlink">
			<a href="index.php?chose=messages">{$lang.back|htmlentities}</a>
		</div>
	</div>
</div>