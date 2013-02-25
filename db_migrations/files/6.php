<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'tribunal decision adjustment';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal`
				CHANGE COLUMN `decision` `decision` ENUM("undue","nocent","innocent","rejected","other") NOT NULL DEFAULT "undue" COLLATE "utf8_general_ci" AFTER `judge`
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		$result = \util\mysql\query_raw('
			ALTER TABLE `dw_tribunal`
				CHANGE COLUMN `decision` `decision` ENUM("nocent","innocent","rejected","other") NOT NULL DEFAULT "nocent" COLLATE "utf8_general_ci" AFTER `judge`
		');

		return !!$result;

	}

);