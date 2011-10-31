<?php
/**
 * get the recipient of the message
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function lib_dal_checks_checkRecipient($msgid)
{
	$sql = 'SELECT uid_recipient FROM dw_message WHERE msgid="'.mysql_real_escape_string($msgid).'"';
	return lib_util_mysqlQuery($sql);
}
/**
 * get the sender of the message
 * @author Neithan
 * @param int $msgid
 * @return int
 */
function lib_dal_checks_checkSender($msgid)
{
	$sql = 'SELECT uid_sender FROM dw_message WHERE msgid="'.mysql_real_escape_string($msgid).'"';
	return lib_util_mysqlQuery($sql);
}
?>