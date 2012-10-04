<div class="readMessage">
	<div class="subheading">{$lang.message}</div>
	<div class="message">
		<table>
			<tr>
				<th>{$lang.sender}:</th>
				<td>{$message.sender}</td>
			</tr>
			<tr>
				<th>{$lang.sentDate}:</th>
				<td>{$message.sentDate}</td>
			</tr>
			<tr>
				<th>{$lang.title}:</th>
				<td>{$message.title}</td>
			</tr>
			<tr>
				<td colspan="2">{$message.message|nl2br}</td>
			</tr>
		</table>
		<div class="messageLinks">
			<div class="link">
				<a href="index.php?chose=messages&amp;mmode=aw&amp;msgid={$smarty.get.msgid}">{$lang.answer}</a>
			</div>
			<div class="link">
				<a href="index.php?chose=messages&amp;mmode=received&amp;msgid={$smarty.get.msgid}&amp;do=archive">{$lang.arch}</a>
			</div>
			<div class="link lastLink">
				<a href="index.php?chose=messages&amp;mmode=received">{$lang.back}</a>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>