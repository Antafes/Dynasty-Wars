<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'remove the buddhistic temple from all christian players';
	},

	'up' => function ($migration_metadata) {
		require_once(__DIR__.'/../../dw/lib/bl/buildings.inc.php');

		$results = array();

		$sql = '
			SELECT
				b.bid,
				b.lvl,
				b.upgrade_lvl,
				b.kind,
				b.map_x,
				b.map_y,
				u.uid
			FROM dw_user AS u
			JOIN dw_buildings AS b ON (b.uid = u.uid)
			WHERE u.religion = 2
				AND b.kind = 18
		';
		$data = \util\mysql\query($sql, true);

		foreach ($data as $row)
		{
			$resources = array(
				'food' => 0,
				'wood' => 0,
				'rock' => 0,
				'iron' => 0,
				'paper' => 0,
				'koku' => 0,
			);

			for ($i = 0; $i < $row['lvl']; $i++)
			{
				$prices = \bl\buildings\prices($row['kind'], $i, 0, $row['map_x'].':'.$row['map_y']);
				$resources['food'] += round($prices['food']);
				$resources['wood'] += round($prices['wood']);
				$resources['rock'] += round($prices['rock']);
				$resources['iron'] += round($prices['iron']);
				$resources['paper'] += round($prices['paper']);
				$resources['koku'] += round($prices['koku']);
			}

			for ($i = 1; $i < $row['upgrade_lvl']; $i++)
			{
				$prices = \bl\buildings\upgradePrices($row['kind'], $i);
				$resources['food'] += $prices['food'];
				$resources['wood'] += $prices['wood'];
				$resources['rock'] += $prices['rock'];
				$resources['iron'] += $prices['iron'];
				$resources['paper'] += $prices['paper'];
				$resources['koku'] += $prices['koku'];
			}

			foreach ($resources as $resource => $value)
				\bl\resource\addToResources($resource, $value, $row['map_x'].':'.$row['map_y']);

			$sql = '
				DELETE FROM dw_buildings
				WHERE bid = '.\util\mysql\sqlval($row['bid']).'
			';
			$results[] = \util\mysql\query($sql);
		}

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		return ;

	}

);