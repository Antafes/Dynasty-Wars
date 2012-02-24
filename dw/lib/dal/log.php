<?php
namespace dal\log;

/**
 * insert log messages
 * @author Neithan
 * @param int $type
 * @param string $actor
 * @param string $concerned
 * @param string $extra
 * @return int
 */
function saveLog($type, $actor, $concerned, $extra)
{
	$sql = '
		INSERT INTO dw_log (
			log_datetime,
			actor,
			concerned,
			extra,
			type
		) VALUES (
			NOW(),
			"'.mysql_real_escape_string($actor).'",
			"'.mysql_real_escape_string($concerned).'",
			"'.mysql_real_escape_string($extra).'",
			'.mysql_real_escape_string($type).')
	';
	return util\mysql\query($sql);
}

/**
 * new registration
 * @author Neithan
 * @param int $uid_actor
 * @return int
 */
function newReg($uid_actor)
{
	$sql = '
		INSERT INTO dw_log (
			`log_datetime`,
			`actor`,
			`concerned`,
			`type`
		) VALUES (
			NOW(),
			"'.mysql_real_escape_string($uid_actor).'",
			"",
			1
		)
	';
	return util\mysql\query($sql);
}

/**
 * get all log entries
 * @author Neithan
 * @return array
 */
function getLogEntries()
{
	$sql = '
		SELECT * FROM dw_log
		ORDER BY log_datetime DESC
	';
	return util\mysql\query($sql, true);
}