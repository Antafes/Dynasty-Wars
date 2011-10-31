<?php
/**
 * insert logmessages
 * @author Neithan
 * @param int $type
 * @param string $actor
 * @param string $concerned
 * @param string $extra
 * @return int
 */
function lib_dal_log_saveLog($type, $actor, $concerned, $extra)
{
	$sql = '
		INSERT INTO dw_log (
			date,
			actor,
			concerned,
			extra,
			type
		) VALUES (
			'.time().',
			"'.mysql_real_escape_string($actor).'",
			"'.mysql_real_escape_string($concerned).'",
			"'.mysql_real_escape_string($extra).'",
			'.mysql_real_escape_string($type).')
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * new registration
 * @author Neithan
 * @param int $uid_actor
 * @return int
 */
function lib_dal_log_newReg($uid_actor)
{
	$sql = '
		INSERT INTO dw_log (
			`date`,
			`actor`,
			`concerned`,
			`type`
		) VALUES (
			"'.time().'",
			"'.mysql_real_escape_string($uid_actor).'",
			"",
			1
		)
	';
	return lib_util_mysqlQuery($sql);
}

/**
 * get all log entries
 * @author Neithan
 * @return array
 */
function lib_dal_log_getLogEntries()
{
	$sql = '
		SELECT * FROM dw_log
		ORDER BY date DESC
	';
	return lib_util_mysqlQuery($sql, true);
}