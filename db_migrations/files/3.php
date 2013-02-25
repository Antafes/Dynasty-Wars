<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'switch of resource data type';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = \util\mysql\query_raw('
			ALTER TABLE `dw_res`
				CHANGE COLUMN `food` `food` BIGINT NOT NULL DEFAULT "1000" AFTER `map_y`,
				CHANGE COLUMN `wood` `wood` BIGINT NOT NULL DEFAULT "1000" AFTER `food`,
				CHANGE COLUMN `rock` `rock` BIGINT NOT NULL DEFAULT "1000" AFTER `wood`,
				CHANGE COLUMN `iron` `iron` BIGINT NOT NULL DEFAULT "250" AFTER `rock`,
				CHANGE COLUMN `paper` `paper` BIGINT NOT NULL DEFAULT "0" AFTER `iron`,
				CHANGE COLUMN `koku` `koku` BIGINT NOT NULL DEFAULT "0" AFTER `paper`
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		$result = \util\mysql\query_raw('
			ALTER TABLE `dw_res`
				CHANGE COLUMN `food` `food` INT NOT NULL DEFAULT "1000" AFTER `map_y`,
				CHANGE COLUMN `wood` `wood` INT NOT NULL DEFAULT "1000" AFTER `food`,
				CHANGE COLUMN `rock` `rock` INT NOT NULL DEFAULT "1000" AFTER `wood`,
				CHANGE COLUMN `iron` `iron` INT NOT NULL DEFAULT "250" AFTER `rock`,
				CHANGE COLUMN `paper` `paper` INT NOT NULL DEFAULT "0" AFTER `iron`,
				CHANGE COLUMN `koku` `koku` INT NOT NULL DEFAULT "0" AFTER `paper`
		');

		return !!$result;

	}

);