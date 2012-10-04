<div class="gameMenu">
	<form method="post" action="index.php?chose=acp&amp;sub=gameoptions&amp;gameOptionsSub=menu" name="change_menu">
		<div class="subheading">{$lang.gameMenu}</div>
		<table>
			<tr>
				<th class="menuEntry">
					{$lang.menuEntry}
				</th>
				<th class="entryActive">
					{$lang.entryActive}
				</th>
				<th class="sorting">
					{$lang.sorting}
				</th>
				<th class="visibility">
					{$lang.visibility}
				</th>
			</tr>
			{foreach from=$menuEntries item=menuEntry}
				<tr>
					<td class="menuEntry">{$lang[$menuEntry['menu_name']]}</td>
					<td class="entryActive">
						<input type="checkbox" name="entries[{$menuEntry.game_menu_id}]"{if $menuEntry.active} checked="checked"{/if} />
					</td>
					<td>
						{if $menuEntry.menu_name == 'home' || $menuEntry.menu_name == 'logout' || $menuEntry.menu_name == 'acp' || $menuEntry.menu_name == 'usermap'}
							{$menuEntry.sort}
							<input type="hidden" name="sort[{$menuEntry.game_menu_id}]" value="{$menuEntry.sort}" />
						{else}
							<select name="sort[{$menuEntry.game_menu_id}]" style="width: 40px;">
								{html_options options=$sortingArray selected=$menuEntry.sort}
							</select>
						{/if}
					</td>
					<td>
						<input type="checkbox" name="visible[{$menuEntry.game_menu_id}]"{if $menuEntry.visible} checked="checked"{/if}{if $menuEntry.menu_name == 'usermap'} disabled="disabled"{/if} />
					</td>
				</tr>
			{/foreach}
			<tr>
				<td colspan="4" style="text-align: center;">
					<input type="submit" value="{$lang.change}" />
				</td>
			</tr>
		</table>
	</form>
</div>