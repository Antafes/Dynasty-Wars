<?php
namespace dal\general;

/**
 * get the ressource amounts of the defined user
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return array
 */
function getResources($x, $y)
{
	$sql = '
		SELECT * FROM `dw_res`
		WHERE `map_x` = '.mysql_real_escape_string($x).'
			AND `map_y` = '.mysql_real_escape_string($y).'
	';
	return \util\mysql\query($sql);
}

/**
 * chosing language
 * @author Neithan
 * @param int $uid
 * @return string
 */
function getLanguageByUID($uid)
{
	$sql = 'SELECT language FROM dw_user WHERE uid='.mysql_real_escape_string($uid);
	return \util\mysql\query($sql);
}

/**
 * get all languages
 * @author Neithan
 * @param boolean $active if true, only usable languages are returned
 * @return array
 */
function getLanguages($active)
{
	$sql = '
		SELECT
			language,
			name
		FROM dw_languages
	';

	if ($active)
		$sql .= '
			WHERE active
		';

	return \util\mysql\query($sql, true);
}

/**
 * get the actual season
 * @author Neithan
 * @return int
 */
function getSeason()
{
	$sql = 'SELECT season FROM dw_game';
	return \util\mysql\query($sql);
}

/**
 * sending messages
 * @author Neithan
 * @param int $uid_sender
 * @param int $uid_recipient
 * @param string $title
 * @param string $message
 * @param int $type
 * @return int
 */
function sendMessage($uid_sender, $uid_recipient, $title, $message, $type)
{
	$sql = '
		INSERT INTO dw_message (
			uid_sender,
			uid_recipient,
			date,
			title,
			message,
			type
		) VALUES (
			'.mysql_real_escape_string($uid_sender).',
			'.mysql_real_escape_string($uid_recipient).',
			NOW(),
			"'.mysql_real_escape_string($title).'",
			"'.mysql_real_escape_string($message).'",
			'.mysql_real_escape_string($type).'
		)
	';
	return \util\mysql\query($sql);
}

/**
 * deactivate user
 * @author Neithan
 * @param int $uid
 * @param int $state
 * @return int
 */
function deactivateUser($uid, $state)
{
	$sql = 'UPDATE dw_user SET deactivated = '.mysql_real_escape_string($state).' WHERE uid = '.mysql_real_escape_string($uid).'';
	return \util\mysql\query($sql);
}

/**
 * Set the user with this ID to the clan leader
 * @author Neithan
 * @param int $uid
 * @return int
 */
function setClanLeader($uid)
{
	$sql = 'UPDATE dw_user SET rankid = 1 WHERE uid = '.mysql_real_escape_string($uid).'';
	return \util\mysql\query($sql);
}

/**
 * delete a row from the given table
 * @author Neithan
 * @param string $table
 * @param string $where
 * @return int
 */
function deleteFrom($table, $where)
{
	$sql = '
		DELETE FROM '.mysql_real_escape_string($table).'
		WHERE '.mysql_real_escape_string($where).'
	';
	return \util\mysql\query($sql);
}

/**
 * reset the map position
 * @author Neithan
 * @param int $uid
 * @return int
 */
function updateMap($uid)
{
	$sql = 'UPDATE dw_map SET uid = 0, city = "" WHERE uid = '.mysql_real_escape_string($uid).'';
	return \util\mysql\query($sql);
}

/**
 * delete all buildings
 * @author Neithan
 * @param int $uid
 * @return int
 */
function deleteBuildings($uid)
{
	$sql = '
		DELETE FROM dw_buildings, dw_build
		USING dw_buildings LEFT JOIN dw_build USING (bid)
		WHERE dw_buildings.uid = '.mysql_real_escape_string($uid).'
	';
	return \util\mysql\query($sql);
}

/**
 * update uid and city
 * @author Neithan
 * @param string $where
 * @param int $uid default = 0
 * @param string $city default = ''
 * @return int
 */
function changePosition($where, $uid = 0, $city = '')
{
	$sql = '
		UPDATE dw_map
		SET uid = '.mysql_real_escape_string($uid).',
			city = "'.mysql_real_escape_string($city).'"
		WHERE '.mysql_real_escape_string($where);
	return \util\mysql\query($sql);
}

/**
 * check if the menu entry is active or not
 * @author Neithan
 * @param string $entry_name
 * @return int
 */
function checkMenuEntry($entry_name)
{
	$sql = '
		SELECT active FROM dw_game_menu
		WHERE menu_name = "'.mysql_real_escape_string($entry_name).'"
	';
	return \util\mysql\query($sql);
}

/**
 * get the total number of messages that are not read
 * @author Neithan
 * @param int $recipient
 * @return int
 */
function getUnreadMessagesCount($recipient)
{
	$sql = '
		SELECT COUNT(msgid) msg_count
		FROM dw_message
		WHERE uid_recipient = '.mysql_real_escape_string($recipient).'
			AND unread = 1
			AND !del_recipient
	';
	return (int)\util\mysql\query($sql);
}

/**
 * get all missionary entries for the specified user
 * @author Neithan
 * @param int $uid
 * @return int
 */
function getMissionary($uid)
{
	$sql = '
		SELECT COUNT(*)
		FROM dw_missionary
		WHERE uid='.mysql_real_escape_string($uid).'
	';
	return (int)\util\mysql\query($sql);
}

/**
 * check if the users language is existing and active
 * @param string $language
 * @return boolean
 */
function checkLanguageIsActive($language)
{
	$sql = '
		SELECT language
		FROM dw_languages
		WHERE language = "'.mysql_real_escape_string($language).'"
			AND active
	';
	return (bool)\util\mysql\query($sql);
}

/**
 * get the fallback language
 * @return string
 */
function getFallbackLanguage()
{
	$sql = '
		SELECT language
		FROM dw_language
		WHERE fallback
	';
	return \util\mysql\query($sql);
}

/**
 * get the language id to the given language code
 * @author Neithan
 * @param String $code
 * @return int
 */
function getLanguageIDByCode($code)
{
	$sql = '
		SELECT language_id
		FROM dw_languages
		WHERE language = '.\util\mysql\sqlval($code).'
	';
	return \util\mysql\query($sql);
}