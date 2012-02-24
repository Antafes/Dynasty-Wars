<?php
/**
 * get all news entries
 * @author Neithan
 * @return array
 */
function lib_bl_news_getAllEntries()
{
	return dal\news\getAllEntries();
}

/**
 * get the specified news entry
 * @author Neithan
 * @param int $nid
 * @return array
 */
function lib_bl_news_getEntry($nid)
{
	return dal\news\getEntry($nid);
}

/**
 * save a new news entry
 * @author Neithan
 * @param String $title
 * @param String $content
 * @param UserCls $author
 * @return int
 */
function lib_bl_news_save($title, $content, UserCls $author)
{
	return dal\news\save($title, $content, $author->getUID(), $author->getNick());
}

/**
 * change an existing news entry
 * @author Neithan
 * @param int $nid
 * @param String $title
 * @param String $content
 * @param UserCls $changer
 * @return int
 */
function lib_bl_news_update($nid, $title, $content, UserCls $changer)
{
	return dal\news\update($nid, $title, $content, $changer->getUID(), $changer->getNick());
}