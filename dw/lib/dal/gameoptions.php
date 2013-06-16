<?php
namespace dal\gameOptions;

/**
 * get the specified menu entry
 * @author Neithan
 * @param string $entryName
 * @return array
 */
function getMenuEntry($entryName)
{
	$sql = '
		SELECT *
		FROM dw_game_menu
		WHERE menu_name = '.\util\mysql\sqlval($entryName).'
	';
	return \util\mysql\query($sql);
}

/**
 * get all menu entries
 * @author Neithan
 * @param boolean $visible (optional)
 * @return array
 */
function getAllMenuEntries($visible = true)
{
	$sql = '
		SELECT *
		FROM dw_game_menu
		'.($visible ? 'WHERE visible = 1' : '').'
		ORDER BY sort ASC';
	return \util\mysql\query($sql, true);
}

/**
 * set the status of an menu entry
 * @author Neithan
 * @param int $game_menu_id
 * @param int $active
 * @return int
 */
function setMenuEntry($game_menu_id, $active, $sort, $visible)
{
	$sql = '
		UPDATE dw_game_menu
		SET active = '.\util\mysql\sqlval($active).',
			sort = '.\util\mysql\sqlval($sort).',
			visible = '.\util\mysql\sqlval($visible).'
		WHERE game_menu_id = '.\util\mysql\sqlval($game_menu_id).'
	';
	return \util\mysql\query($sql);
}

/**
 * set the status of all entries
 * @author Neithan
 * @param int $active
 * @param int $visible
 * @return int
 */
function setAllMenuEntries($active, $visible)
{
	$sql = '
		UPDATE dw_game_menu
		SET active = '.\util\mysql\sqlval($active).',
			visible = '.\util\mysql\sqlval($visible).'
	';
	return \util\mysql\query($sql);
}

/**
 * get the game options
 * @author Neithan
 * @return array
 */
function getGameOptions()
{
	$sql = 'SELECT * FROM dw_game';
	return \util\mysql\query($sql);
}