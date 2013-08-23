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
	$now = new \DWDateTime();
	$sql = '
		INSERT INTO dw_log (
			log_datetime,
			actor,
			concerned,
			extra,
			type
		) VALUES (
			'.\util\mysql\sqlval($now->format()).',
			'.\util\mysql\sqlval($actor).',
			'.\util\mysql\sqlval($concerned).',
			'.\util\mysql\sqlval($extra).',
			'.\util\mysql\sqlval($type).')
	';
	return \util\mysql\query($sql);
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
	return \util\mysql\query($sql, true);
}