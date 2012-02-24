<?php
/**
 * get the list of users for the acp user list
 * @author Neithan
 * @return array
 */
function lib_bl_user_getACPUserList()
{
	return dal\user\getACPUserList();
}

/**
 * checks, if the specified user exists
 * @author Neithan;
 * @param String $nick
 * @return bool
 */
function lib_bl_user_exists($nick)
{
	return dal\user\exists($nick) > 0;
}