<?php
/**
 * returns all open hearings
 * @author Neithan
 * @return array
 */
function lib_bl_tribunal_getAllHearings()
{
	return lib_dal_tribunal_getAllHearings();
}

/**
 * returns an array with all causes used in the tribunal
 * @author Neithan
 * @param string $lang
 * @return array
 */
function lib_bl_tribunal_getAllCauses($lang)
{
	return lib_dal_tribunal_getAllCauses(lib_bl_tribunal_lang2id($lang));
}

/**
 * change the language to the database int
 * @author Neithan
 * @param unknown_type $lang
 * @return unknown_type
 */
function lib_bl_tribunal_lang2id($lang)
{
	switch ($lang)
	{
		case 'de':
			return 1;
			break;
		case 'en':
		default:
			return 2;
			break;
	}
}

/**
 * returns all message titles of the user
 * @author Neithan
 * @param int $uid
 * @return array
 */
function lib_bl_tribunal_getAllMessages($uid)
{
	return lib_dal_tribunal_getAllMessages($uid);
}

/**
 * returns an array with all rules
 * @author Neithan
 * @param string $language
 * @return array
 */
function lib_bl_tribunal_getAllRules($language = '')
{
	if ($language == '')
	{
		global $lang;
		$language = $lang['lang'];
	}
	$rules = lib_dal_tribunal_getAllRules($language);
	$rules_array = array();
	foreach ($rules as $rule)
	{
		$ruletexts = lib_dal_tribunal_getAllRuleTexts($rule['ruid'], $language);
		$texts = array();
		foreach ($ruletexts as $text)
		{
			if ($text['subclause'] > 0)
			{
				$texts[$text['clause']]['subclauses'][$text['subclause']]['rutid'] = (int)$text['rutid'];
				$texts[$text['clause']]['subclauses'][$text['subclause']]['text'] = $text['description'];
			}
			else
			{
				$texts[$text['clause']]['rutid'] = (int)$text['rutid'];
				$texts[$text['clause']]['text'] = $text['description'];
			}
		}
		$rules_array[$rule['paragraph']] = array(
			'ruid' => (int)$rule['ruid'],
			'title' => $rule['title'],
			'texts' => $texts,
		);
	}
	return $rules_array;
}

/**
 * insert a new rule
 * @author Neithan
 * @param array $valuelist array([paragraph], [title], [clauses] => array([clause1], [clause2], ...),
 * 				[subclauses] => array([clausex] => [subclause1], [clausex] => [subclause2], ...), [language])
 * @return unknown_type
 */
function lib_bl_tribunal_insertRule($valuelist)
{
	$ruid = lib_dal_tribunal_insertRule($valuelist['language'], $valuelist['paragraph'], $valuelist['title']);
	$clauses_count = count($valuelist['clauses']);
	for ($i = 0; $i < $clauses_count; $i++)
	{
		$clause = $valuelist['clauses'][$i];
		lib_dal_tribunal_insertRuleText($ruid, $valuelist['language'], $i + 1, $clause['text']);
		if (is_array($clause['subclauses']))
		{
			$subclauses_count = count($clause['subclauses']);
			for ($n = 0; $n < $subclauses_count; $n++)
				lib_dal_tribunal_insertRuleText($ruid, $valuelist['language'], $i + 1, $clause['subclauses'][$n]['text'], $n + 1);
		}
	}
}

/**
 * mark a whole rule as deleted
 * @author Neithan
 * @param int $ruid
 * @return void
 */
function lib_bl_tribunal_deleteRule($ruid)
{
	lib_dal_tribunal_deleteRule('dw_tribunal_rules', 'ruid', $ruid);
	lib_dal_tribunal_deleteRule('dw_tribunal_rules_texts', 'ruid', $ruid);
}

/**
 * mark a clause as deleted
 * @author Neithan
 * @param int $rutid
 * @return void
 */
function lib_bl_tribunal_deleteClause($rutid)
{
	lib_dal_tribunal_deleteRule('dw_tribunal_rules_texts', 'rutid', $rutid);
}

/**
 * insert a new hearing with arguments
 * @author Neithan
 * @param int $suitor
 * @param int $accused
 * @param string $cause
 * @param string $description
 * @param array $arguments array([msgid1], [msgid2],...)
 * @return void
 */
function lib_bl_tribunal_insertHearing($suitor, $accused, $cause, $description, $arguments)
{
	$error = array();
	$accused_uid = lib_dal_user_nick2uid($accused);
	if ($accused == '')
		$error['accused'] = true;
	if ($cause == 0)
		$error['cause'] = true;
	if (!is_array($arguments) and count($arguments) < 1)
		$error['arguments'] = true;
	if ($suitor == $accused_uid)
		$error['suitor'] = true;
	if (count($error) == 0)
	{
		$tid = lib_dal_tribunal_insertHearing($suitor, $accused_uid, $cause, $description);
		lib_bl_tribunal_addArgument($tid, $arguments, 'suitor');
		$accused_lang = lib_bl_general_getLanguage($accused_uid);
		include('language/'.$accused_lang.'/ingame/tribunal.php');
		lib_bl_general_sendMessage($suitor, $accused_uid, $lang['info_title'], sprintf($lang['info_msg'], $tid), 3);
		return false;
	}
	else
		return $error;
}

/**
 * add an argument
 * @author Neithan
 * @param int $tid
 * @param array $arguments array([msgid1], [msgid2],...)
 * @param string $from
 * @return array
 */
function lib_bl_tribunal_addArgument($tid, $arguments, $from)
{
	$aid_array = array();
	foreach ($arguments as $argument)
		$aid_array[] = lib_dal_tribunal_insertArgument($tid, $argument, $from);
	return $aid_array;
}

/**
 * get a single cause
 * @author Neithan
 * @param int $tcid
 * @return array
 */
function lib_bl_tribunal_getCause($tcid)
{
	global $lang;
	return lib_dal_tribunal_getCause($tcid, lib_bl_tribunal_lang2id($lang['lang']));
}

/**
 * get a single hearing with all arguments
 * @author Neithan
 * @param int $tid
 * @return array
 */
function lib_bl_tribunal_getHearing($tid)
{
	global $own_uid;
	$hearing = lib_dal_tribunal_getHearing($tid);
	$hearing['arguments'] = lib_dal_tribunal_getArguments($tid, (($admin > 1 and !$own_uid) ? true : false));
	return $hearing;
}

/**
 * approve an argument
 * @author Neithan
 * @param int $aid
 * @param string $approved
 * @return int
 */
function lib_bl_tribunal_approveArgument($aid, $approved)
{
	return lib_dal_tribunal_approveArgument($aid, ($approved == 'accept' ? 1 : -1));
}

/**
 * delete an active hearing
 * @author Neithan
 * @param int $tid
 * @return int
 */
function lib_bl_tribunal_recallHearing($tid, $uid)
{
	return lib_dal_tribunal_recallHearing($tid, $uid);
}

/**
 * return a single argument by aid
 * @author Neithan
 * @param int $aid
 * @return array
 */
function lib_bl_tribunal_getArgument($aid)
{
	return lib_dal_tribunal_getArgument($aid);
}

/**
 * insert the judges decision
 * @author Neithan
 * @param int $tid
 * @param string $decision
 * @param string $reason
 * @return int
 */
function lib_bl_tribunal_makeDecision($tid, $decision, $reason)
{
	return lib_dal_tribunal_makeDecision($tid, $decision, $reason);
}

/**
 * block or unblock comments for a hearing
 * @author Neithan
 * @param int $tid
 * @param int $block
 * @return int
 */
function lib_bl_tribunal_blockComments($tid, $block)
{
	return lib_dal_tribunal_blockComments($tid, $block);
}

/**
 * get all comments
 * @author Neithan
 * @param int $tid
 * @return array
 */
function lib_bl_tribunal_getComments($tid)
{
	return lib_dal_tribunal_getComments($tid);
}

/**
 * save a new comment
 * @author Neitha
 * @param int $tid
 * @param int $uid
 * @param string $comment
 * @return int
 */
function lib_bl_tribunal_saveComment($tid, $uid, $comment)
{
	return lib_dal_tribunal_saveComment($tid, $uid, $comment);
}

/**
 * delete a comment
 * @author Neithan
 * @param int $tcoid
 * @return int
 */
function lib_bl_tribunal_deleteComment($tcoid)
{
	return lib_dal_tribunal_deleteComment($tcoid);
}

/**
 * edit a comment
 * @author Neithan
 * @param int $tcoid
 * @param string $comment
 * @param int $uid
 * @return int
 */
function lib_bl_tribunal_editComment($tcoid, $comment, $uid)
{
	return lib_dal_tribunal_editComment($tcoid, $comment, $uid);
}

/**
 * get a single comment
 * @param int $tcoid
 * @return array
 */
function lib_bl_tribunal_getComment($tcoid)
{
	return lib_dal_tribunal_getComment($tcoid);
}
?>