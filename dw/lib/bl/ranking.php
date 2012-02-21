<?php
function lib_bl_ranking_getUserRanking()
{
	global $lang;

    $ranklist = lib_dal_ranking_getUserRanking();

    foreach($ranklist as &$value)
	{
		$date = DWDateTime::createFromFormat('Y-m-d H:i:s', $value['registration_datetime']);
        $value['registration_datetime'] = $date->format($lang['timeformat']);
		$value['city'] = '['.$value['map_x'].':'.$value['map_y'].']';
    }
    unset($value);

    return $ranklist;
}

