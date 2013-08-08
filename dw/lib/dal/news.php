<?php
namespace dal\news;

/**
 * get all news entries
 * @author Neithan
 * @return array
 */
function getAllEntries()
{
	$sql = '
		SELECT * FROM dw_news
		ORDER BY create_datetime DESC
	';
	return \util\mysql\query($sql, true);
}

/**
 * get the specified news entry
 * @author Neithan
 * @param int $nid
 * @return array
 */
function getEntry($nid)
{
	$sql = '
		SELECT * FROM dw_news
		WHERE nid = '.\util\mysql\sqlval($nid).'
	';
	return \util\mysql\query($sql);
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
function save($title, $content, $uid, $nick)
{
	$now = new \DWDateTime();
	$sql = '
		INSERT INTO dw_news (
		SET uid = '.\util\mysql\sqlval($uid).',
			nick = '.\util\mysql\sqlval($nick).',
			title = '.\util\mysql\sqlval($title).',
			text = '.\util\mysql\sqlval($content).',
			create_datetime = '.\util\mysql\sqlval($now->format()).'
	';
	return \util\mysql\query($sql);
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
function update($nid, $title, $content, $changerUID, $changerNick)
{
	$now = new \DWDateTime();
	$sql = '
		UPDATE dw_news
		SET title = '.\util\mysql\sqlval($title).',
			text = '.\util\mysql\sqlval($content).',
			changed_uid = '.\util\mysql\sqlval($changerUID).',
			changed_nick = '.\util\mysql\sqlval($changerNick).',
			changed = changed + 1,
			changed_datetime = '.\util\mysql\sqlval($now->format()).'
		WHERE nid = '.\util\mysql\sqlval($nid).'
	';
	return \util\mysql\query($sql);
}