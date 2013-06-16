<div class="subheading">
	{if !$smarty.get.nid}
		{$lang.writeNews}
	{else}
		{$lang.changeNews}
	{/if}
</div>
<form method="post" action="index.php?chose=acp&amp;sub=news&amp;nmode={$smarty.get.nmode}&amp;nid={$smarty.get.nid}">
	{if $message}
		<div class="infoMessage">{$message}</div>
	{/if}
	<div class="newsChange">
		<table>
			<tr>
				<th style="width: 50px;">{$lang.title}:</th>
				<td>
					<input type="text" name="title" value="{$newsEntry.title}" />
				</td>
			</tr>
			<tr>
				<th colspan="2">{$lang.text}:</th>
			</tr>
			<tr>
				<td colspan="2">
					<textarea name="news" cols="50" rows="10">{$newsEntry.text}</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;">
					<input type="submit" value="{$lang.save}" />
				</td>
			</tr>
		</table>
		{if $smarty.get.nid}
		<div class="backlink">
			<a href="index.php?chose=acp&amp;sub=news&amp;nmode=1">{$lang.back}</a>
		</div>
		{/if}
	</div>
</form>