<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'extend the version field to 20 chars';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_game`
			CHANGE COLUMN `version` `version` VARCHAR(20) NOT NULL AFTER `unitcosts`
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		$result = \util\mysql\query_raw('
			ALTER TABLE `dw_game`
			CHANGE COLUMN `version` `version` VARCHAR(8) NOT NULL AFTER `unitcosts`
		');

		return !!$result;

	}

);