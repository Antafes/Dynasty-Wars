<?php
namespace dal\messages;

/**
 * get all messages of the specified type
 * @author Neithan
 * @param int $type
 * @param String $for possible values: sender, recipient
 * @return array
 */
function getMessages($uid, $type, $for, $archived = 0)
{
	$sql = '
		SELECT * FROM dw_message
		WHERE uid_'.$for.' = '.mysql_real_escape_string($uid).'
			AND type = '.mysql_real_escape_string($type).'
			AND !del_'.$for.'
			AND archive = '.mysql_real_escape_string($archived).'
		ORDER BY create_datetime DESC
	';
	return util\mysql\query($sql, true);
}

/**
 * get the specified message
 * @author Neithan
 * @param int $msgid
 * @param String $for
 * @return array
 */
function getMessage($msgid, $for, $archived = 0)
{
	$sql = '
		SELECT * FROM dw_message
		WHERE msgid = '.mysql_real_escape_string($msgid).'
			AND !del_'.$for.'
			AND archive = '.mysql_real_escape_string($archived).'
	';
	return util\mysql\query($sql);
}

/**
 * mark the specified message either for the sender or the recipient as deleted
 * @author Neithan
 * @param int $msgid
 * @param String $deleteFor possible values: sender, recipient
 * @param boolean $forceDeletion will force the deletion of unread messages
 * @return int
 */
function markAsDeleted($msgid, $deleteFor, $forceDeletion)
{
	if ($deleteFor == 'sender' || $deleteFor == 'recipient')
	{
		$sql = '
			UPDATE dw_message
			SET del_'.$deleteFor.' = 1
			WHERE msgid = '.mysql_real_escape_string($msgid).'
		';

		if (!$forceDeletion)
			$sql .= 'AND !unread';

		return util\mysql\query($sql);
	}
	else
		return 0;
}

/**
 * mark the specified message as read
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function markRead($msgid)
{
	$sql = '
		UPDATE dw_message
		SET unread = 0,
			read_datetime = NOW()
		WHERE msgid = '.mysql_real_escape_string($msgid).'
	';
	return util\mysql\query($sql);
}

/**
 * archive a message
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function archive($msgid)
{
	$sql = '
		UPDATE dw_message
		SET archive = 1
		WHERE msgid = '.mysql_real_escape_string($msgid).'
	';
	return util\mysql\query($sql);
}

/**
 * get the recipient of the message
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function checkRecipient($msgid)
{
	$sql = 'SELECT uid_recipient FROM dw_message WHERE msgid="'.mysql_real_escape_string($msgid).'"';
	return util\mysql\query($sql);
}
/**
 * get the sender of the message
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function checkSender($msgid)
{
	$sql = 'SELECT uid_sender FROM dw_message WHERE msgid="'.mysql_real_escape_string($msgid).'"';
	return util\mysql\query($sql);
}