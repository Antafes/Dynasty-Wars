<?php
namespace bl\user;

/**
 * get the list of users for the acp user list
 * @author Neithan
 * @return array
 */
function getACPUserList()
{
	return dal\user\getACPUserList();
}

/**
 * checks, if the specified user exists
 * @author Neithan;
 * @param String $nick
 * @return bool
 */
function exists($nick)
{
	return dal\user\exists($nick) > 0;
}