<?php
/**
 * get the specified menu entry
 * @author Neithan
 * @param string $entryName
 * @return array
 */
function lib_bl_gameOptions_getMenuEntry($entryName)
{
	return dal\gameOptions\getMenuEntry($entryName);
}
/**
 * get all menu entries
 * @author Neithan
 * @return array
 */
function lib_bl_gameOptions_getAllMenuEntries($visible = true)
{
	return dal\gameOptions\getAllMenuEntries($visible);
}

/**
 * set the status of all menu entries
 * @author Neithan
 * @param array $changedEntries
 * @param array $sorts
 * @param array $visible
 * @return void
 */
function lib_bl_gameOptions_setAllMenuEntries($changedEntries, $sorts, $visible)
{
	$allEntries = lib_bl_gameOptions_getAllMenuEntries(false);
	dal\gameOptions\setAllMenuEntries(0, 0);
	foreach ($allEntries as $entry)
	{
		$game_menu_id = $entry['game_menu_id'];
		dal\gameOptions\setMenuEntry($game_menu_id, ($changedEntries[$game_menu_id] ? 1 : 0), $sorts[$game_menu_id], ($visible[$game_menu_id] ? 1 : 0));
	}
}

/**
 * get all game options
 * @author Neithan
 * @return array
 */
function lib_bl_gameOptions_getGameOptions()
{
	$gameOptions = dal\gameOptions\getGameOptions();

	$errorReportingArray = array();
	$errorReporting = $gameOptions['error_report'];
	for ($i = 4, $n = 8; $i > 0; $i--)
	{
		if ($errorReporting - $n >= 0)
		{
			$errorReporting -= $n;
			$errorReportingArray[] = $n;
		}

		$n = floor($n / 2);
	}

	ksort($errorReportingArray);
	$gameOptions['errorReporting'] = $errorReportingArray;

	return $gameOptions;
}