<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'switch of charset 2';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_build` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_buildings` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_buildings_bak` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_building_stats` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_buildtimes_unit` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_buildtimes_upgr` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_build_unit` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_clan` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_clan_applications` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_clan_rank` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_clan_rankname` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_costs_b` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_costs_b_upgr` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_costs_u` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_game` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_game_menu` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_languages` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_log` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_lostpw` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_map` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_map2` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_map_bak` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_market` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_message` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_missionary` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_news` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_points` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_res` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_research` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_arguments` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_causes` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_comments` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_rules` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal_rules_texts` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_troops` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_troops_move` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_units` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_unit_stats` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
		');
		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_user` CONVERT TO CHARSET utf8 COLLATE "utf8_general_ci"
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