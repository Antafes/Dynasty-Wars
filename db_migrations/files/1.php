<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'create dwars tables';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_build`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_build` (
				`bid` int(255) unsigned NOT NULL DEFAULT "0",
				`upgrade` tinyint(1) unsigned NOT NULL DEFAULT "0",
				`starttime` int(11) unsigned NOT NULL DEFAULT "0",
				`endtime` int(11) unsigned NOT NULL DEFAULT "0",
				PRIMARY KEY (`bid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_buildings`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_buildings` (
				`bid` int(255) NOT NULL AUTO_INCREMENT,
				`uid` int(255) NOT NULL DEFAULT "0",
				`map_x` int(3) NOT NULL DEFAULT "0",
				`map_y` int(3) NOT NULL DEFAULT "0",
				`kind` int(2) NOT NULL DEFAULT "0",
				`lvl` int(255) NOT NULL DEFAULT "0",
				`upgrade_lvl` int(2) NOT NULL DEFAULT "0",
				`position` tinyint(2) unsigned NOT NULL,
				PRIMARY KEY (`bid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_building_stats`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_building_stats` (
				`bsid` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`kind` tinyint(3) unsigned NOT NULL,
				`upgrade_lvl` tinyint(3) unsigned NOT NULL,
				`defense` int(10) unsigned NOT NULL,
				`attack` int(10) unsigned NOT NULL,
				PRIMARY KEY (`bsid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_buildtimes`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_buildtimes` (
				`kind` int(2) NOT NULL DEFAULT "0",
				`btime` int(5) NOT NULL DEFAULT "0",
				PRIMARY KEY (`kind`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_buildtimes_unit`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_buildtimes_unit` (
				`kind` int(2) NOT NULL DEFAULT "0",
				`btime` int(4) NOT NULL DEFAULT "0",
				PRIMARY KEY (`kind`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_buildtimes_upgr`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_buildtimes_upgr` (
				`kind` tinyint(2) NOT NULL,
				`kind_u` tinyint(1) NOT NULL,
				`upgrtime` int(5) NOT NULL,
				PRIMARY KEY (`kind`,`kind_u`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_build_unit`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_build_unit` (
				`tid` int(255) NOT NULL AUTO_INCREMENT,
				`kind` int(1) NOT NULL DEFAULT "0",
				`uid` int(255) NOT NULL DEFAULT "0",
				`count` int(10) NOT NULL DEFAULT "0",
				`starttime` int(11) NOT NULL DEFAULT "0",
				`endtime` int(11) NOT NULL DEFAULT "0",
				`city` varchar(7) COLLATE latin1_german2_ci NOT NULL,
				PRIMARY KEY (`tid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_clan`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_clan` (
				`cid` int(255) NOT NULL AUTO_INCREMENT,
				`clanname` varchar(30) COLLATE latin1_german2_ci NOT NULL,
				`clantag` varchar(5) COLLATE latin1_german2_ci NOT NULL,
				`founder` varchar(20) COLLATE latin1_german2_ci NOT NULL,
				`public_text` text COLLATE latin1_german2_ci NOT NULL,
				`internal_text` text COLLATE latin1_german2_ci NOT NULL,
				PRIMARY KEY (`cid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_clan_applications`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_clan_applications` (
				`appid` int(255) NOT NULL AUTO_INCREMENT,
				`cid` int(255) NOT NULL DEFAULT "0",
				`uid` int(255) NOT NULL DEFAULT "0",
				`applicationtext` text COLLATE latin1_german2_ci NOT NULL,
				`apptime` int(11) NOT NULL DEFAULT "0",
				PRIMARY KEY (`appid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_clan_rank`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_clan_rank` (
				`cid` int(255) NOT NULL DEFAULT "0",
				`rankid` int(2) NOT NULL AUTO_INCREMENT,
				`rnid` int(255) NOT NULL DEFAULT "0",
				`admin` int(1) NOT NULL DEFAULT "0",
				`standard` int(1) NOT NULL DEFAULT "0",
				PRIMARY KEY (`cid`,`rankid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_clan_rankname`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_clan_rankname` (
				`rnid` int(255) NOT NULL AUTO_INCREMENT,
				`rankname` varchar(20) COLLATE latin1_german2_ci NOT NULL,
				PRIMARY KEY (`rnid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_costs_b`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_costs_b` (
				`kind` int(2) NOT NULL,
				`food` int(10) NOT NULL,
				`wood` int(10) NOT NULL,
				`rock` int(10) NOT NULL,
				`iron` int(10) NOT NULL,
				`paper` int(10) NOT NULL,
				`koku` int(10) NOT NULL,
				PRIMARY KEY (`kind`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_costs_b_upgr`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_costs_b_upgr` (
				`kind` tinyint(4) NOT NULL,
				`kind_u` tinyint(1) NOT NULL,
				`food` int(10) NOT NULL,
				`wood` int(10) NOT NULL,
				`rock` int(10) NOT NULL,
				`iron` int(10) NOT NULL,
				`paper` int(10) NOT NULL,
				`koku` int(10) NOT NULL,
				PRIMARY KEY (`kind`,`kind_u`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_costs_u`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_costs_u` (
				`kind` int(2) NOT NULL DEFAULT "0",
				`food` int(6) NOT NULL DEFAULT "0",
				`wood` int(6) NOT NULL DEFAULT "0",
				`rock` int(6) NOT NULL DEFAULT "0",
				`iron` int(6) NOT NULL DEFAULT "0",
				`paper` int(6) NOT NULL DEFAULT "0",
				`koku` int(6) NOT NULL DEFAULT "0",
				PRIMARY KEY (`kind`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_game`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_game` (
				`login_closed` int(1) NOT NULL DEFAULT "0",
				`reg_closed` int(1) NOT NULL DEFAULT "0",
				`board` varchar(50) COLLATE latin1_german2_ci NOT NULL,
				`show_board` int(1) NOT NULL DEFAULT "0",
				`season` int(1) NOT NULL DEFAULT "0",
				`adminmail` varchar(50) COLLATE latin1_german2_ci NOT NULL,
				`error_report` int(2) NOT NULL DEFAULT "0",
				`unitcosts` int(1) NOT NULL DEFAULT "0",
				`version` varchar(8) COLLATE latin1_german2_ci NOT NULL,
				`canattack` int(1) NOT NULL DEFAULT "0"
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_game_menu`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_game_menu` (
				`game_menu_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
				`menu_name` varchar(20) COLLATE latin1_german2_ci NOT NULL,
				`active` tinyint(1) unsigned NOT NULL DEFAULT "1",
				`sort` tinyint(3) unsigned NOT NULL,
				`visible` tinyint(1) unsigned NOT NULL DEFAULT "0",
				PRIMARY KEY (`game_menu_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_languages`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_languages` (
				`language_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`language` char(2) NOT NULL,
				`name` varchar(255) NOT NULL,
				`active` tinyint(1) NOT NULL,
				`fallback` tinyint(1) NOT NULL,
				PRIMARY KEY (`language_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_log`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_log` (
				`actid` int(255) NOT NULL AUTO_INCREMENT,
				`date` int(11) NOT NULL DEFAULT "0",
				`actor` varchar(20) COLLATE latin1_german2_ci NOT NULL,
				`concerned` varchar(20) COLLATE latin1_german2_ci NOT NULL,
				`type` int(10) NOT NULL DEFAULT "0",
				`extra` varchar(50) COLLATE latin1_german2_ci NOT NULL,
				PRIMARY KEY (`actid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_lostpw`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_lostpw` (
				`lpid` int(255) NOT NULL AUTO_INCREMENT,
				`mailid` varchar(30) COLLATE latin1_german2_ci NOT NULL,
				`sent_time` int(11) NOT NULL,
				`uid` int(255) NOT NULL,
				PRIMARY KEY (`lpid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_map`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_map` (
				`map_x` int(3) NOT NULL DEFAULT "0",
				`map_y` int(3) NOT NULL DEFAULT "0",
				`terrain` int(1) DEFAULT NULL,
				`uid` int(255) NOT NULL,
				`city` varchar(20) COLLATE latin1_german2_ci NOT NULL,
				`maincity` int(1) NOT NULL,
				`isle` int(1) NOT NULL,
				`harbour` int(1) NOT NULL,
				PRIMARY KEY (`map_x`,`map_y`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_market`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_market` (
				`mid` int(255) NOT NULL AUTO_INCREMENT,
				`sid` int(255) NOT NULL DEFAULT "0",
				`sx` int(3) NOT NULL,
				`sy` int(3) NOT NULL,
				`bid` int(11) DEFAULT NULL,
				`s_resource` varchar(255) COLLATE latin1_german2_ci NOT NULL,
				`s_amount` int(255) NOT NULL DEFAULT "0",
				`e_resource` varchar(255) COLLATE latin1_german2_ci NOT NULL,
				`e_amount` int(255) NOT NULL DEFAULT "0",
				`tax` int(10) unsigned DEFAULT NULL,
				`complete` tinyint(1) NOT NULL DEFAULT "0",
				`timestamp` int(10) unsigned NOT NULL DEFAULT "1",
				PRIMARY KEY (`mid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_message`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_message` (
				`msgid` int(255) NOT NULL AUTO_INCREMENT,
				`uid_sender` int(255) NOT NULL DEFAULT "0",
				`uid_recipient` int(255) NOT NULL DEFAULT "0",
				`date` int(11) NOT NULL DEFAULT "0",
				`title` varchar(100) COLLATE latin1_german2_ci NOT NULL,
				`message` text COLLATE latin1_german2_ci NOT NULL,
				`unread` int(1) NOT NULL DEFAULT "1",
				`date_read` int(11) NOT NULL,
				`type` int(1) NOT NULL DEFAULT "0",
				`archive` int(1) NOT NULL,
				`del_sender` int(1) NOT NULL,
				`del_recipient` int(1) NOT NULL,
				PRIMARY KEY (`msgid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_missionary`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_missionary` (
				`mid` int(255) NOT NULL AUTO_INCREMENT,
				`uid` int(255) NOT NULL DEFAULT "0",
				PRIMARY KEY (`mid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_news`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_news` (
				`nid` int(255) NOT NULL AUTO_INCREMENT,
				`uid` int(255) NOT NULL DEFAULT "0",
				`nick` varchar(20) COLLATE latin1_german2_ci NOT NULL,
				`title` varchar(100) COLLATE latin1_german2_ci NOT NULL,
				`text` text COLLATE latin1_german2_ci NOT NULL,
				`date` int(11) NOT NULL DEFAULT "0",
				`changed` int(3) NOT NULL DEFAULT "0",
				`last_changed` int(11) NOT NULL DEFAULT "0",
				`changed_uid` int(255) NOT NULL DEFAULT "0",
				`changed_nick` varchar(20) COLLATE latin1_german2_ci NOT NULL,
				PRIMARY KEY (`nid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_points`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_points` (
				`uid` int(255) NOT NULL AUTO_INCREMENT,
				`unit_points` int(255) NOT NULL DEFAULT "0",
				`building_points` int(255) NOT NULL DEFAULT "0",
				PRIMARY KEY (`uid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_res`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_res` (
				`uid` int(255) NOT NULL AUTO_INCREMENT,
				`map_x` int(3) unsigned NOT NULL,
				`map_y` int(3) unsigned NOT NULL,
				`food` bigint(20) NOT NULL DEFAULT "1000",
				`wood` bigint(20) NOT NULL DEFAULT "1000",
				`rock` bigint(20) NOT NULL DEFAULT "1000",
				`iron` bigint(20) NOT NULL DEFAULT "250",
				`paper` bigint(20) NOT NULL DEFAULT "0",
				`koku` bigint(20) NOT NULL DEFAULT "0",
				`last_time` int(11) NOT NULL DEFAULT "0",
				`paper_prod` int(3) NOT NULL DEFAULT "100",
				PRIMARY KEY (`uid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_research`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_research` (
				`rid` int(255) NOT NULL AUTO_INCREMENT,
				`map_x` int(3) NOT NULL DEFAULT "0",
				`map_y` int(3) NOT NULL DEFAULT "0",
				`uid` int(255) NOT NULL DEFAULT "0",
				`type` int(2) NOT NULL DEFAULT "0",
				`class` int(2) NOT NULL DEFAULT "0",
				`lvl` int(255) NOT NULL DEFAULT "0",
				`starttime` int(11) NOT NULL DEFAULT "0",
				PRIMARY KEY (`rid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_tribunal` (
				`tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`suitor` int(10) unsigned NOT NULL,
				`accused` int(10) unsigned NOT NULL,
				`cause` int(10) unsigned NOT NULL,
				`description` text COLLATE latin1_german2_ci NOT NULL,
				`date` int(10) unsigned NOT NULL,
				`judge` int(10) unsigned NOT NULL,
				`decision` enum("nocent","innocent","rejected","other") COLLATE latin1_german2_ci NOT NULL,
				`reason` text COLLATE latin1_german2_ci NOT NULL,
				`decision_date` int(10) unsigned NOT NULL,
				`block_comments` tinyint(1) unsigned NOT NULL DEFAULT "0",
				`deleted` tinyint(1) unsigned NOT NULL DEFAULT "0",
				`deleted_by` int(10) unsigned NOT NULL,
				PRIMARY KEY (`tid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal_arguments`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_tribunal_arguments` (
				`aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`tid` int(10) unsigned NOT NULL,
				`msgid` int(10) unsigned NOT NULL,
				`from` int(10) unsigned NOT NULL,
				`date_added` int(11) NOT NULL,
				`approved` tinyint(4) NOT NULL DEFAULT "0",
				PRIMARY KEY (`aid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal_causes`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_tribunal_causes` (
				`tcid` int(10) unsigned NOT NULL,
				`language` tinyint(1) NOT NULL,
				`cause` varchar(30) COLLATE latin1_german2_ci NOT NULL,
				`sort` int(10) unsigned NOT NULL,
				PRIMARY KEY (`tcid`,`language`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal_comments`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_tribunal_comments` (
				`tcoid` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`tid` int(10) unsigned NOT NULL,
				`writer` int(10) unsigned NOT NULL,
				`comment` text COLLATE latin1_german2_ci NOT NULL,
				`date_added` int(10) unsigned NOT NULL,
				`last_changed_from` int(10) unsigned NOT NULL,
				`date_last_changed` int(10) unsigned NOT NULL,
				`changed_count` tinyint(3) unsigned NOT NULL,
				`deleted` tinyint(1) unsigned NOT NULL DEFAULT "0",
				PRIMARY KEY (`tcoid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal_rules`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_tribunal_rules` (
				`ruid` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`lang` char(2) COLLATE latin1_german2_ci NOT NULL,
				`paragraph` tinyint(3) unsigned NOT NULL,
				`title` varchar(30) COLLATE latin1_german2_ci NOT NULL,
				`deleted` tinyint(1) unsigned NOT NULL DEFAULT "0",
				PRIMARY KEY (`ruid`,`lang`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal_rules_texts`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_tribunal_rules_texts` (
				`rutid` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`ruid` int(10) unsigned NOT NULL,
				`lang` char(2) COLLATE latin1_german2_ci NOT NULL,
				`clause` tinyint(3) unsigned NOT NULL,
				`subclause` tinyint(3) unsigned NOT NULL,
				`description` text COLLATE latin1_german2_ci NOT NULL,
				`deleted` tinyint(1) unsigned NOT NULL DEFAULT "0",
				PRIMARY KEY (`rutid`,`lang`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_troops`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_troops` (
				`tid` int(255) NOT NULL AUTO_INCREMENT,
				`uid` int(255) NOT NULL DEFAULT "0",
				`pos_x` int(3) NOT NULL DEFAULT "0",
				`pos_y` int(3) NOT NULL DEFAULT "0",
				`name` varchar(20) COLLATE latin1_german2_ci NOT NULL,
				`res` varchar(5) COLLATE latin1_german2_ci NOT NULL,
				`amount` int(255) NOT NULL DEFAULT "0",
				PRIMARY KEY (`tid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_troops_move`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_troops_move` (
				`tmid` int(255) NOT NULL AUTO_INCREMENT,
				`tid` int(255) NOT NULL DEFAULT "0",
				`tx` int(3) NOT NULL DEFAULT "0",
				`ty` int(3) NOT NULL DEFAULT "0",
				`type` int(1) NOT NULL DEFAULT "0",
				`endtime` int(11) NOT NULL DEFAULT "0",
				PRIMARY KEY (`tmid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_units`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_units` (
				`unid` int(255) NOT NULL AUTO_INCREMENT,
				`uid` int(255) NOT NULL DEFAULT "0",
				`kind` int(2) NOT NULL DEFAULT "0",
				`count` int(255) NOT NULL DEFAULT "0",
				`pos_x` int(3) NOT NULL DEFAULT "0",
				`pos_y` int(3) NOT NULL DEFAULT "0",
				`tid` int(255) NOT NULL DEFAULT "0",
				PRIMARY KEY (`unid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_unit_stats`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_unit_stats` (
				`usid` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`kind` tinyint(4) NOT NULL,
				`strength` tinyint(4) NOT NULL,
				`moral` tinyint(4) NOT NULL,
				`agility` tinyint(4) NOT NULL,
				`armor` tinyint(4) NOT NULL,
				`speed` tinyint(4) NOT NULL,
				PRIMARY KEY (`usid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_user`
		');
		$results[] = \util\mysql\query_raw('
			CREATE TABLE IF NOT EXISTS `dw_user` (
				`uid` int(255) NOT NULL AUTO_INCREMENT,
				`nick` varchar(20) COLLATE latin1_german2_ci NOT NULL DEFAULT "",
				`password` varchar(32) COLLATE latin1_german2_ci NOT NULL DEFAULT "",
				`email` varchar(50) COLLATE latin1_german2_ci NOT NULL DEFAULT "",
				`blocked` int(1) NOT NULL DEFAULT "0",
				`regdate` int(11) NOT NULL DEFAULT "0",
				`registration_datetime` datetime NOT NULL,
				`game_rank` int(1) NOT NULL DEFAULT "0",
				`rankid` int(2) NOT NULL DEFAULT "0",
				`cid` int(255) NOT NULL DEFAULT "0",
				`description` text COLLATE latin1_german2_ci NOT NULL,
				`last_login` int(11) NOT NULL DEFAULT "0",
				`last_login_datetime` datetime NOT NULL,
				`status` varchar(15) COLLATE latin1_german2_ci NOT NULL DEFAULT "",
				`language` char(2) COLLATE latin1_german2_ci NOT NULL DEFAULT "",
				`religion` int(1) NOT NULL DEFAULT "1",
				`deactivated` int(1) NOT NULL DEFAULT "0",
				PRIMARY KEY (`uid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		$results = array();
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_build`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_buildings`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_building_stats`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_buildtimes`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_buildtimes_unit`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_buildtimes_upgr`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_build_unit`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_clan`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_clan_applications`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_clan_rank`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_clan_rankname`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_costs_b`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_costs_b_upgr`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_costs_u`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_game`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_game_menu`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_languages`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_log`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_lostpw`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_map`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_market`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_message`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_missionary`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_news`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_points`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_res`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_research`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal_arguments`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal_causes`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal_comments`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal_rules`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_tribunal_rules_texts`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_troops`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_troops_move`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_units`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_unit_stats`
		');
		$results[] = \util\mysql\query_raw('
			DROP TABLE IF EXISTS `dw_user`
		');

		return !in_array(false, $results);

	}

);