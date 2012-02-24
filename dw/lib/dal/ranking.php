<?php
/**
 * Returns the User Ranking
 * @author BlackIce
 * @return array
 */
function lib_dal_ranking_getUserRanking(){
	$sql = '
		SELECT
			dw_points.uid,
			user.registration_datetime,
			unit_points,
			building_points,
			unit_points + building_points points,
			nick,
			blocked,
			map.map_x,
			map.map_y,
			dw_clan.clanname
		FROM dw_points
		JOIN dw_user user ON dw_points.uid=user.uid
		JOIN dw_map map ON map.uid = user.uid
		LEFT JOIN dw_clan ON dw_clan.cid = user.cid
		WHERE !deactivated
		ORDER BY points DESC
	';

	return util\mysql\query($sql, true);
}

/**
 * Returns the Clan Ranking
 * @author BlackIce
 * @return array
 */
function lib_dal_ranking_getClanRanking(){
	$SQL = 'SELECT dw_clan.cid, sum(unit_points) unitPoints,
			sum(building_points) buildingPoints, sum(unit_points+building_points) AS points,
			clanname
			FROM dw_clan
			LEFT OUTER JOIN dw_user ON dw_clan.cid=dw_user.cid
			LEFT OUTER JOIN dw_points ON dw_user.uid=dw_points.uid
			WHERE NOT deactivated GROUP BY dw_clan.cid ORDER BY points DESC';
	return util\mysql\query($SQL,True);
}
?>