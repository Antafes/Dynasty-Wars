<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'remove the buddhistic temple from all christian players';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$sql = '
			SELECT uid
			FROM dw_user
			WHERE religion = 2
		';
		$christians = \util\mysql\query($sql, true);

		foreach ($christians as $row)
		{
			$results[] = \util\mysql\query('
				DELETE FROM dw_buildings
				WHERE uid = '.\util\mysql\sqlval($row['uid']).'
					AND kind = 18
			') !== false;
		}

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		return ;

	}

);