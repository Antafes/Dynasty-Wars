<?php
require_once('loggedin/header.php');
require_once('lib/bl/checks.inc.php');
require_once('lib/bl/messages.inc.php');

lib_bl_general_loadLanguageFile('messages');
$smarty->assign('lang', $lang);

$cbutton = array();
for ($n = 0, $m = 1; $n < $_POST['cbuttons']; $n++, $m++)
	$cbutton[$n] = $_POST['cbutton'.$m];

if ($_GET['mmode'] == "aw")
{
	$newaw = $_POST["newaw"];
	if (!$newaw)
	{
		$message_array = lib_bl_general_getMessage($_GET["aw_msgid"]);
		if (count($message_array) > 0)
		{
			$aw_title = $message_array['title'];
			$aw_message = $message_array['message'];
			$aw_recipient = lib_bl_general_uid2nick($message_array['uid_sender']);
			$exp_msg = explode("\n", $aw_message);
			$lines = count($exp_msg);
			$n = 0;
			unset($new_message);
			$new_message = sprintf($lang["oldmsg"], $aw_recipient);
			while ($n < $lines)
			{
				if (substr($exp_msg[$n], 0, 1) == ">" or substr($exp_msg[$n], 1, 1) == ">")
					$old = ">";
				else
					$old = "> ";
				if (substr($exp_msg[$n], 0, 3) != ">>>")
				{
					if ($n+1 == $lines)
						$new_message .= $old.$exp_msg[$n];
					else
						$new_message .= $old.$exp_msg[$n]."\n";
				}
				else
					$n = $lines;
				$n++;
			}
		}
	}
	if (!$aw_title)
		$aw_title = $lang["notitle"];
	$res = explode(" ", $aw_title);
	unset($aw_title);
	foreach ($res as $re)
	{
		if ($re != "Re:")
		{
			$aw_title .= $re;
			$aw_title .= " ";
		}
	}
}

if (!$_GET['mmode'])
{
	lib_bl_messages_checkReadMessages($_SESSION['user']->getUID());
	$msgerg = @mysql_query("SELECT msgid, unread, type, date_read FROM dw_message WHERE uid_recipient='".$_SESSION['user']->getUid()."' AND NOT archive AND NOT del_recipient", $con);
	if ($msgerg)
		$lines2 = mysql_num_rows($msgerg);
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" class="table_tc"><font size="5"><?php echo htmlentities($lang["messages"]) ?></font></th>
										</tr>
										<tr>
											<td width="660" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="660" class="table_tc">
<?php
	if (!$own_uid) {
?>
												<a href="index.php?chose=messages&amp;mmode=new"><?php echo htmlentities($lang["writemsg"]) ?></a>
<?php
	} else {
?>
												&nbsp;
<?php
	}
?>
											</td>
										</tr>
<?php
	if ($_SESSION['user']->getGameRank() >= 1) {
?>
										<tr>
											<td width="660" class="table_tc">
<?php
		if (!$own_uid) {
?>
												<a href="index.php?chose=messages&amp;mmode=newall"><?php echo htmlentities($lang["writetoall"]) ?></a>
<?php
		} else {
?>
												&nbsp;
<?php
		}
?>
											</td>
										</tr>
<?php
	}

	$messages = lib_bl_messages_getMessages($_SESSION['user']->getUID(), array(1, 2));
	$eventMessages = lib_bl_messages_getMessages($_SESSION['user']->getUID(), array(3, 4));
	$messageCount = lib_bl_messages_getCounts($messages);
	$eventMessageCount = lib_bl_messages_getCounts($eventMessages);
	$smarty->assign('messageCount', $messageCount);
	$smarty->assign('eventMessageCount', $eventMessageCount);
?>
										<tr>
											<td width="660" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=received"><?php echo htmlentities($lang["received"]) ?>&nbsp;(<?php echo $unread ?>/<?php echo $number ?>)</a>
											</td>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=event"><?php echo htmlentities($lang["events"]) ?>&nbsp;(<?php echo $eveunread ?>/<?php echo $evenumber ?>)</a>
											</td>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=sended"><?php echo htmlentities($lang["sended"]) ?></a>
											</td>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=archive"><?php echo htmlentities($lang["archive"]) ?></a>
											</td>
										</tr>
										<tr>
											<td width="660" class="table_tc"><?php echo htmlentities($lang["msginfo"]) ?></td>
										</tr>
									</table>
<?php
//botschaften
}
elseif ($_GET['mmode'] == 'received')
{
	if ($_POST['delete'])
	{
		$msgids = count($cbutton);
		$n = 0;
		while ($n < $msgids) {
			$msgid = $cbutton[$n];
			$check = lib_bl_checks_checkUser($msgid, $_SESSION['user']->getUID(), 1);
			if ($check) {
				$deleted = lib_bl_general_delMessage($msgid, $_SESSION['user']->getUID());
				$n++;
			} else {
				$n = $msgids;
			}
		}
	}

	if ($_GET["do"] == "archive")
		if (lib_bl_messages_archive($_GET['msgid']))
			$smarty->assign('infoMessage', $lang['archived']);

	$smarty->assign('messages', lib_bl_messages_getMessages($_SESSION['user']->getUID(), array(1, 2)));
	$msgerg = @mysql_query("SELECT msgid, uid_sender, date, title, unread, message, nick FROM dw_message LEFT OUTER JOIN dw_user ON uid_sender=uid WHERE uid_recipient='".$_SESSION['user']->getUid()."' and (type='1' OR type='2') AND NOT archive AND NOT del_recipient ORDER BY `msgid` ASC", $con);
	if ($msgerg) {
		$lines = mysql_num_rows($msgerg);
	}
	if (!$_GET['msg_read']) {
?>
									<form method="post" action="index.php?chose=messages&amp;mmode=received" name="del">
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" colspan="5" class="table_tc"><font size="5"><?php echo htmlentities($lang["recmsgs"]) ?></font></th>
										</tr>
										<tr>
											<td width="660" colspan="5" class="no_content">&nbsp;</td>
										</tr>
<?php
		if ($archived) {
?>
										<tr>
											<th width="660" colspan="5" class="table_tc"><?php echo htmlentities($lang["archived"]) ?></th>
										</tr>
<?php
		}
?>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="210" class="table_tc"><?php echo htmlentities($lang["message"]) ?></td>
											<td width="140" class="table_tl"><?php echo htmlentities($lang["sender"]) ?></td>
											<td width="150" class="table_tc"><input type="submit" name="delete" value="<?php echo htmlentities($lang["delete"]) ?>"/></td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
<?php
		if (!$lines) {
?>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="350" colspan="2" class="table_tc">
												<?php echo htmlentities($lang["nomsgs"]) ?>
											</td>
											<td width="150" class="table_tc">&nbsp;</td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
<?php
		}
		$n = $lines - 1;
		while ($n >= 0) {
			$uid_sender = mysql_result($msgerg, $n, 1);
			if ($uid_sender >= 1) {
				$sender_nick = mysql_result($msgerg, $n, 6);
			} else {
				$sender_nick = "Kaiser";
			}
?>
										<tr>
											<td width="100" class="table_tr">&nbsp;</td>
											<td width="350" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=received&amp;msgid=<?php echo mysql_result($msgerg, $n, 0) ?>&amp;msgread=1">
<?php
			if (mysql_result($msgerg, $n, 4) == 1) {
?>
												<img src="pictures/msg_unread.gif" alt="<?php echo htmlentities($lang["unread"]) ?>" title="<?php echo htmlentities($lang["unread"]) ?>" border="0"/>
<?php
			} elseif (mysql_result($msgerg, $n, 4) == 0) {
?>
												<img src="pictures/msg_read.gif" alt="<?php echo htmlentities($lang["read"]) ?>" title="<?php echo htmlentities($lang["read"]) ?>" border="0"/>
<?php
			}
			if (mysql_result($msgerg, $n, 3)) {
				echo htmlentities(mysql_result($msgerg, $n, 3));
			} else {
				echo htmlentities($lang["notitle"]);
			}
?>
												</a>
											</td>
											<td width="140" class="table_tl"><?php echo htmlentities($sender_nick) ?></td>
											<td width="150" class="table_tc">
												<input type="checkbox" name="cbutton<?php echo $n + 1 ?>" onclick="selectThis()" value="<?php echo mysql_result($msgerg, $n, 0) ?>"/>
											</td>
											<td width="70" class="table_tc">&nbsp;</td>
										</tr>
<?php
			$n--;
		}
?>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="350" class="table_tc" colspan="2">
												<a href="index.php?chose=messages"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
											<td width="150" class="table_tc">
												<label for="markall"><?php echo htmlentities($lang["markall"]) ?>&nbsp;</label><input name="cbuttonAll" id="markall" type="checkbox" onclick="all()"/>
												<input type="hidden" name="cbuttons" value="<?php echo $lines ?>"/>
											</td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
									</table>
									</form>
<?php
//nachrichten lesen
	} elseif ($_GET['msg_read'] == 1 and $_GET['mmode'] == "received") {
		$check = lib_bl_checks_checkUser($_GET['msgid'], $_SESSION['user']->getUID(), 1);
		if ($check) {
			$readerg = @mysql_query('UPDATE dw_message SET unread="0", date_read="'.time().'" WHERE msgid='.$_GET['msgid'].'', $con);
			$message_array = lib_bl_general_getMessage($_GET['msgid']);
			if (count($message_array) > 0)
			{
				$uid_sender = $message_array["uid_sender"];
				$date = $message_array["date"];
				$title = $message_array["title"];
				$message = $message_array["message"];
			}
			if ($uid_sender != -1)
				$sender_nick = $message_array["nick"];
			else
				$sender_nick = "Kaiser";
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" colspan="4" class="table_tc"><font size="5"><?php echo htmlentities($lang["message"]) ?></font></th>
										</tr>
										<tr>
											<td width="160" rowspan="5" class="no_content">&nbsp;</td>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["sender"]) ?>:</td>
											<td width="175" class="table_tc"><?php echo htmlentities($sender_nick) ?></td>
											<td width="160" rowspan="5" class="table_tc">&nbsp;</td>
										</tr>
										<tr>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["senddate"]) ?>:</td>
											<td width="175" class="table_tc"><?php echo date($lang["msgtimeformat"], $date) ?></td>
										</tr>
										<tr>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["title"]) ?>:</td>
											<td width="175" class="table_tc"><?php if ($title) { echo htmlentities($title); } else { echo htmlentities($lang["notitle"]); } ?>
											</td>
										</tr>
										<tr>
											<td width="350" colspan="2" class="table_tl"><?php echo nl2br($message) ?></td>
										</tr>
										<tr>
											<td width="350" class="no_content" colspan="2">
												<table width="340" class="no_content">
													<tr>
														<td width="115" class="table_tc">
															<a href="index.php?chose=messages&amp;mmode=aw&amp;aw_msgid=<?php echo $_GET['msgid'] ?>"><?php echo htmlentities($lang["answer"]) ?></a>
														</td>
														<td width="110" class="table_tc">
															<a href="index.php?chose=messages&amp;mmode=received&amp;do=archive&amp;msgid=<?php echo $_GET['msgid'] ?>"><?php echo htmlentities($lang["arch"]) ?></a>
														</td>
														<td width="115" class="table_tc">
															<a href="index.php?chose=messages&amp;mmode=received"><?php echo htmlentities($lang["back"]) ?></a>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
<?php
		} else {
?>
									<meta http-equiv="refresh" content="0;URL=index.php?chose=messages" />
<?php
		}
	}
//sended messages
} elseif ($_GET['mmode'] == "sent") {
	if ($_POST['delete']) {
		$msgids = count($cbutton);
		$n = 0;
		while ($n < $msgids) {
			$msgid = $cbutton[$n];
			$check = lib_bl_checks_checkUser($msgid, $_SESSION['user']->getUID(), 2);
			if ($check) {
				$deleted = lib_bl_general_delMessage($msgid, $_SESSION['user']->getUID());
				$n++;
			} else {
				$n = $msgids;
			}
		}
	}
	$msgerg = @mysql_query("SELECT msgid, uid_recipient, date, title, unread, message, nick FROM dw_message LEFT OUTER JOIN dw_user ON uid_recipient=uid WHERE uid_sender='".$_SESSION['user']->getUid()."' and (type='1' OR type='2') AND NOT archive AND NOT del_sender ORDER BY `msgid` ASC", $con);
	if ($msgerg) {
		$lines = mysql_num_rows($msgerg);
	}
	if (!$_GET['msg_read']) {
?>
									<form method="post" action="index.php?chose=messages&amp;mmode=sended" name="del">
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" colspan="5" class="table_tc"><font size="5"><?php echo htmlentities($lang["sendmsgs"]) ?></font></th>
										</tr>
										<tr>
											<td width="660" colspan="5" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="210" class="table_tc"><?php echo htmlentities($lang["message"]) ?></td>
											<td width="140" class="table_tl"><?php echo htmlentities($lang["recipient"]) ?></td>
											<td width="150" class="table_tc"><input type="submit" name="delete" value="<?php echo htmlentities($lang["delete"]) ?>"/></td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
<?php
		if (!$lines) {
?>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="350" colspan="2" class="table_tc">
												<?php echo htmlentities($lang["nomsgs"]) ?>
											</td>
											<td width="150" class="table_tc">&nbsp;</td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
<?php
		}
		$n = $lines - 1;
		while ($n >= 0) {
			$uid_recipient = mysql_result($msgerg, $n, 1);
			if ($uid_recipient >= 1) {
				$recipient_nick = mysql_result($msgerg, $n, 6);
			} else {
				$recipient_nick = "Kaiser";
			}
?>
										<tr>
											<td width="100" class="table_tr">&nbsp;</td>
											<td width="350" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=sended&amp;msgid=<?php echo mysql_result($msgerg, $n, 0) ?>&amp;msgread=1">
<?php
			if (mysql_result($msgerg, $n, 3)) {
				echo htmlentities(mysql_result($msgerg, $n, 3));
			} else {
				echo htmlentities($lang["notitle"]);
			}
?>
												</a>
											</td>
											<td width="140" class="table_tl"><?php echo htmlentities($recipient_nick) ?></td>
											<td width="150" class="table_tc">
												<input type="checkbox" name="cbutton<?php echo $n + 1 ?>" onclick="selectThis()" value="<?php echo mysql_result($msgerg, $n, 0) ?>"/>
											</td>
											<td width="70" class="table_tc">&nbsp;</td>
										</tr>
<?php
			$n--;
		}
?>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="350" class="table_tc" colspan="2">
												<a href="index.php?chose=messages"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
											<td width="150" class="table_tc">
												<label for="markall"><?php echo htmlentities($lang["markall"]) ?>&nbsp;</label><input name="cbuttonAll" id="markall" type="checkbox" onclick="all()"/>
												<input type="hidden" name="cbuttons" value="<?php echo $lines ?>"/>
											</td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
									</table>
									</form>
<?php
//nachrichten lesen
	} elseif ($_GET['msg_read'] == 1 and $_GET['mmode'] == "sended") {
		$check = lib_bl_checks_checkUser($_GET['msgid'], $_SESSION['user']->getUID(), 2);
		if ($check) {
			$readerg = @mysql_query('UPDATE dw_message SET unread="0", date_read="'.time().'" WHERE msgid='.$_GET['msgid'].'', $con);
			$readerg2 = @mysql_query('SELECT uid_recipient, date, title, message, nick FROM dw_message LEFT OUTER JOIN dw_user ON uid_recipient=uid WHERE msgid='.$_GET['msgid'].'', $con);
			if ($readerg2) {
				$read = mysql_fetch_array($readerg2);
				$uid_recipient = $read["uid_recipient"];
				$date = $read["date"];
				$title = $read["title"];
				$message = $read["message"];
			}
			if ($uid_recipient != -1) {
				$recipient_nick = $read["nick"];
			} else {
				$recipient_nick = "Kaiser";
			}
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" colspan="4" class="table_tc"><font size="5"><?php echo htmlentities($lang["message"]) ?></font></th>
										</tr>
										<tr>
											<td width="160" rowspan="5" class="no_content">&nbsp;</td>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["recipient"]) ?>:</td>
											<td width="175" class="table_tc"><?php echo htmlentities($recipient_nick) ?></td>
											<td width="160" rowspan="5" class="table_tc">&nbsp;</td>
										</tr>
										<tr>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["senddate"]) ?>:</td>
											<td width="175" class="table_tc"><?php echo date($lang["msgtimeformat"], $date) ?></td>
										</tr>
										<tr>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["title"]) ?>:</td>
											<td width="175" class="table_tc"><?php if ($title) { echo htmlentities($title); } else { echo htmlentities($lang["notitle"]); } ?>
											</td>
										</tr>
										<tr>
											<td width="350" colspan="2" class="table_tl"><?php echo nl2br(htmlentities($message)) ?></td>
										</tr>
										<tr>
											<td width="350" class="table_tc" colspan="2">
												<a href="index.php?chose=messages&amp;mmode=sended"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
										</tr>
									</table>
<?php
		} else {
?>
									<meta http-equiv="refresh" content="0;URL=index.php?chose=messages" />
<?php
		}
	}
//archived messages
} elseif ($_GET['mmode'] == "archive") {
	if ($_POST['delete']) {
		$msgids = count($cbutton);
		$n = 0;
		while ($n < $msgids) {
			$msgid = $cbutton[$n];
			$check = lib_bl_checks_checkUser($msgid, $_SESSION['user']->getUID(), 1);
			if ($check) {
				$deleted = lib_bl_general_delMessage($msgid, $_SESSION['user']->getUID());
				$n++;
			} else {
				$n = $msgids;
			}
		}
	}
	$msgerg = @mysql_query("SELECT msgid, uid_sender, date, title, unread, message, nick FROM dw_message LEFT OUTER JOIN dw_user ON uid_sender=uid WHERE uid_recipient='".$_SESSION['user']->getUid()."' and (type='1' OR type='2') AND archive AND NOT del_recipient ORDER BY `msgid` ASC", $con);
	if ($msgerg) {
		$lines = mysql_num_rows($msgerg);
	}
	if (!$_GET['msg_read']) {
?>
									<form method="post" action="index.php?chose=messages&amp;mmode=archive" name="del">
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" colspan="5" class="table_tc"><?php echo htmlentities($lang["archive"]) ?></th>
										</tr>
										<tr>
											<td width="660" colspan="5" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="210" class="table_tc"><?php echo htmlentities($lang["message"]) ?></td>
											<td width="140" class="table_tl"><?php echo htmlentities($lang["sender"]) ?></td>
											<td width="150" class="table_tc"><input type="submit" name="delete" value="<?php echo htmlentities($lang["delete"]) ?>"/></td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
<?php
		if (!$lines) {
?>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="350" colspan="2" class="table_tc">
												<?php echo htmlentities($lang["nomsgs"]) ?>
											</td>
											<td width="150" class="table_tc">&nbsp;</td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
<?php
		}
		$n = $lines - 1;
		while ($n >= 0) {
			$uid_sender = mysql_result($msgerg, $n, 1);
			if ($uid_sender >= 1) {
				$sender_nick = mysql_result($msgerg, $n, 6);
			} else {
				$sender_nick = "Kaiser";
			}
?>
										<tr>
											<td width="100" class="table_tr">&nbsp;</td>
											<td width="350" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=archive&amp;msgid=<?php echo mysql_result($msgerg, $n, 0) ?>&amp;msgread=1">
<?php
			if (mysql_result($msgerg, $n, 3)) {
				echo htmlentities(mysql_result($msgerg, $n, 3));
			} else {
				echo htmlentities($lang["notitle"]);
			}
?>
												</a>
											</td>
											<td width="140" class="table_tl"><?php echo htmlentities($sender_nick) ?></td>
											<td width="150" class="table_tc">
												<input type="checkbox" name="cbutton<?php echo $n + 1 ?>" onclick="selectThis()" value="<?php echo mysql_result($msgerg, $n, 0) ?>"/>
											</td>
											<td width="70" class="table_tc">&nbsp;</td>
										</tr>
<?php
			$n--;
		}
?>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="350" class="table_tc" colspan="2">
												<a href="index.php?chose=messages"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
											<td width="150" class="table_tc">
												<label for="markall"><?php echo htmlentities($lang["markall"]) ?>&nbsp;</label><input name="cbuttonAll" id="markall" type="checkbox" onclick="all()"/>
												<input type="hidden" name="cbuttons" value="<?php echo $lines ?>"/>
											</td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
									</table>
									</form>
<?php
//nachrichten lesen
	} elseif ($_GET['msg_read'] == 1 and $_GET['mmode'] == "archive") {
		$check = lib_bl_checks_checkUser($_GET['msgid'], $_SESSION['user']->getUID(), 1);
		if ($check) {
			$readerg = @mysql_query('UPDATE dw_message SET unread="0", date_read="'.time().'" WHERE msgid='.$_GET['msgid'].'', $con);
			$readerg2 = @mysql_query('SELECT uid_sender, date, title, message, nick FROM dw_message LEFT OUTER JOIN dw_user ON uid_sender=uid WHERE msgid='.$_GET['msgid'].'', $con);
			if ($readerg2) {
				$read = mysql_fetch_array($readerg2);
				$uid_sender = $read["uid_sender"];
				$date = $read["date"];
				$title = $read["title"];
				$message = $read["message"];
			}
			if ($uid_sender != -1) {
				$sender_nick = $read["nick"];
			} else {
				$sender_nick = "Kaiser";
			}
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" colspan="4" class="table_tc"><?php echo htmlentities($lang["message"]) ?></th>
										</tr>
										<tr>
											<td width="160" rowspan="5" class="no_content">&nbsp;</td>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["sender"]) ?>:</td>
											<td width="175" class="table_tc"><?php echo htmlentities($sender_nick) ?></td>
											<td width="160" rowspan="5" class="table_tc">&nbsp;</td>
										</tr>
										<tr>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["senddate"]) ?>:</td>
											<td width="175" class="table_tc"><?php echo date($lang["msgtimeformat"], $date) ?></td>
										</tr>
										<tr>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["title"]) ?>:</td>
											<td width="175" class="table_tc"><?php if ($title) { echo htmlentities($title); } else { echo htmlentities($lang["notitle"]); } ?>
											</td>
										</tr>
										<tr>
											<td width="350" colspan="2" class="table_tl"><?php echo nl2br($message) ?></td>
										</tr>
										<tr>
											<td width="350" class="table_tc" colspan="2">
												<a href="index.php?chose=messages&amp;mmode=archive"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
										</tr>
									</table>
<?php
		} else {
?>
									<meta http-equiv="refresh" content="0;URL=index.php?chose=messages" />
<?php
		}
	}
//ereignisse
}
elseif ($_GET['mmode'] == "event")
{
	if ($_POST['delete']) {
		$msgids = count($cbutton);
		$n = 0;
		while ($n < $msgids) {
			$msgid = $cbutton[$n];
			$check = lib_bl_checks_checkUser($msgid, $_SESSION['user']->getUID(), 1);
			if ($check) {
				$deleted = lib_bl_general_delMessage($msgid, $_SESSION['user']->getUID());
				$n++;
			} else {
				$n = $msgids;
			}
		}
	}
	$msgerg = @mysql_query("SELECT msgid, uid_sender, date, title, unread, message FROM dw_message WHERE uid_recipient='".$_SESSION['user']->getUid()."' AND (type=3 OR type = 4) AND NOT archive AND NOT del_recipient", $con);
	if ($msgerg) {
		$lines = mysql_num_rows($msgerg);
	}
	if (!$_GET['msg_read']) {
?>
									<form method="post" action="index.php?chose=messages&amp;mmode=event" name="del">
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" colspan="5" class="table_tc"><?php echo htmlentities($lang["events"]) ?></th>
										</tr>
										<tr>
											<td width="660" colspan="5" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="210" class="table_tc"><?php echo htmlentities($lang["event"]) ?></td>
											<td width="140" class="table_tl"><?php echo htmlentities($lang["sender"]) ?></td>
											<td width="150" class="table_tc"><input type="submit" name="delete" value="<?php echo htmlentities($lang["delete"]) ?>"/></td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
<?php
		if (!$lines) {
?>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="350" colspan="2" class="table_tc">
												<?php echo htmlentities($lang["noevents"]) ?>
											</td>
											<td width="150" class="table_tc">&nbsp;</td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
<?php
		}
		$n = $lines - 1;
		while ($n >= 0) {
?>
										<tr>
											<td width="100" class="table_tr">&nbsp;</td>
											<td width="350" class="table_tc">
												<a href="index.php?chose=messages&amp;msgid=<?php echo mysql_result($msgerg, $n, 0) ?>&amp;mmode=event&amp;msgread=1">
<?php
			if (mysql_result($msgerg, $n, 4) == 1) {
?>
												<img src="pictures/msg_unread.gif" alt="<?php echo htmlentities($lang["unread"]) ?>" title="<?php echo htmlentities($lang["unread"]) ?>" border="0"/>
<?php
			} elseif (mysql_result($msgerg, $n, 4) == 0) {
?>
												<img src="pictures/msg_read.gif" alt="<?php echo htmlentities($lang["read"]) ?>" title="<?php echo htmlentities($lang["read"]) ?>" border="0"/>
<?php
			}
			if (mysql_result($msgerg, $n, 3)) {
				echo htmlentities(mysql_result($msgerg, $n, 3));
			} else {
				echo htmlentities($lang["notitle"]);
			}
?>
												</a>
											</td>
											<td width="140" class="table_tl"><?php echo htmlentities($lang["emperor"]) ?></td>
											<td width="150" class="table_tc">
												<input type="checkbox" name="cbutton<?php echo $n + 1 ?>" onclick="selectThis()" value="<?php echo mysql_result($msgerg, $n, 0) ?>"/>
											</td>
											<td width="70" class="table_tc">&nbsp;</td>
										</tr>
<?php
			$n--;
		}
?>
										<tr>
											<td width="100" class="no_content">&nbsp;</td>
											<td width="350" class="table_tc" colspan="2">
												<a href="index.php?chose=messages"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
											<td width="150" class="table_tc">
												<label for="markall"><?php echo htmlentities($lang["markall"]) ?>&nbsp;</label><input name="cbuttonAll" id="markall" type="checkbox" onclick="all()"/>
												<input type="hidden" name="cbuttons" value="<?php echo $lines ?>"/>
											</td>
											<td width="70" class="no_content">&nbsp;</td>
										</tr>
									</table>
									</form>
<?php
//ereignisnachrichten lesen
	} elseif ($_GET['msg_read'] == 1 and $_GET['mmode'] = "event") {
		$check = lib_bl_checks_checkUser($_GET['msgid'], $_SESSION['user']->getUID(), 1);
		if ($check) {
			$readerg = @mysql_query('UPDATE dw_message SET unread="0", date_read="'.time().'" WHERE msgid='.$_GET['msgid'].'', $con);
			$message = lib_bl_general_getMessage($_GET['msgid']);
			if (is_array($message))
			{
				$date = $message["date"];
				$title = $message["title"];
				$message = $message["message"];
			}
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" colspan="4" class="table_tc"><?php echo htmlentities($lang["event"]) ?></th>
										</tr>
										<tr>
											<td width="160" rowspan="5" class="no_content">&nbsp;</td>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["sender"]) ?>:</td>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["emperor"]) ?></td>
											<td width="160" rowspan="5" class="table_tc">&nbsp;</td>
										</tr>
										<tr>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["senddate"]) ?>:</td>
											<td width="175" class="table_tc"><?php echo date($lang["msgtimeformat"], $date) ?></td>
										</tr>
										<tr>
											<td width="175" class="table_tc"><?php echo htmlentities($lang["title"]) ?>:</td>
											<td width="175" class="table_tc"><?php if ($title) { echo htmlentities($title); } else { echo htmlentities($lang["notitle"]); } ?>
											</td>
										</tr>
										<tr>
											<td width="350" colspan="2" class="table_tl"><?php echo nl2br($message) ?></td>
										</tr>
										<tr>
											<td colspan="2" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=event"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
										</tr>
									</table>
<?php
		} else
			header('Location: index.php?chose=messages');
	}
}
elseif ($_GET['mmode'] == "new")
{
	if (!$_POST['sent']) //neue botschaft schreiben
		$smarty->assign('recipient', 1);
	elseif ($_POST['sent'] == 1) //botschaft senden
	{
		if (strcasecmp($_POST['recipient'], $lang['emperor']) != 0)
		{
			if ($_POST['recipient'] and $_POST['message'])
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
	if (!$newaw)
	{
?>
									<form method="post" action="index.php?chose=messages&amp;mmode=aw">
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" colspan="5" class="table_tc"><?php echo htmlentities($lang["writemsg"]) ?></th>
										</tr>
										<tr>
											<td width="660" colspan="5" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="160" class="no_content">&nbsp;</td>
											<td width="70" class="table_tc"><?php echo htmlentities($lang["recipient"]) ?>:</td>
											<td width="210" class="table_tc">
												<input type="text" name="recipient" value="<?php echo $aw_recipient ?>" />
											</td>
											<td width="70" class="no_content">&nbsp;</td>
											<td width="160" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="160" class="no_content">&nbsp;</td>
											<td width="70" class="table_tc"><?php echo htmlentities($lang["title"]) ?></td>
											<td width="210" class="table_tc">
												<input type="text" name="title" value="Re: <?php echo $aw_title ?>" />
											</td>
											<td width="70" class="no_content">&nbsp;</td>
											<td width="160" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="160" class="no_content">&nbsp;</td>
											<td width="350" class="table_tc" colspan="3">
												<textarea name="message" cols="50" rows="10"><?php echo $new_message ?></textarea>
											</td>
											<td width="160" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="160" class="no_content">&nbsp;</td>
											<td width="350" class="table_tc" colspan="3">
												<input type="submit" name="sub" value="<?php echo htmlentities($lang["send"]) ?>" />
												<input type="hidden" name="newaw" value="1" />
											</td>
											<td width="160" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="160" class="no_content">&nbsp;</td>
											<td width="350" colspan="3" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=received&amp;msgid=<?php echo $aw_msgid ?>&amp;msgread=1"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
											<td width="160" class="no_content">&nbsp;</td>
										</tr>
									</table>
									</form>
<?php
//nachricht senden
	} elseif ($newaw == 1) {
		if ($_POST['recipient'] and $_POST['message']) {
			$helperg = @mysql_query('SELECT uid FROM dw_user WHERE nick="'.$_POST['recipient'].'"', $con);
			if ($helperg) {
				$senduid = mysql_result($helperg, 0);
				$date = time();
//     $senderg = @mysql_query("INSERT INTO dw_message (uid_sender, uid_recipient, date, title, message, type) VALUES ('$uid', '$senduid', '$date', '$sendtitle', '$sendmessage', '1')", $con);
				$senderg = lib_bl_general_sendMessage($_SESSION['user']->getUID(), $senduid, mysql_real_escape_string($_POST['title']), mysql_real_escape_string($_POST['message']), 1);
				if ($senderg) {
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<td width="660" class="table_tc"><?php echo htmlentities(sprintf($lang["msgsend"], $_POST['recipient'])) ?></td>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<a href="index.php?chose=messages"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
										</tr>
									</table>
<?php
				} else {
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<td width="660" class="table_tc"><?php echo htmlentities($lang["failedseding"]) ?></td>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=received"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
										</tr>
									</table>
<?php
				}
			} else {
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<td width="660" class="table_tc"><?php echo htmlentities($lang["notfound"]) ?></td>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=new"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
										</tr>
									</table>
<?php
			}
		} else {
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<td width="660" class="table_tc"><?php echo htmlentities($lang["nomsg_recipient"]) ?></td>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<a href="index.php?chose=messages&amp;mmode=received"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
										</tr>
									</table>
<?php
		}
	}
}
elseif ($_GET['mmode'] == 'newall')
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