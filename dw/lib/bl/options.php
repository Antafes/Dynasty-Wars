<?php
/**
 * get the old password
 * @author Neithan
 * @param int $uid
 * @return string
 */
function lib_bl_options_getOldPassword($uid)
{
	return lib_dal_options_getOldPassword($uid);
}
/**
 * change the users password
 * @author Neithan
 * @param string $pw
 * @param int $uid
 * @return int
 */
function lib_bl_options_changePassword($pw, $uid)
{
	return lib_dal_options_changePassword($pw, $uid);
}
?>