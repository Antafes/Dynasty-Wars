<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'switch to mysql datetime';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_build`
				ADD COLUMN `start_datetime` DATETIME NOT NULL AFTER `upgrade`,
				ADD COLUMN `end_datetime` DATETIME NOT NULL AFTER `starttime`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_build_unit`
				ADD COLUMN `start_datetime` DATETIME NOT NULL AFTER `count`,
				ADD COLUMN `end_datetime` DATETIME NOT NULL AFTER `starttime`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_clan_applications`
				ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `applicationtext`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_log`
				ADD COLUMN `log_datetime` DATETIME NOT NULL AFTER `date`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_lostpw`
				ADD COLUMN `sent_datetime` DATETIME NOT NULL AFTER `mailid`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_market`
				ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `timestamp`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_message`
				ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `date`,
				ADD COLUMN `read_datetime` DATETIME NOT NULL AFTER `date_read`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_news`
				ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `date`,
				ADD COLUMN `changed_datetime` DATETIME NOT NULL AFTER `last_changed`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_res`
				ADD COLUMN `last_datetime` DATETIME NOT NULL AFTER `last_time`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_research`
				ALTER `starttime` DROP DEFAULT
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_research`
				CHANGE COLUMN `starttime` `end_datetime` DATETIME NOT NULL AFTER `lvl`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal`
				ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `date`,
				ADD COLUMN `decision_datetime` DATETIME NOT NULL AFTER `decision_date`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_arguments`
				ADD COLUMN `added_datetime` DATETIME NOT NULL AFTER `date_added`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_comments`
				ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `date_added`,
				ADD COLUMN `changed_datetime` DATETIME NOT NULL AFTER `date_last_changed`
		');

		$results[] = \util\mysql\query_raw('
				ALTER TABLE `dw_troops_move`
				ADD COLUMN `end_datetime` DATETIME NOT NULL AFTER `endtime`
		');

		$sql = '
			SELECT
				bid,
				starttime,
				endtime
			FROM dw_build
		';
		$data = util\mysql\query($sql, true);

		foreach ($data as $row)
		{
			$sql = '
				UPDATE dw_build
				SET start_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['starttime'])).'",
					end_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['endtime'])).'"
				WHERE bid = '.mysql_real_escape_string($row['bid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				tid,
				starttime,
				endtime
			FROM dw_build_unit
		';
		$data = util\mysql\query($sql, true);

		foreach ($data as $row)
		{
			$sql = '
				UPDATE dw_build_unit
				SET start_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['starttime'])).'",
					end_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['endtime'])).'"
				WHERE tid = '.mysql_real_escape_string($row['tid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				appid,
				apptime
			FROM dw_clan_applications
		';
		$data = util\mysql\query($sql, true);

		foreach ($data as $row)
		{
			$sql = '
				UPDATE dw_clan_applications
				SET create_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['apptime'])).'"
				WHERE appid = '.mysql_real_escape_string($row['appid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				actid,
				date
			FROM dw_log
		';
		$logEntries = util\mysql\query($sql, true);

		foreach ($logEntries as $logEntry)
		{
			$sql = '
				UPDATE dw_log
				SET log_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $logEntry['date'])).'"
				WHERE actid = '.mysql_real_escape_string($logEntry['actid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				lpid,
				sent_time
			FROM dw_lostpw
		';
		$logEntries = util\mysql\query($sql, true);

		foreach ($logEntries as $logEntry)
		{
			$sql = '
				UPDATE dw_lost_pw
				SET sent_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $logEntry['sent_time'])).'"
				WHERE lpid = '.mysql_real_escape_string($logEntry['lpid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				mid,
				timestamp
			FROM dw_market
		';
		$market_data = util\mysql\query($sql, true);

		foreach ($market_data as $row)
		{
			$sql = '
				UPDATE dw_market
				SET create_datetime = "'.mysql_real_escape_string($row['timestamp'] != 1 ? date('Y-m-d H:i:s', $row['timestamp']) : 0).'"
				WHERE mid = '.mysql_real_escape_string($row['mid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				msgid,
				date,
				date_read
			FROM dw_message
		';
		$messages = util\mysql\query($sql, true);

		foreach ($messages as $message)
		{
			$sql = '
				UPDATE dw_message
				SET create_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $message['date'])).'",
					read_datetime = "'.mysql_real_escape_string($message['date_read'] ? date('Y-m-d H:i:s', $message['date_read']) : 0).'"
				WHERE msgid = '.mysql_real_escape_string($message['msgid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				nid,
				date,
				last_changed
			FROM dw_news
		';
		$data = util\mysql\query($sql, true);

		foreach ($data as $row)
		{
			$sql = '
				UPDATE dw_news
				SET create_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['date'])).'",
					changed_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['last_changed'])).'"
				WHERE nid = '.mysql_real_escape_string($row['nid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				uid,
				map_x,
				map_y,
				last_time
			FROM dw_res
		';
		$logEntries = util\mysql\query($sql, true);

		foreach ($logEntries as $logEntry)
		{
			$sql = '
				UPDATE dw_res
				SET last_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $logEntry['last_time'])).'"
				WHERE uid = '.mysql_real_escape_string($logEntry['uid']).'
					AND map_x = '.mysql_real_escape_string($logEntry['map_x']).'
					AND map_y = '.mysql_real_escape_string($logEntry['map_y']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				tid,
				date,
				decision_date
			FROM dw_tribunal
		';
		$data = util\mysql\query($sql, true);

		foreach ($data as $row)
		{
			$sql = '
				UPDATE dw_tribunal
				SET create_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['date'])).'",
					decision_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['decision_date'])).'"
				WHERE tid = '.mysql_real_escape_string($row['tid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				aid,
				date_added
			FROM dw_tribunal_arguments
		';
		$logEntries = util\mysql\query($sql, true);

		foreach ($logEntries as $logEntry)
		{
			$sql = '
				UPDATE dw_tribunal_arguments
				SET added_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $logEntry['date_added'])).'"
				WHERE aid = '.mysql_real_escape_string($logEntry['aid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				tcoid,
				date_added,
				date_last_changed
			FROM dw_tribunal_comments
		';
		$data = util\mysql\query($sql, true);

		foreach ($data as $row)
		{
			$sql = '
				UPDATE dw_tribunal_comments
				SET create_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['date_added'])).'",
					changed_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $row['date_last_changed'])).'"
				WHERE tcoid = '.mysql_real_escape_string($row['tcoid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				tmid,
				endtime
			FROM dw_troops_move
		';
		$logEntries = util\mysql\query($sql, true);

		foreach ($logEntries as $logEntry)
		{
			$sql = '
				UPDATE dw_troops_move
				SET end_datetime = "'.mysql_real_escape_string(date('Y-m-d H:i:s', $logEntry['endtime'])).'"
				WHERE tmid = '.mysql_real_escape_string($logEntry['tmid']).'
			';
			util\mysql\query($sql);
		}

		$sql = '
			SELECT
				uid,
				regdate,
				last_login
			FROM dw_user
		';
		$data = util\mysql\query($sql, true);

		foreach ($data as $row)
		{
			$sql = '
				UPDATE dw_user
				SET registration_datetime = "'.mysql_real_escape_string($row['regdate'] ? date('Y-m-d H:i:s', $row['regdate']) : 0).'",
					last_login_datetime = "'.mysql_real_escape_string($row['last_login'] ? date('Y-m-d H:i:s', $row['last_login']) : 0).'"
				WHERE uid = '.mysql_real_escape_string($row['uid']).'
			';
			util\mysql\query($sql);
		}

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_build`
				DROP COLUMN `starttime`,
				DROP COLUMN `endtime`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_build_unit`
				DROP COLUMN `starttime`,
				DROP COLUMN `endtime`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_clan_applications`
				DROP COLUMN `apptime`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_log`
				DROP COLUMN `date`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_lostpw`
				DROP COLUMN `sent_time`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_market`
				DROP COLUMN `timestamp`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_message`
				DROP COLUMN `date`,
				DROP COLUMN `date_read`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_news`
				DROP COLUMN `date`,
				DROP COLUMN `last_changed`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_res`
				DROP COLUMN `last_time`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal`
				DROP COLUMN `date`,
				DROP COLUMN `decision_date`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_arguments`
				DROP COLUMN `date_added`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_comments`
				DROP COLUMN `date_added`,
				DROP COLUMN `date_last_changed`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_troops_move`
				DROP COLUMN `endtime`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_user`
				DROP COLUMN `regdate`,
				DROP COLUMN `last_login`
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		return ;

	}

);