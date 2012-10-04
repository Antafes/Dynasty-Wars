<?php
namespace dal\tribunal;

/**
 * returns all open hearings
 * @author Neithan
 * @return array
 */
function getAllHearings()
{
	$sql = '
		SELECT * FROM dw_tribunal
		WHERE decision_datetime = 0
			AND !deleted
	';
	return \util\mysql\query($sql, true);
}

/**
 * returns an array with all causes used in the tribunal
 * @author Neithan
 * @param string $lang
 * @return array
 */
function getAllCauses($lang)
{
	$sql = '
		SELECT tcid, cause FROM dw_tribunal_causes
		WHERE language = '.\util\mysql\sqlval($lang).'
		ORDER BY sort
	';
	return \util\mysql\query($sql);
}

/**
 * returns all message titles of the user
 * @author Neithan
 * @param int $uid
 * @return array
 */
function getAllMessages($uid)
{
	$sql = '
		SELECT msgid, title FROM dw_message
		WHERE uid_recipient = '.\util\mysql\sqlval($uid).'
			AND ((read_datetime >= NOW()
				OR read_datetime = 0)
				OR archive = 1)
	';
	return \util\mysql\query($sql, true);
}

/**
 * returns an array with all ingame rules
 * @author Neithan
 * @param string $lang
 * @return array
 */
function getAllRules($lang)
{
	$sql = '
		SELECT ruid, paragraph, title
		FROM dw_tribunal_rules
		WHERE lang = '.\util\mysql\sqlval($lang).'
			AND !deleted
		ORDER BY paragraph ASC
	';
	return \util\mysql\query($sql, true);
}

/**
 * returns an array with the texts to this rule
 * @author Neithan
 * @param string $lang
 * @return array
 */
function getAllRuleTexts($ruid, $lang)
{
	$sql = '
		SELECT
			rutid,
			clause,
			subclause,
			description
		FROM dw_tribunal_rules_texts
		WHERE ruid = '.\util\mysql\sqlval($ruid).'
			AND lang = '.\util\mysql\sqlval($lang).'
			AND !deleted
		ORDER BY clause, subclause
	';
	return \util\mysql\query($sql, true);
}

/**
 * insert a new rule
 * @author Neithan
 * @param string $language
 * @param int $paragraph
 * @param string $title
 * @return int
 */
function insertRule($language, $paragraph, $title)
{
	$sql = '
		INSERT INTO dw_tribunal_rules (
			lang,
			paragraph,
			title
		) VALUES (
			'.\util\mysql\sqlval($language).',
			'.\util\mysql\sqlval($paragraph).',
			'.\util\mysql\sqlval($title).'
		)
	';
	return \util\mysql\query($sql);
}

/**
 * insert the text to a rule
 * @author Neithan
 * @param int $ruid
 * @param string $language
 * @param int $clause
 * @param string $text
 * @return int
 */
function insertRuleText($ruid, $language, $clause, $text, $subclause = 0)
{
	$sql = '
		INSERT INTO dw_tribunal_rules_texts (
			ruid,
			lang,
			clause,
			subclause,
			description
		) VALUES (
			'.\util\mysql\sqlval($ruid).',
			'.\util\mysql\sqlval($language).',
			'.\util\mysql\sqlval($clause).',
			'.\util\mysql\sqlval($subclause).',
			'.\util\mysql\sqlval($text).'
		)
	';
	return \util\mysql\query($sql);
}

/**
 * mark an entity of the specified table as deleted
 * @author Neithan
 * @param string $table
 * @param string $field
 * @param int $key
 * @return int
 */
function deleteRule($table, $field, $key)
{
	$sql = '
		UPDATE '.\util\mysql\sqlval($table, false).'
		SET deleted = 1
		WHERE '.\util\mysql\sqlval($field, false).' = '.\util\mysql\sqlval($key).'
	';
	return \util\mysql\query($sql);
}

/**
 * insert a new hearing
 * @author Neithan
 * @param int $suitor
 * @param int $accused
 * @param string $cause
 * @param string $description
 * @return int
 */
function insertHearing($suitor, $accused, $cause, $description)
{
	$sql = '
		INSERT INTO dw_tribunal (
			suitor,
			accused,
			cause,
			description,
			create_datetime
		) VALUES (
			'.\util\mysql\sqlval($suitor).',
			'.\util\mysql\sqlval($accused).',
			'.\util\mysql\sqlval($cause).',
			'.\util\mysql\sqlval($description).',
			NOW()
		)
	';
	return \util\mysql\query($sql);
}

/**
 * insert an argument for a hearing
 * @author Neithan
 * @param int $tid
 * @param int $msgid
 * @param string $from
 * @return int
 */
function insertArgument($tid, $msgid, $from)
{
	$sql = '
		INSERT INTO dw_tribunal_arguments (
			tid,
			msgid,
			`from`,
			added_datetime
		) VALUES (
			'.\util\mysql\sqlval($tid).',
			'.\util\mysql\sqlval($msgid).',
			'.\util\mysql\sqlval($from).',
			NOW()
		)
	';
	return \util\mysql\query($sql);
}

/**
 * get a single cause
 * @author Neithan
 * @param int $tcid
 * @param int $lang_id
 * @return array
 */
function getCause($tcid, $lang_id)
{
	$sql = '
		SELECT * FROM dw_tribunal_causes
		WHERE tcid = '.\util\mysql\sqlval($tcid).'
			AND language = '.\util\mysql\sqlval($lang_id).'
	';
	return \util\mysql\query($sql);
}

/**
 * return a single hearing
 * @author Neithan
 * @param int $tid
 * @return array
 */
function getHearing($tid)
{
	$sql = '
		SELECT * FROM dw_tribunal
		WHERE tid = '.\util\mysql\sqlval($tid).'
	';
	return \util\mysql\query($sql);
}

/**
 * returns all arguments for a hearing
 * @author Neithan
 * @param int $tid
 * @param bool $approved
 * @return array
 */
function getArguments($tid, $approved)
{
	$sql = '
		SELECT * FROM dw_tribunal_arguments
		WHERE tid = '.\util\mysql\sqlval($tid).'
	';
	if ($approved == false)
		$sql .= '		AND approved = 1
	';

	return \util\mysql\query($sql, true);
}

/**
 * set the approved-flag
 * @author Neithan
 * @param int $aid
 * @param int $approved
 * @return int
 */
function approveArgument($aid, $approved)
{
	$sql = '
		UPDATE dw_tribunal_arguments
		SET approved = '.\util\mysql\sqlval($approved).'
		WHERE aid = '.\util\mysql\sqlval($aid).'
	';
	return \util\mysql\query($sql);
}

/**
 * delete an active hearing
 * @author Neithan
 * @param int $tid
 * @return int
 */
function recallHearing($tid, $uid)
{
	$sql = '
		UPDATE dw_tribunal
		SET deleted = 1,
			deleted_by = '.\util\mysql\sqlval($uid).'
		WHERE tid = '.\util\mysql\sqlval($tid).'
	';
	return \util\mysql\query($sql);
}

/**
 * return a single hearing
 * @author Neithan
 * @param int $aid
 * @return array
 */
function getArgument($aid)
{
	$sql = '
		SELECT * FROM dw_tribunal_arguments
		WHERE aid = '.\util\mysql\sqlval($aid).'
	';
	return \util\mysql\query($sql);
}

/**
 * insert the judges decision
 * @author Neithan
 * @param int $tid
 * @param string $decision
 * @param string $reason
 * @return int
 */
function makeDecision($tid, $decision, $reason)
{
	$sql = '
		UPDATE dw_tribunal
		SET decision = '.\util\mysql\sqlval($decision).',
			reason = '.\util\mysql\sqlval($reason).',
			decision_datetime = NOW()
		WHERE tid = '.\util\mysql\sqlval($tid).'
	';
	return \util\mysql\query($sql);
}

/**
 * block or unblock comments for a hearing
 * @author Neithan
 * @param int $tid
 * @param int $block
 * @return int
 */
function blockComments($tid, $block)
{
	$sql = '
		UPDATE dw_tribunal
		SET block_comments = '.\util\mysql\sqlval($block).'
		WHERE tid = '.\util\mysql\sqlval($tid).'
	';
	return \util\mysql\query($sql);
}

/**
 * get all comments
 * @author Neithan
 * @param int $tid
 * @return array
 */
function getComments($tid)
{
	$sql = '
		SELECT
			tcoid,
			writer,
			comment,
			create_datetime,
			last_changed_from,
			changed_datetime,
			changed_count
		FROM dw_tribunal_comments
		WHERE tid = '.\util\mysql\sqlval($tid).'
			AND !deleted
		ORDER BY create_datetime
	';
	return \util\mysql\query($sql, true);
}

/**
 * save a new comment
 * @author Neithan
 * @param int $tid
 * @param int $uid
 * @param string $comment
 * @return int
 */
function saveComment($tid, $uid, $comment)
{
	$sql = '
		INSERT INTO dw_tribunal_comments (
			tid,
			writer,
			comment,
			create_datetime
		) VALUES (
			'.\util\mysql\sqlval($tid).',
			'.\util\mysql\sqlval($uid).',
			'.\util\mysql\sqlval($comment).',
			NOW()
		)
	';
	return \util\mysql\query($sql);
}

/**
 * delete a comment
 * @author Neithan
 * @param int $tcoid
 * @return int
 */
function deleteComment($tcoid)
{
	$sql = '
		UPDATE dw_tribunal_comments
		SET deleted = 1
		WHERE tcoid = '.\util\mysql\sqlval($tcoid).'
	';
	return \util\mysql\query($sql);
}

/**
 * edit a comment
 * @author Neithan
 * @param int $tcoid
 * @param string $comment
 * @param int $uid
 * @return int
 */
function editComment($tcoid, $comment, $uid)
{
	$sql = '
		UPDATE dw_tribunal_comments
		SET comment = '.\util\mysql\sqlval($comment).',
			last_changed_from = '.\util\mysql\sqlval($uid).',
			changed_datetime = NOW(),
			changed_count = changed_count + 1
		WHERE tcoid = '.\util\mysql\sqlval($tcoid).'
	';
	return \util\mysql\query($sql);
}

/**
 * get a single comment
 * @param int $tcoid
 * @return array
 */
function getComment($tcoid)
{
	$sql = '
		SELECT
			tcoid,
			writer,
			comment,
			create_datetime,
			last_changed_from,
			changed_datetime,
			changed_count
		FROM dw_tribunal_comments
		WHERE tcoid = '.\util\mysql\sqlval($tcoid).'
			AND !deleted
		ORDER BY create_datetime
	';
	return \util\mysql\query($sql);
}