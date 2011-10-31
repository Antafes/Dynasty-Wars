<?php
/**
 * insert logmessages
 * @author Neithan
 * @param int $type
 * @param string $actor
 * @param string $concerned
 * @param string $extra
 */
function lib_bl_log_saveLog($type, $actor, $concerned, $extra)
{
	$date = time();
	if ($actor)
		$nick_actor = lib_dal_user_uid2nick($actor);
	if ($concerned)
		$nick_concerned = lib_dal_user_uid2nick($concerned);
	lib_dal_log_saveLog($type, $nick_actor, $nick_concerned, $extra);
}

/**
 * translate failed log texts
 * @author Neithan
 * @param string $text
 * @param string $user
 * @param string $concerned
 * @param int $type
 * @return string
 */
function lib_bl_log_translateFailed($text, $user, $concerned, $type)
{
	$e_text = explode("%", $text);
	$count = count($e_text);
	for ($n = 0; $n < $count; $n++)
	{
		if (strcmp($e_text[$n],"DATE") == 0)
			$e_text[$n] = date();
		if (strcmp($e_text[$n],"TIME") == 0)
			$e_text[$n] = time();
		if (strcmp($e_text[$n],"USER") == 0)
			$e_text[$n] = $user;
		if (strcmp($e_text[$n],"CONCERNED") == 0)
			$e_text[$n] = $concerned;
		if (strcmp($e_text[$n],"TYPE") == 0)
			$e_text[$n] = $type;
		$c_text .= $e_text[$n];
	}
	return $c_text;
}

/**
 * translage log texts
 * @author Neithan
 * @param string $text
 * @param string $user
 * @param string $concerned
 * @param string $extra
 * @return string
 */
function lib_bl_log_translateLog($text, $user, $concerned, $extra, $timestamp)
{
	return str_replace(array(
		'%DATE%',
		'%TIME%',
		'%USER%',
		'%CONCERNED%',
		'%EXTRA%',
	), array(
		date('d.m.Y', $timestamp),
		date('H:i:s', $timestamp),
		$user,
		$concerned,
		$extra,
	), $text);
}

/**
 * failed to insert log entry, send error mail
 * @author Neithan
 * @param int $user
 * @param int $con_user
 * @param int $type
 */
function lib_bl_log_failed($user, $con_user, $type)
{
	$username = lib_dal_user_uid2nick($user);
	$con_username = lib_dal_user_uid2nick($con_user);
	$header = "From: Dynasty Wars <support@dynastywars.de>";
	$subject = $lang["failedlog_title"];
	$message = lib_bl_log_translateFailed($lang["failedlog"], $username, $con_username, $type);
	mail($email, $subject, $message, $header);
}

/**
 * new registration
 * @author Neithan
 * @param int $uid_actor
 * @return <int> returns 1 if the log entry has been successfully saved, otherwise 0
 */
function lib_bl_log_newReg($uid_actor)
{
	$actor = lib_dal_user_uid2nick($uid_actor);
	$date = time();
	if (lib_dal_log_newReg($uid_actor))
		return 1;
	else
		return 0;
}

/**
 * returns the log message
 * @author Neithan
 * @param string $actor
 * @param string $concerned
 * @param string $extra
 * @param int $type
 * @return string
 */
function lib_bl_log_types($type, $actor, $concerned, $extra, $timestamp)
{
	global $lang;
	lib_bl_general_loadLanguageFile('log', 'acp');
	return lib_bl_log_translateLog($lang["t".$type], $actor, $concerned, $extra, $timestamp);
}

/**
 * get all log entries
 * @author Neithan
 * @return array
 */
function lib_bl_log_getLogEntries()
{
	return lib_dal_log_getLogEntries();
}

/**
 * get the html for displaying the log
 * @author Neithan
 * @param int $page
 * @return string
 */
function lib_bl_log_prepareEntries($page)
{
	$entries = lib_bl_log_getLogEntries();

	$html = '';
	for (
		$n = 20 * $page - 20,
		$p = 20 * $page,
		$c = 1; //row selector, for better readability
		$n < $p && $n < count($entries);
		$n++)
	{
		$html .= '
			<tr>
				<td class="logAction">
					'.htmlentities(lib_bl_log_types($entries[$n]['type'], $entries[$n]['actor'], $entries[$n]['concerned'], $entries[$n]['extra'], $entries[$n]['date'])).'
				</td>
				<td class="logDate">
					'.date('d.m.Y H:i:s', $entries[$n]['date']).'
				</td>
		   </tr>
	   ';

		if ($c == 1)
			$c++;
		else
			$c--;
	}

	return $html;
}

/**
 * returns the amount of log entries
 * @author Neithan
 * @return int
 */
function lib_bl_log_getLogCount()
{
	return count(lib_bl_log_getLogEntries());
}