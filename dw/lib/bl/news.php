<?php
namespace bl\news;

/**
 * get all news entries
 * @author Neithan
 * @return array
 */
function getAllEntries()
{
	return \dal\news\getAllEntries();
}

/**
 * get the specified news entry
 * @author Neithan
 * @param int $nid
 * @return array
 */
function getEntry($nid)
{
	return \dal\news\getEntry($nid);
}

/**
 * save a new news entry
 * @author Neithan
 * @param String $title
 * @param String $content
 * @param \bl\user\UserCls $author
 * @return int
 */
function save($title, $content, \bl\user\UserCls $author)
{
	return \dal\news\save($title, $content, $author->getUID(), $author->getNick());
}

/**
 * change an existing news entry
 * @author Neithan
 * @param int $nid
 * @param String $title
 * @param String $content
 * @param \bl\user\UserCls $changer
 * @return int
 */
function update($nid, $title, $content, \bl\user\UserCls $changer)
{
	return \dal\news\update($nid, $title, $content, $changer->getUID(), $changer->getNick());
}