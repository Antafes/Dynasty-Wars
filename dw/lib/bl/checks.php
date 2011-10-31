<?php
/**
 * check if the message is for this user
 * @author Neithan
 * @param int $msgid
 * @param int $uid
 * @param int $mode 1 for check recipient, 2 for check sender
 * @return <int> returns 1 if the message is for this user, otherwise 0
 */
function lib_bl_checks_checkUser($msgid, $uid, $mode)
{
	if ($mode == 1)
		$checkuid = lib_dal_checks_checkRecipient($msgid);
	elseif ($mode == 2)
		$checkuid = lib_dal_checks_checkSender($msgid);
	if ($uid == $checkuid)
		return 1;
	else
		return 0;
}
?>