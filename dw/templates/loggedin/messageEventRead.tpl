<div class="readEventMessage">
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
		<div class="backlink">
			<a href="index.php?chose=messages&amp;mmode=event">{$lang.back}</a>
		</div>
	</div>
</div>