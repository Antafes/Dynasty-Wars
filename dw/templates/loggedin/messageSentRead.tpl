<div class="readMessage">
	<div class="subheading">{$lang.message|htmlentities}</div>
	<div class="message">
		<table>
			<tr>
				<th>{$lang.recipient|htmlentities}:</th>
				<td>{$message.recipient|htmlentities}</td>
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
			<a href="index.php?chose=messages&amp;mmode=sent">{$lang.back|htmlentities}</a>
		</div>
	</div>
</div>