<?php
/**
 * get all messages of the specified type
 * @author Neithan
 * @param int $type
 * @param String $for possible values: sender, recipient
 * @return array
 */
function lib_dal_messages_getMessages($uid, $type, $for)
{
	$sql = '
		SELECT * FROM dw_message
		WHERE uid_'.$for.' = '.mysql_real_escape_string($uid).'
			AND type = '.mysql_real_escape_string($type).'
			AND !del_'.$for.'
	';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * mark the specified message either for the sender or the recipient as deleted
 * @author Neithan
 * @param int $msgid
 * @param String $deleteFor possible values: sender, recipient
 * @return int
 */
function lib_dal_messages_markAsDeleted($msgid, $deleteFor)
{
	if ($deleteFor == 'sender' || $deleteFor == 'recipient')
	{
		$sql = '
			UPDATE dw_message
			SET del_'.$deleteFor.' = 1
			WHERE msgid = '.mysql_real_escape_string($msgid).'
				AND !unread
		';
		return lib_util_mysqlQuery($sql);
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
function lib_dal_messages_markRead($msgid)
{
	$sql = '
		UPDATE dw_message
		SET unread = 0
		WHERE msgid = '.mysql_real_escape_string($msgid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * archive a message
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function lib_dal_messages_archive($msgid)
{
	$sql = '
		UPDATE dw_message
		SET archive = 1
		WHERE msgid = '.mysql_real_escape_string($msgid).'
	';
	return lib_util_mysqlQuery($sql);
}