<?php
/**
 * get all messages of the specified type(s)
 * @author Neithan
 * @param int/array $type
 * @return array
 */
function lib_bl_messages_getMessages($uid, $type)
{
	if (is_array($type))
	{
		$messagesArray = array();
		foreach ($type as $t)
		{
			$messages = lib_dal_messages_getMessages($uid, $t, 'recipient');
			if ($messages)
				$messagesArray += $messages;
		}

		return $messagesArray;
	}
	else
		return lib_dal_messages_getMessages($uid, $type, 'recipient');
}

/**
 * return the amount of read and unread messages
 * @author Neithan
 * @param array $messages
 * @return int
 */
function lib_bl_messages_getCounts($messages)
{
	$counter = array(
		'readMessages' => 0,
		'unreadMessages' => 0,
	);

	foreach ($messages as $message)
	{
		if ($message['unread'])
			$counter['unreadMessages']++;
		else
			$counter['readMessages']++;
	}

	return $counter;
}

/**
 * mark the specified message as deleted for the sender
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function lib_bl_messages_markAsDeletedSender($msgid)
{
	return lib_dal_messages_markAsDeleted($msgid, 'sender');
}

/**
 * mark the specified message as deleted for the recipient
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function lib_bl_messages_markAsDeletedRecipient($msgid)
{
	return lib_dal_messages_markAsDeleted($msgid, 'recipient');
}

/**
 * mark the specified message as read
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function lib_bl_messages_markRead($msgid)
{
	if (is_int($msgid))
		return lib_dal_messages_markRead ($msgid);
	else
		return 0;
}

/**
 * check if there are read messages that are older than 14 days and mark them
 * as deleted.
 * @author Neithan
 */
function lib_bl_messages_checkReadMessages($uid)
{
	$messages = lib_bl_messages_getMessages($uid, array(1, 2, 3, 4));

	foreach ($messages as $message)
	{
		if (!$message['unread'])
		{
			$messageDate = new DateTime(date('Y-m-d H:i:s', $message['date_read']));
			$currentDate = new DateTime();
			$dateDiff = $currentDate->diff($messageDate);

			if ($dateDiff->d >= 14 || $dateDiff->m || $dateDiff->y)
				lib_bl_messages_markAsDeletedRecipient($message['msgid']);
		}
	}
}

/**
 * archive a message
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function lib_bl_messages_archive($msgid)
{
	return lib_dal_messages_archive($msgid);
}