<?php
/**
 * returns all open hearings
 * @author Neithan
 * @return array
 */
function lib_dal_tribunal_getAllHearings()
{
	$sql = '
		SELECT * FROM dw_tribunal
		WHERE decision_datetime = 0
			AND !deleted
	';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * returns an array with all causes used in the tribunal
 * @author Neithan
 * @param string $lang
 * @return array
 */
function lib_dal_tribunal_getAllCauses($lang)
{
	$sql = '
		SELECT tcid, cause FROM dw_tribunal_causes
		WHERE language = '.mysql_real_escape_string($lang).'
		ORDER BY sort
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * returns all message titles of the user
 * @author Neithan
 * @param int $uid
 * @return array
 */
function lib_dal_tribunal_getAllMessages($uid)
{
	$now = new DWDateTime();
	$sql = '
		SELECT msgid, title FROM dw_message
		WHERE uid_recipient = '.mysql_real_escape_string($uid).'
			AND ((read_datetime >= "'.mysql_real_escape_string($now->format()).'"
				OR read_datetime = 0)
				OR archive = 1)
	';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * returns an array with all ingame rules
 * @author Neithan
 * @param string $lang
 * @return array
 */
function lib_dal_tribunal_getAllRules($lang)
{
	$sql = '
		SELECT ruid, paragraph, title
		FROM dw_tribunal_rules
		WHERE lang = "'.mysql_real_escape_string($lang).'"
			AND !deleted
		ORDER BY paragraph ASC
	';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * returns an array with the texts to this rule
 * @author Neithan
 * @param string $lang
 * @return array
 */
function lib_dal_tribunal_getAllRuleTexts($ruid, $lang)
{
	$sql = '
		SELECT
			rutid,
			clause,
			subclause,
			description
		FROM dw_tribunal_rules_texts
		WHERE ruid = '.mysql_real_escape_string($ruid).'
			AND lang = "'.mysql_real_escape_string($lang).'"
			AND !deleted
		ORDER BY clause, subclause
	';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * insert a new rule
 * @author Neithan
 * @param string $language
 * @param int $paragraph
 * @param string $title
 * @return int
 */
function lib_dal_tribunal_insertRule($language, $paragraph, $title)
{
	$sql = '
		INSERT INTO dw_tribunal_rules (
			lang,
			paragraph,
			title
		) VALUES (
			"'.mysql_real_escape_string($language).'",
			'.mysql_real_escape_string($paragraph).',
			"'.mysql_real_escape_string($title).'"
		)
	';
	return lib_util_mysqlQuery($sql);
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
function lib_dal_tribunal_insertRuleText($ruid, $language, $clause, $text, $subclause = 0)
{
	$sql = '
		INSERT INTO dw_tribunal_rules_texts (
			ruid,
			lang,
			clause,
			subclause,
			description
		) VALUES (
			'.mysql_real_escape_string($ruid).',
			"'.mysql_real_escape_string($language).'",
			'.mysql_real_escape_string($clause).',
			'.mysql_real_escape_string($subclause).',
			"'.mysql_real_escape_string($text).'"
		)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * mark an entity of the specified table as deleted
 * @author Neithan
 * @param string $table
 * @param string $field
 * @param int $key
 * @return int
 */
function lib_dal_tribunal_deleteRule($table, $field, $key)
{
	$sql = '
		UPDATE '.mysql_real_escape_string($table).'
		SET deleted = 1
		WHERE '.mysql_real_escape_string($field).' = '.mysql_real_escape_string($key).'
	';
	return lib_util_mysqlQuery($sql);
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
function lib_dal_tribunal_insertHearing($suitor, $accused, $cause, $description)
{
	$sql = '
		INSERT INTO dw_tribunal (
			suitor,
			accused,
			cause,
			description,
			create_datetime
		) VALUES (
			'.mysql_real_escape_string($suitor).',
			'.mysql_real_escape_string($accused).',
			"'.mysql_real_escape_string($cause).'",
			"'.mysql_real_escape_string($description).'",
			NOW()
		)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * insert an argument for a hearing
 * @author Neithan
 * @param int $tid
 * @param int $msgid
 * @param string $from
 * @return int
 */
function lib_dal_tribunal_insertArgument($tid, $msgid, $from)
{
	$sql = '
		INSERT INTO dw_tribunal_arguments (
			tid,
			msgid,
			`from`,
			added_datetime
		) VALUES (
			'.mysql_real_escape_string($tid).',
			'.mysql_real_escape_string($msgid).',
			"'.mysql_real_escape_string($from).'",
			NOW()
		)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * get a single cause
 * @author Neithan
 * @param int $tcid
 * @param int $lang_id
 * @return array
 */
function lib_dal_tribunal_getCause($tcid, $lang_id)
{
	$sql = '
		SELECT * FROM dw_tribunal_causes
		WHERE tcid = '.mysql_real_escape_string($tcid).'
			AND language = '.mysql_real_escape_string($lang_id).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * return a single hearing
 * @author Neithan
 * @param int $tid
 * @return array
 */
function lib_dal_tribunal_getHearing($tid)
{
	$sql = '
		SELECT * FROM dw_tribunal
		WHERE tid = '.mysql_real_escape_string($tid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * returns all arguments for a hearing
 * @author Neithan
 * @param int $tid
 * @param bool $approved
 * @return array
 */
function lib_dal_tribunal_getArguments($tid, $approved)
{
	$sql = '
		SELECT * FROM dw_tribunal_arguments
		WHERE tid = '.mysql_real_escape_string($tid).'
	';
	if ($approved == false)
		$sql .= '		AND approved = 1
	';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * set the approved-flag
 * @author Neithan
 * @param int $aid
 * @param int $approved
 * @return int
 */
function lib_dal_tribunal_approveArgument($aid, $approved)
{
	$sql = '
		UPDATE dw_tribunal_arguments
		SET approved = '.mysql_real_escape_string($approved).'
		WHERE aid = '.mysql_real_escape_string($aid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * delete an active hearing
 * @author Neithan
 * @param int $tid
 * @return int
 */
function lib_dal_tribunal_recallHearing($tid, $uid)
{
	$sql = '
		UPDATE dw_tribunal
		SET deleted = 1,
			deleted_by = '.mysql_real_escape_string($uid).'
		WHERE tid = '.mysql_real_escape_string($tid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * return a single hearing
 * @author Neithan
 * @param int $aid
 * @return array
 */
function lib_dal_tribunal_getArgument($aid)
{
	$sql = '
		SELECT * FROM dw_tribunal_arguments
		WHERE aid = '.mysql_real_escape_string($aid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * insert the judges decision
 * @author Neithan
 * @param int $tid
 * @param string $decision
 * @param string $reason
 * @return int
 */
function lib_dal_tribunal_makeDecision($tid, $decision, $reason)
{
	$sql = '
		UPDATE dw_tribunal
		SET decision = "'.mysql_real_escape_string($decision).'",
			reason = "'.mysql_real_escape_string($reason).'",
			decision_datetime = NOW()
		WHERE tid = '.mysql_real_escape_string($tid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * block or unblock comments for a hearing
 * @author Neithan
 * @param int $tid
 * @param int $block
 * @return int
 */
function lib_dal_tribunal_blockComments($tid, $block)
{
	$sql = '
		UPDATE dw_tribunal
		SET block_comments = '.mysql_real_escape_string($block).'
		WHERE tid = '.mysql_real_escape_string($tid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * get all comments
 * @author Neithan
 * @param int $tid
 * @return array
 */
function lib_dal_tribunal_getComments($tid)
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
		WHERE tid = '.mysql_real_escape_string($tid).'
			AND !deleted
		ORDER BY create_datetime
	';
	return lib_util_mysqlQuery($sql, true);
}

/**
 * save a new comment
 * @author Neithan
 * @param int $tid
 * @param int $uid
 * @param string $comment
 * @return int
 */
function lib_dal_tribunal_saveComment($tid, $uid, $comment)
{
	$sql = '
		INSERT INTO dw_tribunal_comments (
			tid,
			writer,
			comment,
			create_datetime
		) VALUES (
			'.mysql_real_escape_string($tid).',
			'.mysql_real_escape_string($uid).',
			"'.mysql_real_escape_string($comment).'",
			NOW()
		)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * delete a comment
 * @author Neithan
 * @param int $tcoid
 * @return int
 */
function lib_dal_tribunal_deleteComment($tcoid)
{
	$sql = '
		UPDATE dw_tribunal_comments
		SET deleted = 1
		WHERE tcoid = '.mysql_real_escape_string($tcoid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * edit a comment
 * @author Neithan
 * @param int $tcoid
 * @param string $comment
 * @param int $uid
 * @return int
 */
function lib_dal_tribunal_editComment($tcoid, $comment, $uid)
{
	$sql = '
		UPDATE dw_tribunal_comments
		SET comment = "'.mysql_real_escape_string($comment).'",
			last_changed_from = '.mysql_real_escape_string($uid).',
			changed_datetime = NOW(),
			changed_count = changed_count + 1
		WHERE tcoid = '.mysql_real_escape_string($tcoid).'
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * get a single comment
 * @param int $tcoid
 * @return array
 */
function lib_dal_tribunal_getComment($tcoid)
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
		WHERE tcoid = '.mysql_real_escape_string($tcoid).'
			AND !deleted
		ORDER BY create_datetime
	';
	return lib_util_mysqlQuery($sql);
}
?>