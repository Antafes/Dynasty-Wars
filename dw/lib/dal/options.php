<?php
/**
 * get the old password
 * @author Neithan
 * @param int $uid
 * @return string
 */
function lib_dal_options_getOldPassword($uid)
{
	$sql = 'SELECT password FROM dw_user WHERE uid = '.$uid.'';
	return lib_util_mysqlQuery($sql);
}
/**
 * change the users password
 * @author Neithan
 * @param string $pw
 * @param int $uid
 * @return int
 */
function lib_dal_options_changePassword($pw, $uid)
{
	$sql = 'UPDATE dw_user SET password="'.mysql_real_escape_string($pw).'" WHERE uid = '.$uid.'';
	return lib_util_mysqlQuery($sql);
}
?>