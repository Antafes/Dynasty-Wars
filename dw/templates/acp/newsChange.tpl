<div class="subheading">
	{if !$smarty.get.nid}
		{$lang.writeNews|htmlentities}
	{else}
		{$lang.changeNews|htmlentities}
	{/if}
</div>
<form method="post" action="index.php?chose=acp&amp;sub=news&amp;nmode={$smarty.get.nmode}&amp;nid={$smarty.get.nid}">
	{if $message}
		<div class="infoMessage">{$message|htmlentities}</div>
	{/if}
	<div class="newsChange">
		<table>
			<tr>
				<th style="width: 50px;">{$lang.title|htmlentities}:</th>
				<td>
					<input type="text" name="title" value="{$newsEntry.title|htmlentities}" />
				</td>
			</tr>
			<tr>
				<th colspan="2">{$lang.text|htmlentities}:</th>
			</tr>
			<tr>
				<td colspan="2">
					<textarea name="news" cols="50" rows="10">{$newsEntry.text|htmlentities}</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;">
					<input type="submit" value="{$lang.save|htmlentities}" />
				</td>
			</tr>
		</table>
		{if $smarty.get.nid}
		<div class="backlink">
			<a href="index.php?chose=acp&amp;sub=news&amp;nmode=1">{$lang.back|htmlentities}</a>
		</div>
		{/if}
	</div>
</form>