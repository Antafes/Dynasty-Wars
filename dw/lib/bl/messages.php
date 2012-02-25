<?php
namespace bl\messages;

/**
 * get all messages of the specified type(s)
 * @author Neithan
 * @param int/array $type
 * @return array
 */
function getMessages($uid, $type)
{
	$messagesArray = array();
	if (is_array($type))
	{
		foreach ($type as $t)
		{
			$messages = dal\messages\getMessages($uid, $t, 'recipient');
			if ($messages)
				$messagesArray += $messages;
		}
	}
	else
		$messagesArray = dal\messages\getMessages($uid, $type, 'recipient');

	if ($messagesArray)
	{
		foreach ($messagesArray as &$message)
			$message['sender'] = bl\general\uid2nick($message['uid_sender']);
		unset($message);
	}

	return $messagesArray;
}

/**
 * get the specified message
 * @author Neithan
 * @param int $msgid
 * @return array
 */
function getMessage($msgid)
{
	global $lang;

	$message = dal\message\getMessage($msgid, 'recipient');

	if ($message)
	{
		$message['sender'] = lib_bl_general_uid2nick ($message['uid_sender']);
		$messageDate = \DWDateTime::createFromFormat('Y-m-d H:i:s', $message['create_datetime']);
		$message['sentDate'] = $messageDate->format($lang['messageTimeFormat']);
	}

	$parser = new wikiparser;
	$message['message'] = preg_replace('#(\\\\r\\\\n|\\\\\\\\r\\\\\\\\n|\\\\n|\\\\\\\\n)#', "\r\n", $message['message']);
	$message['message'] = $parser->parseIt($message['message']);

	return $message;
}

/**
 * get all sent messages of the specified type(s)
 * @author Neithan
 * @param int $uid
 * @param int/array $type
 * @return array
 */
function getSentMessages($uid, $type)
{
	$messagesArray = array();
	if (is_array($type))
	{
		foreach ($type as $t)
		{
			$messages = dal\messages\getMessages($uid, $t, 'sender');
			if ($messages)
				$messagesArray += $messages;
		}
	}
	else
		$messagesArray = dal\messages\getMessages($uid, $type, 'sender');

	if ($messagesArray)
	{
		foreach ($messagesArray as &$message)
			$message['recipient'] = bl\general\uid2nick($message['uid_recipient']);
		unset($message);
	}

	return $messagesArray;
}

/**
 * get the specified sent message
 * @author Neithan
 * @param int $msgid
 * @return array
 */
function getSentMessage($msgid)
{
	global $lang;

	$message = dal\message\getMessage($msgid, 'sender');

	if ($message)
	{
		$message['recipient'] = lib_bl_general_uid2nick ($message['uid_recipient']);
		$messageDate = \DWDateTime::createFromFormat('Y-m-d H:i:s', $message['create_datetime']);
		$message['sentDate'] = $messageDate->format($lang['messageTimeFormat']);
	}

	$parser = new wikiparser;
	$message['message'] = preg_replace('#(\\\\r\\\\n|\\\\\\\\r\\\\\\\\n|\\\\n|\\\\\\\\n)#', "\r\n", $message['message']);
	$message['message'] = $parser->parseIt($message['message']);

	return $message;
}

/**
 * get all messages of the specified type(s)
 * @author Neithan
 * @param int/array $type
 * @return array
 */
function getArchivedMessages($uid, $type)
{
	$messagesArray = array();
	if (is_array($type))
	{
		foreach ($type as $t)
		{
			$messages = dal\messages\getMessages($uid, $t, 'recipient', 1);
			if ($messages)
				$messagesArray += $messages;
		}
	}
	else
		$messagesArray = dal\messages\getMessages($uid, $type, 'recipient', 1);

	if ($messagesArray)
	{
		foreach ($messagesArray as &$message)
			$message['sender'] = bl\general\uid2nick($message['uid_sender']);
		unset($message);
	}

	return $messagesArray;
}

/**
 * get the specified message
 * @author Neithan
 * @param int $msgid
 * @return array
 */
function getArchivedMessage($msgid)
{
	global $lang;

	$message = dal\message\getMessage($msgid, 'recipient', 1);

	if ($message)
	{
		$message['sender'] = bl\general\uid2nick ($message['uid_sender']);
		$messageDate = \DWDateTime::createFromFormat('Y-m-d H:i:s', $message['create_datetime']);
		$message['sentDate'] = $messageDate->format($lang['messageTimeFormat']);
	}

	$parser = new wikiparser;
	$message['message'] = preg_replace('#(\\\\r\\\\n|\\\\\\\\r\\\\\\\\n|\\\\n|\\\\\\\\n)#', "\r\n", $message['message']);
	$message['message'] = $parser->parseIt($message['message']);

	return $message;
}

/**
 * return the amount of read and unread messages
 * @author Neithan
 * @param array $messages
 * @return int
 */
function getCounts($messages)
{
	$counter = array(
		'totalMessages' => 0,
		'unreadMessages' => 0,
	);

	foreach ($messages as $message)
	{
		if ($message['unread'])
			$counter['unreadMessages']++;
		$counter['totalMessages']++;
	}

	return $counter;
}

/**
 * mark the specified message as deleted for the sender
 * @author Neithan
 * @param int $msgid
 * @param boolean $forceDeletion (default: false) will force the deletion of unread messages
 * @return int
 */
function markAsDeletedSender($msgid, $forceDeletion = false)
{
	return dal\messages\markAsDeleted($msgid, 'sender', $forceDeletion);
}

/**
 * mark the specified message as deleted for the recipient
 * @author Neithan
 * @param int $msgid
 * @param boolean $forceDeletion (default: false) will force the deletion of unread messages
 * @return int
 */
function markAsDeletedRecipient($msgid, $forceDeletion = false)
{
	return dal\messages\markAsDeleted($msgid, 'recipient', $forceDeletion);
}

/**
 * mark the specified message as read
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function markRead($msgid)
{
	if (is_int($msgid))
		return dal\messages\markRead ($msgid);
	else
		return 0;
}

/**
 * check if there are read messages that are older than 14 days and mark them
 * as deleted.
 * @author Neithan
 */
function checkReadMessages($uid)
{
	$messages = getMessages($uid, array(1, 2, 3, 4));

	foreach ($messages as $message)
	{
		if (!$message['unread'])
		{
			$messageDate = \DWDateTime::createFromFormat('Y-m-d H:i:s', $message['read_datetime']);
			$currentDate = new \DWDateTime();
			$dateDiff = $currentDate->diff($messageDate);

			if ($dateDiff->d >= 14 || $dateDiff->m || $dateDiff->y)
				markAsDeletedRecipient($message['msgid']);
		}
	}
}

/**
 * archive a message
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function archive($msgid)
{
	return dal\messages\archive($msgid);
}

/**
 * check if the message is for this user
 * @author Neithan
 * @param int $msgid
 * @param int $uid
 * @param int $mode 1 for check recipient, 2 for check sender
 * @return <int> returns 1 if the message is for this user, otherwise 0
 */
function checkUser($msgid, $uid, $mode)
{
	if ($mode == 1)
		$checkuid = dal\messages\checkRecipient($msgid);
	elseif ($mode == 2)
		$checkuid = dal\messages\checkSender($msgid);

	if ($uid == $checkuid)
		return 1;
	else
		return 0;
}