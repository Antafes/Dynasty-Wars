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
		require_once(dirname(__FILE__).'/../../timestamp2datetime/build.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_build`
				DROP COLUMN `starttime`,
				DROP COLUMN `endtime`
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_build_unit`
				ADD COLUMN `start_datetime` DATETIME NOT NULL AFTER `count`,
				ADD COLUMN `end_datetime` DATETIME NOT NULL AFTER `starttime`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/build_unit.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_build_unit`
				DROP COLUMN `starttime`,
				DROP COLUMN `endtime`
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_clan_applications`
				add COLUMN `create_datetime` DATETIME NOT NULL AFTER `applicationtext`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/clan_applications.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_clan_applications`
				DROP COLUMN `apptime`
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_log`
				ADD COLUMN `log_datetime` DATETIME NOT NULL AFTER `date`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/log.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_log`
				DROP COLUMN `date`
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_lostpw`
				ADD COLUMN `sent_datetime` DATETIME NOT NULL AFTER `mailid`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/lost_pw.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_lostpw`
				DROP COLUMN `sent_time`
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_market`
				ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `timestamp`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/market.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_market`
				DROP COLUMN `timestamp`
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_message`
				ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `date`,
				ADD COLUMN `read_datetime` DATETIME NOT NULL AFTER `date_read`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/message.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_message`
				DROP COLUMN `date`,
				DROP COLUMN `date_read`
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_news`
				ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `date`,
				ADD COLUMN `changed_datetime` DATETIME NOT NULL AFTER `last_changed`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/news.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_news`
				DROP COLUMN `date`,
				DROP COLUMN `last_changed`
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_res`
				ADD COLUMN `last_datetime` DATETIME NOT NULL AFTER `last_time`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/resources.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_res`
				DROP COLUMN `last_time`
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
		require_once(dirname(__FILE__).'/../../timestamp2datetime/tribunal.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal`
				DROP COLUMN `date`,
				DROP COLUMN `decision_date`
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_arguments`
				ADD COLUMN `added_datetime` DATETIME NOT NULL AFTER `date_added`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/tribunal_arguments.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_arguments`
				DROP COLUMN `date_added`
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_comments`
				ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `date_added`,
				ADD COLUMN `changed_datetime` DATETIME NOT NULL AFTER `date_last_changed`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/tribunal_comments.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_comments`
				DROP COLUMN `date_added`,
				DROP COLUMN `date_last_changed`
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_troops_move`
				ADD COLUMN `end_datetime` DATETIME NOT NULL AFTER `endtime`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/troops_move.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_troops_move`
				DROP COLUMN `endtime`
		');
		require_once(dirname(__FILE__).'/../../timestamp2datetime/users.php');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_user`
				DROP COLUMN `regdate`,
				DROP COLUMN `last_login`
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

//		$result = \util\mysql\query_raw('
//			ALTER TABLE tbl CHANGE col col_to_delete TEXT
//		');

		return 'no undo here';

	}

);