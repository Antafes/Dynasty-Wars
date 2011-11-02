<div class="readMessage">
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
		<div class="messageLinks">
			<div class="link">
				<a href="index.php?chose=messages&amp;mmode=aw&amp;msgid={$smarty.get.msgid}">{$lang.answer|htmlentities}</a>
			</div>
			<div class="link">
				<a href="index.php?chose=messages&amp;mmode=received&amp;msgid={$smarty.get.msgid}&amp;do=archive">{$lang.arch|htmlentities}</a>
			</div>
			<div class="link lastLink">
				<a href="index.php?chose=messages&amp;mmode=received">{$lang.back|htmlentities}</a>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>