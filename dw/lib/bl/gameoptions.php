<?php
/**
 * get the specified menu entry
 * @author Neithan
 * @param string $entryName
 * @return array
 */
function lib_bl_gameoptions_getEntry($entryName)
{
	return lib_dal_gameoptions_getEntry($entryName);
}
/**
 * get all menu entries
 * @author Neithan
 * @return array
 */
function lib_bl_gameoptions_getAllEntries($visible = true)
{
	return lib_dal_gameoptions_getAllEntries($visible);
}

/**
 * set the status of all menu entries
 * @author Neithan
 * @param array $changedEntries
 * @param array $sorts
 * @param array $visible
 * @return void
 */
function lib_bl_gameoptions_setAllEntries($changedEntries, $sorts, $visible)
{
	$allEntries = lib_bl_gameoptions_getAllEntries(false);
	lib_dal_gameoptions_setAllEntries(0, 0);
	foreach ($allEntries as $entry)
	{
		$game_menu_id = $entry['game_menu_id'];
		lib_dal_gameoptions_setEntry($game_menu_id, ($changedEntries[$game_menu_id] ? 1 : 0), $sorts[$game_menu_id], ($visible[$game_menu_id] ? 1 : 0));
	}
}
?>