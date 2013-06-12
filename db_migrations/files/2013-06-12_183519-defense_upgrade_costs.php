<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'upgrade costs for defense buildings';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		// query with noTransform, because the table has no auto_increment key
		$results[] = \util\mysql\query('
			INSERT INTO `dw_costs_b_upgr` (`kind`, `kind_u`, `food`, `wood`, `rock`, `iron`, `paper`, `koku`)
			VALUES (23, 0, 5000, 5000, 2500, 1000, 0, 3000)
		', true);

		$results[] = \util\mysql\query('
			INSERT INTO `dw_costs_b_upgr` (`kind`, `kind_u`, `food`, `wood`, `rock`, `iron`, `paper`, `koku`)
			VALUES (23, 1, 50000, 50000, 10000, 5000, 0, 60000)
		', true);

		$results[] = \util\mysql\query('
			INSERT INTO `dw_costs_b_upgr` (`kind`, `kind_u`, `food`, `wood`, `rock`, `iron`, `paper`, `koku`)
			VALUES (23, 2, 100000, 50000, 100000, 30000, 0, 150000)
		', true);

		$results[] = \util\mysql\query('
			INSERT INTO `dw_costs_b_upgr` (`kind`, `kind_u`, `food`, `wood`, `rock`, `iron`, `paper`, `koku`)
			VALUES (23, 3, 350000, 100000, 400000, 70000, 0, 400000)
		', true);

		$results[] = \util\mysql\query('
			INSERT INTO `dw_costs_b_upgr` (`kind`, `kind_u`, `food`, `wood`, `rock`, `iron`, `paper`, `koku`)
			VALUES (24, 0, 5000, 6000, 1000, 2000, 0, 4000)
		', true);

		$results[] = \util\mysql\query('
			INSERT INTO `dw_costs_b_upgr` (`kind`, `kind_u`, `food`, `wood`, `rock`, `iron`, `paper`, `koku`)
			VALUES (24, 1, 10000, 11000, 3500, 5000, 0, 11000)
		', true);

		$results[] = \util\mysql\query('
			INSERT INTO `dw_costs_b_upgr` (`kind`, `kind_u`, `food`, `wood`, `rock`, `iron`, `paper`, `koku`)
			VALUES (24, 2, 90000, 30000, 80000, 10000, 0, 120000)
		', true);

		$results[] = \util\mysql\query('
			INSERT INTO `dw_costs_b_upgr` (`kind`, `kind_u`, `food`, `wood`, `rock`, `iron`, `paper`, `koku`)
			VALUES (24, 3, 170000, 50000, 170000, 30000, 0, 250000)
		', true);

		$results[] = \util\mysql\query('
			INSERT INTO `dw_costs_b_upgr` (`kind`, `kind_u`, `food`, `wood`, `rock`, `iron`, `paper`, `koku`)
			VALUES (25, 0, 20000, 35000, 20000, 10000, 5000, 40000)
		', true);

		$results[] = \util\mysql\query('
			INSERT INTO `dw_costs_b_upgr` (`kind`, `kind_u`, `food`, `wood`, `rock`, `iron`, `paper`, `koku`)
			VALUES (25, 1, 75000, 90000, 70000, 25000, 20000, 110000)
		', true);

		$results[] = \util\mysql\query('
			INSERT INTO `dw_costs_b_upgr` (`kind`, `kind_u`, `food`, `wood`, `rock`, `iron`, `paper`, `koku`)
			VALUES (25, 2, 200000, 180000, 300000, 250000, 100000, 500000)
		', true);

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		$result = \util\mysql\query('
			DELETE FROM `dw_costs_b_upgr`
			WHERE `kind` IN (23, 24, 25)
		');

		return !!$result;

	}

);