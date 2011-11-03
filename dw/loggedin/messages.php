<?php
require_once('loggedin/header.php');

lib_bl_general_loadLanguageFile('messages');
$smarty->assign('lang', $lang);

//check for read messages that are older than 14 days and mark them as deleted
lib_bl_messages_checkReadMessages($_SESSION['user']->getUID());

if (!$_GET['mmode'])
{
	$messages = lib_bl_messages_getMessages($_SESSION['user']->getUID(), array(1, 2));
	$eventMessages = lib_bl_messages_getMessages($_SESSION['user']->getUID(), array(3, 4));
	$messageCount = lib_bl_messages_getCounts($messages);
	$eventMessageCount = lib_bl_messages_getCounts($eventMessages);
	$smarty->assign('messageCount', $messageCount);
	$smarty->assign('eventMessageCount', $eventMessageCount);
}
elseif ($_GET['mmode'] == 'received') //received messages
{
	if ($_POST['deleting'])
	{
		$msgids = count($_POST['delete']);
		for ($i = 0; $i < $msgids; $i++)
		{
			$msgid = $_POST['delete'][$i];

			if (lib_bl_messages_checkUser($msgid, $_SESSION['user']->getUID(), 1))
				lib_bl_general_delMessage($msgid, $_SESSION['user']->getUID());
			else
				$n = $msgids;
		}

		$smarty->assign('infoMessage', $lang['messagesDeleted']);
	}

	if ($_GET["do"] == "archive")
	{
		if (lib_bl_messages_archive($_GET['msgid']))
		{
			$smarty->assign('infoMessage', $lang['archived']);
			unset($_GET['msgid']);
		}
	}

	if (!$_GET['msgid'])
		$smarty->assign('messages', lib_bl_messages_getMessages($_SESSION['user']->getUID(), array(1, 2)));
	else
	{
		if (lib_bl_messages_checkUser($_GET['msgid'], $_SESSION['user']->getUID(), 1))
		{
			$smarty->assign('message', lib_bl_messages_getMessage($_GET['msgid']));
			$readerg = @mysql_query('UPDATE dw_message SET unread="0", date_read="'.time().'" WHERE msgid='.$_GET['msgid'].'', $con);
			$messageArray = lib_bl_messages_getMessage($_GET['msgid']);
			if (count($messageArray) > 0)
			{
				$uid_sender = $messageArray["uid_sender"];
				$date = $messageArray["date"];
				$title = $messageArray["title"];
				$message = $messageArray["message"];
			}
			if ($uid_sender != -1)
				$sender_nick = $messageArray["nick"];
			else
				$sender_nick = "Kaiser";
		}
		else
			lib_bl_general_redirect('index.php?chose=messages');
	}
}
elseif ($_GET['mmode'] == "sent") //sent messages
{
	if ($_POST['deleting'])
	{
		$msgids = count($_POST['delete']);
		for ($i = 0; $i < $msgids; $i++)
		{
			$msgid = $_POST['delete'][$i];

			if (lib_bl_messages_checkUser($msgid, $_SESSION['user']->getUID(), 1))
				lib_bl_general_delMessage($msgid, $_SESSION['user']->getUID());
			else
				$n = $msgids;
		}

		$smarty->assign('infoMessage', $lang['messagesDeleted']);
	}

	if (!$_GET['msgid'])
		$smarty->assign('messages', lib_bl_messages_getSentMessages($_SESSION['user']->getUID(), array(1, 2)));
	else
	{
		if (lib_bl_messages_checkUser($_GET['msgid'], $_SESSION['user']->getUID(), 2))
		{
			$smarty->assign('message', lib_bl_messages_getSentMessage($_GET['msgid']));
			$readerg = @mysql_query('UPDATE dw_message SET unread="0", date_read="'.time().'" WHERE msgid='.$_GET['msgid'].'', $con);
			$messageArray = lib_bl_messages_getMessage($_GET['msgid']);
			if (count($messageArray) > 0)
			{
				$uid_sender = $messageArray["uid_sender"];
				$date = $messageArray["date"];
				$title = $messageArray["title"];
				$message = $messageArray["message"];
			}
			if ($uid_sender != -1)
				$sender_nick = $messageArray["nick"];
			else
				$sender_nick = "Kaiser";
		}
		else
			lib_bl_general_redirect('index.php?chose=messages');
	}
}
elseif ($_GET['mmode'] == "archive") //archived messages
{
	if ($_POST['deleting'])
	{
		$msgids = count($_POST['delete']);
		for ($i = 0; $i < $msgids; $i++)
		{
			$msgid = $_POST['delete'][$i];

			if (lib_bl_messages_checkUser($msgid, $_SESSION['user']->getUID(), 1))
				lib_bl_general_delMessage($msgid, $_SESSION['user']->getUID());
			else
				$n = $msgids;
		}

		$smarty->assign('infoMessage', $lang['messagesDeleted']);
	}

	if (!$_GET['msgid'])
		$smarty->assign('messages', lib_bl_messages_getArchivedMessages($_SESSION['user']->getUID(), array(1, 2)));
	else
	{
		if (lib_bl_messages_checkUser($_GET['msgid'], $_SESSION['user']->getUID(), 1))
		{
			$smarty->assign('message', lib_bl_messages_getArchivedMessage($_GET['msgid']));
			$readerg = @mysql_query('UPDATE dw_message SET unread="0", date_read="'.time().'" WHERE msgid='.$_GET['msgid'].'', $con);
			$messageArray = lib_bl_messages_getMessage($_GET['msgid']);
			if (count($messageArray) > 0)
			{
				$uid_sender = $messageArray["uid_sender"];
				$date = $messageArray["date"];
				$title = $messageArray["title"];
				$message = $messageArray["message"];
			}
			if ($uid_sender != -1)
				$sender_nick = $messageArray["nick"];
			else
				$sender_nick = "Kaiser";
		}
		else
			lib_bl_general_redirect('index.php?chose=messages');
	}
}
elseif ($_GET['mmode'] == "event") //event messages
{
	if ($_POST['deleting'])
	{
		$msgids = count($_POST['delete']);
		for ($i = 0; $i < $msgids; $i++)
		{
			$msgid = $_POST['delete'][$i];

			if (lib_bl_messages_checkUser($msgid, $_SESSION['user']->getUID(), 1))
				lib_bl_general_delMessage($msgid, $_SESSION['user']->getUID());
			else
				$n = $msgids;
		}

		$smarty->assign('infoMessage', $lang['messagesDeleted']);
	}

	if (!$_GET['msgid'])
		$smarty->assign('messages', lib_bl_messages_getMessages($_SESSION['user']->getUID(), array(3, 4)));
	else
	{
		if (lib_bl_messages_checkUser($_GET['msgid'], $_SESSION['user']->getUID(), 1))
		{
			$smarty->assign('message', lib_bl_messages_getMessage($_GET['msgid']));
			$readerg = @mysql_query('UPDATE dw_message SET unread="0", date_read="'.time().'" WHERE msgid='.$_GET['msgid'].'', $con);
			$messageArray = lib_bl_messages_getMessage($_GET['msgid']);
			if (count($messageArray) > 0)
			{
				$uid_sender = $messageArray["uid_sender"];
				$date = $messageArray["date"];
				$title = $messageArray["title"];
				$message = $messageArray["message"];
			}
			if ($uid_sender != -1)
				$sender_nick = $messageArray["nick"];
			else
				$sender_nick = "Kaiser";
		}
		else
			lib_bl_general_redirect('index.php?chose=messages');
	}
}
elseif ($_GET['mmode'] == "new") //write message
{
	if (!$_POST['sent'])
	{
		$smarty->assign('recipient', 1);
		$smarty->assign('message', array('recipient' => lib_bl_general_uid2nick($_GET['recipient'])));
	}
	elseif ($_POST['sent'] == 1)
	{
		if (strcasecmp($_POST['recipient'], $lang['emperor']) != 0)
		{
			if ($_POST['recipient'] && $_POST['message'])
			{
				$recipientUID = lib_bl_general_nick2uid($_POST['recipient']);
				if ($recipientUID)
				{
					$senderg = lib_bl_general_sendMessage($_SESSION['user']->getUID(), $recipientUID, $_POST['title'], $_POST['message'], 1);

					if ($senderg)
						$smarty->assign('infoMessage', sprintf($lang['messageSent'], $_POST['recipient']));
					else
						$smarty->assign('infoMessage', $lang['failedSending']);
				}
				else
					$smarty->assign('infoMessage', $lang['notFound']);
			}
			else
				$smarty->assign('infoMessage', $lang['noMessageRecipient']);
		}
		else
			$smarty->assign('infoMessage', $lang['sendEmperor']);
	}
}
elseif ($_GET['mmode'] == "aw") //answer
{
	if (!$_POST['message'])
	{
		$messageArray = lib_bl_messages_getMessage($_GET['msgid']);

		if ($messageArray)
		{
			$exp_msg = explode("\n", $messageArray['message']);
			$new_message = sprintf($lang["oldMessage"], lib_bl_general_uid2nick($messageArray['uid_sender']));

			for ($n = 0; $n < count($exp_msg); $n++)
			{
				if (substr($exp_msg[$n], 0, 1) == ">" or substr($exp_msg[$n], 1, 1) == ">")
					$old = ">";
				else
					$old = "> ";

				if (substr($exp_msg[$n], 0, 3) != ">>>")
				{
					if ($n + 1 == count($exp_msg))
						$new_message .= $old.$exp_msg[$n];
					else
						$new_message .= $old.$exp_msg[$n]."\n";
				}
				else
					$n = count($exp_msg);
			}

			if (!$messageArray['title'])
				$res = explode(" ", $lang["notitle"]);
			else
				$res = explode(' ', $messageArray['title']);

			$aw_title = 'Re: ';
			foreach ($res as $re)
			{
				if ($re != "Re:")
				{
					$aw_title .= $re;
					$aw_title .= " ";
				}
			}

			$smarty->assign('message', array(
				'title' => $aw_title,
				'message' => $new_message,
				'recipient' => lib_bl_general_uid2nick($messageArray['uid_sender']),
			) + $messageArray);
			$smarty->assign('recipient', 1);
		}
	}
	elseif ($_POST['message'] == 1) //send answer message
	{
		if (strcasecmp($_POST['recipient'], $lang['emperor']) != 0)
		{
			if ($_POST['recipient'] && $_POST['message'])
			{
				$recipientUID = lib_bl_general_nick2uid($_POST['recipient']);
				if ($recipientUID)
				{
					$senderg = lib_bl_general_sendMessage($_SESSION['user']->getUID(), $recipientUID, $_POST['title'], $_POST['message'], 1);

					if ($senderg)
						$smarty->assign('infoMessage', sprintf($lang['messageSent'], $_POST['recipient']));
					else
						$smarty->assign('infoMessage', $lang['failedSending']);
				}
				else
					$smarty->assign('infoMessage', $lang['notFound']);
			}
			else
				$smarty->assign('infoMessage', $lang['noMessageRecipient']);
		}
		else
			$smarty->assign('infoMessage', $lang['sendEmperor']);
	}
}
elseif ($_GET['mmode'] == 'newall') //write a message to all players
{
	if ($_POST['message'])
	{
		$userList = lib_bl_user_getACPUserList();

		foreach ($userList as $user)
			lib_bl_general_sendMessage($_SESSION['user']->getUID(), $user['uid'], $_POST['title'], $_POST['message'], 2);

		$smarty->assign('infoMessage', $lang['messagesSent']);
	}
}
require_once("loggedin/footer.php");

$smarty->display('messages.tpl');