<?php
/**
 * get all news entries
 * @author Neithan
 * @return array
 */
function lib_dal_news_getAllEntries()
{
	$sql = '
		SELECT * FROM dw_news
		ORDER BY create_datetime DESC
	';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * get the specified news entry
 * @author Neithan
 * @param int $nid
 * @return array
 */
function lib_dal_news_getEntry($nid)
{
	$sql = '
		SELECT * FROM dw_news
		WHERE nid = '.mysql_real_escape_string($nid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * save a new news entry
 * @author Neithan
 * @param String $title
 * @param String $content
 * @param int $uid
 * @param String $nick
 * @return int
 */
function lib_dal_news_save($title, $content, $uid, $nick)
{
	$sql = '
		INSERT INTO dw_news (
			uid,
			nick,
			title,
			text,
			create_datetime
		) VALUES (
			'.mysql_real_escape_string($uid).',
			"'.mysql_real_escape_string($nick).'",
			"'.mysql_real_escape_string($title).'",
			"'.mysql_real_escape_string($content).'",
			NOW()
		)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * change an existing news entry
 * @author Neithan
 * @param int $nid
 * @param String $title
 * @param String $content
 * @param int $changerUID
 * @param String $changerNick
 * @return int
 */
function lib_dal_news_update($nid, $title, $content, $changerUID, $changerNick)
{
	$sql = '
		UPDATE dw_news
		SET title = "'.mysql_real_escape_string($title).'",
			text = "'.mysql_real_escape_string($content).'",
			changed_uid = '.mysql_real_escape_string($changerUID).',
			changed_nick = "'.mysql_real_escape_string($changerNick).'",
			changed = changed + 1,
			changed_datetime = NOW()
		WHERE nid = '.mysql_real_escape_string($nid).'
	';
	return lib_util_mysqlQuery($sql);
}