<div class="readEventMessage">
	<div class="subheading">{$lang.message|htmlentities}</div>
	<div class="message">
		<table>
			<tr>
				<th>{$lang.sender|htmlentities}:</th>
				<td>{$message.sender|htmlentities}</td>
			</tr>
			<tr>
				<th>{$lang.sentDate|htmlentities}:</th>
				<td>{$message.sentDate}</td>
			</tr>
			<tr>
				<th>{$lang.title|htmlentities}:</th>
				<td>{$message.title|htmlentities}</td>
			</tr>
			<tr>
				<td colspan="2">{$message.message|nl2br}</td>
			</tr>
		</table>
		<div class="backlink">
			<a href="index.php?chose=messages&amp;mmode=event">{$lang.back|htmlentities}</a>
		</div>
	</div>
</div>