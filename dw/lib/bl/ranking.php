<?php
function lib_bl_ranking_getUserRanking(){
    $ranklist = lib_dal_ranking_getUserRanking();
                    
    foreach($ranklist as &$value){                                                
        $value['regdate'] = date("d.m.Y",$value['regdate']);
    }
    unset($value);
    
    return $ranklist;
}

