<div class="log">
	<div class="subheading">{$lang.actionslog|htmlentities}</div>
	<div class="list">
		<table>
			<tr>
				<th class="logAction">{$lang.action|htmlentities}</th>
				<th class="logDate">{$lang.date|htmlentities}</th>
			</tr>
			{$log}
		</table>
		<div class="pagination">
			{section name=logPagination loop=$pages start=1}
				{assign var=i value=$smarty.section.logPagination.index}
				{if ($i == 1 && !$smarty.get.page) || $i == $smarty.get.page}
					&gt;{$i}&lt;
				{else}
					<a href="index.php?chose=acp&amp;sub=log&amp;page={$i}">{$i}</a>
				{/if}
			{/section}
		</div>
	</div>
</div>