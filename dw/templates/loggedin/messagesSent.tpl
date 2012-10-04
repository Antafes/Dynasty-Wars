<div class="sentMessages">
	<div class="subheading">{$lang.sentMessages}</div>
	<div class="messageList">
		{if $infoMessage}
			<div class="infoMessage">{$infoMessage}</div>
		{/if}
		<form method="post" action="index.php?chose=messages&amp;mmode=received">
			<table>
				<tr>
					<th class="message">{$lang.message}</th>
					<th class="sender">{$lang.recipient}</th>
					<td class="delete">
						<input type="submit" value="{$lang.delete}" />
						<input type="hidden" name="deleting" value="1" />
					</td>
				</tr>
				{foreach from=$messages item=message}
					<tr>
						<td class="message">
							<a href="index.php?chose=messages&amp;mmode=sent&amp;msgid={$message.msgid}">
								<img src="pictures/msg_{if $message.unread}un{/if}read.gif" alt="{$lang.unread}" title="{$lang.unread}" border="0"/>
								{$message.title}
							</a>
						</td>
						<td class="sender">{$message.recipient}</td>
						<td class="delete">
							<input type="checkbox" name="delete[]" value="{$message.msgid}" />
						</td>
					</tr>
				{foreachelse}
					<tr>
						<td colspan="3">{$lang.noMessages}</td>
					</tr>
				{/foreach}
				<tr>
					<td>&nbsp;</td>
					<td colspan="2" style="text-align: right;">
						<label for="markall">{$lang.markAll}&nbsp;</label><input id="markall" type="checkbox" />
					</td>
				</tr>
			</table>
		</form>
		<div class="backlink">
			<a href="index.php?chose=messages">{$lang.back}</a>
		</div>
	</div>
</div>