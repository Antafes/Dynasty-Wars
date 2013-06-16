<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'change charset to UTF-8';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_build_unit`
				CHANGE COLUMN `city` `city` VARCHAR(7) NOT NULL COLLATE "utf8_general_ci" AFTER `end_datetime`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_clan`
				CHANGE COLUMN `clanname` `clanname` VARCHAR(30) NOT NULL COLLATE "utf8_general_ci" AFTER `cid`,
				CHANGE COLUMN `clantag` `clantag` VARCHAR(5) NOT NULL COLLATE "utf8_general_ci" AFTER `clanname`,
				CHANGE COLUMN `founder` `founder` VARCHAR(20) NOT NULL COLLATE "utf8_general_ci" AFTER `clantag`,
				CHANGE COLUMN `public_text` `public_text` TEXT NOT NULL COLLATE "utf8_general_ci" AFTER `founder`,
				CHANGE COLUMN `internal_text` `internal_text` TEXT NOT NULL COLLATE "utf8_general_ci" AFTER `public_text`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_clan_applications`
				CHANGE COLUMN `applicationtext` `applicationtext` TEXT NOT NULL COLLATE "utf8_general_ci" AFTER `uid`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_clan_rankname`
				CHANGE COLUMN `rankname` `rankname` VARCHAR(20) NOT NULL COLLATE "utf8_general_ci" AFTER `rnid`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_game`
				CHANGE COLUMN `board` `board` VARCHAR(50) NOT NULL COLLATE "utf8_general_ci" AFTER `reg_closed`,
				CHANGE COLUMN `adminmail` `adminmail` VARCHAR(50) NOT NULL COLLATE "utf8_general_ci" AFTER `season`,
				CHANGE COLUMN `version` `version` VARCHAR(8) NOT NULL COLLATE "utf8_general_ci" AFTER `unitcosts`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_game_menu`
				CHANGE COLUMN `menu_name` `menu_name` VARCHAR(20) NOT NULL COLLATE "utf8_general_ci" AFTER `game_menu_id`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_languages`
				CHANGE COLUMN `language` `language` CHAR(2) NOT NULL COLLATE "utf8_general_ci" AFTER `language_id`,
				CHANGE COLUMN `name` `name` VARCHAR(255) NOT NULL COLLATE "utf8_general_ci" AFTER `language`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_log`
				CHANGE COLUMN `actor` `actor` VARCHAR(20) NOT NULL COLLATE "utf8_general_ci" AFTER `log_datetime`,
				CHANGE COLUMN `concerned` `concerned` VARCHAR(20) NOT NULL COLLATE "utf8_general_ci" AFTER `actor`,
				CHANGE COLUMN `extra` `extra` VARCHAR(50) NOT NULL COLLATE "utf8_general_ci" AFTER `type`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_lostpw`
				CHANGE COLUMN `mailid` `mailid` VARCHAR(30) NOT NULL COLLATE "utf8_general_ci" AFTER `lpid`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_map`
				CHANGE COLUMN `city` `city` VARCHAR(20) NOT NULL COLLATE "utf8_general_ci" AFTER `uid`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_market`
				CHANGE COLUMN `s_resource` `s_resource` VARCHAR(255) NOT NULL DEFAULT "" COLLATE "utf8_general_ci" AFTER `bid`,
				CHANGE COLUMN `e_resource` `e_resource` VARCHAR(255) NOT NULL DEFAULT "" COLLATE "utf8_general_ci" AFTER `s_amount`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_message`
				CHANGE COLUMN `title` `title` VARCHAR(100) NOT NULL COLLATE "utf8_general_ci" AFTER `create_datetime`,
				CHANGE COLUMN `message` `message` TEXT NOT NULL COLLATE "utf8_general_ci" AFTER `title`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_news`
				CHANGE COLUMN `nick` `nick` VARCHAR(20) NOT NULL COLLATE "utf8_general_ci" AFTER `uid`,
				CHANGE COLUMN `title` `title` VARCHAR(100) NOT NULL COLLATE "utf8_general_ci" AFTER `nick`,
				CHANGE COLUMN `text` `text` TEXT NOT NULL COLLATE "utf8_general_ci" AFTER `title`,
				CHANGE COLUMN `changed_nick` `changed_nick` VARCHAR(20) NOT NULL COLLATE "utf8_general_ci" AFTER `changed_uid`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal`
				CHANGE COLUMN `description` `description` TEXT NOT NULL COLLATE "utf8_general_ci" AFTER `cause`,
				CHANGE COLUMN `decision` `decision` ENUM("nocent","innocent","rejected","other") NOT NULL DEFAULT "nocent" COLLATE "utf8_general_ci" AFTER `judge`,
				CHANGE COLUMN `reason` `reason` TEXT NOT NULL COLLATE "utf8_general_ci" AFTER `decision`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_causes`
				CHANGE COLUMN `cause` `cause` VARCHAR(30) NOT NULL COLLATE "utf8_general_ci" AFTER `language`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_comments`
				CHANGE COLUMN `comment` `comment` TEXT NOT NULL COLLATE "utf8_general_ci" AFTER `writer`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_rules`
				CHANGE COLUMN `lang` `lang` CHAR(2) NOT NULL COLLATE "utf8_general_ci" AFTER `ruid`,
				CHANGE COLUMN `title` `title` VARCHAR(30) NOT NULL COLLATE "utf8_general_ci" AFTER `paragraph`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_rules_texts`
				CHANGE COLUMN `lang` `lang` CHAR(2) NOT NULL COLLATE "utf8_general_ci" AFTER `ruid`,
				CHANGE COLUMN `description` `description` TEXT NOT NULL COLLATE "utf8_general_ci" AFTER `subclause`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_troops`
				CHANGE COLUMN `name` `name` CHAR(20) NOT NULL COLLATE "utf8_general_ci" AFTER `pos_y`,
				CHANGE COLUMN `res` `res` CHAR(5) NOT NULL COLLATE "utf8_general_ci" AFTER `name`
		');

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_user`
				CHANGE COLUMN `nick` `nick` VARCHAR(20) NOT NULL COLLATE "utf8_general_ci" AFTER `uid`,
				CHANGE COLUMN `password` `password` VARCHAR(32) NOT NULL COLLATE "utf8_general_ci" AFTER `nick`,
				CHANGE COLUMN `email` `email` VARCHAR(50) NOT NULL COLLATE "utf8_general_ci" AFTER `password`,
				CHANGE COLUMN `description` `description` TEXT NOT NULL COLLATE "utf8_general_ci" AFTER `cid`,
				CHANGE COLUMN `status` `status` VARCHAR(15) NOT NULL COLLATE "utf8_general_ci" AFTER `last_login_datetime`,
				CHANGE COLUMN `language` `language` CHAR(2) NOT NULL COLLATE "utf8_general_ci" AFTER `status`
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		return ;

	}

);