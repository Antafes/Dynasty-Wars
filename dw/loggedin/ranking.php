<?php
	include('loggedin/header.php');
	include('lib/bl/ranking.inc.php');
                
	lib_bl_general_loadLanguageFile('ranking');
	$rank_tab = $_GET['rank_tab'];

        $smarty->assign('lang', $lang);
        $smarty->assign('heading', htmlentities($lang['ranking']));
        
        $smarty->assign('link_player', '<a href="index.php?chose=ranking&amp;rank_tab=1">'.htmlentities($lang['player']).'</a>');
        $smarty->assign('link_clans', '<a href="index.php?chose=ranking&amp;rank_tab=2">'.htmlentities($lang['clans']).'</a>');
                
	//echo '<div style="margin: 0 80px 100px>


		/*echo '<div id="user_info" class="hidden">';
			echo '<div class="row">';
				echo '<div class="left">Dabei seit: </div>';
				echo '<div class="r" id="register"></div>';
			echo '</div>';

			echo '<div class="row">';
				echo '<div class="left">Position: </div>';
				echo '<div class="r" id="pos"></div>';
			echo '</div>';

			echo '<div class="row">';
				echo '<div class="left">Punkte: </div>';
				echo '<div class="r" id="points"></div>';
			echo '</div>';

			echo '<div class="row">';
				echo '<div class="left">Clan: </div>';
				echo '<div class="r" id="clan"></div>';
			echo '</div>';
		echo '</div>';*/




		if (($rank_tab == 1) or (!$rank_tab)) {
                    $rankerg1 = lib_bl_ranking_getUserRanking();                                      
                    
                    $smarty->assign('user_info_since',htmlentities($lang['since']));
                    $smarty->assign('user_info_position',htmlentities($lang['position']));
                    $smarty->assign('user_info_points',htmlentities($lang['points']));
                    $smarty->assign('user_info_clan',htmlentities($lang['clan']));                                        
                                        
                    //Muss beim durchlauf eingetragen werden
                    //$smarty->assign('user_info_link',');
                    
                    
                    $smarty->assign('rank_list',$rankerg1);
                    
                    //if($_SESSION['user']->getUID() != $value['uid']){
                      //  $smarty->assign('user_info_uid',$_SESSION['user']->getUID());
                        
                        /*$message_text = "<a href=\"index.php?chose=messages&amp;mmode=new&amp;recid=".$value['uid']."\">";
                        $message_text .= '<div style="" class="send_msg">&nbsp;</div>';
			$message_text .= '</a>';
                        $smarty->assign('user_send_msg',$message_text);*/
                    //}
                    
			/*foreach ($rankerg1 as $key => $value){
				echo '<tr><td width="90" class="table_tc">'.(int)($key +1).'</td>';
				echo '<td width="140" class="table_tc">';

				echo '<a href="javascript:;" onClick="showUserInfo(this,\'user_info\',\''.date("d.m.Y",$value['regdate']).'\',\''.htmlentities($value['city']).'\','.$value['points'].',\''.$value['clanname'].'\')">';
					echo $value['nick'].'</td>';
				echo '</a>';
				echo '<td width="90" class="table_tc">'.$value['unit_points'].'</td>';
				echo '<td width="90" class="table_tc">'.$value['building_points'].'</td>';
				echo '<td width="90" class="table_tc">'.$value['points'].'</td>';
				echo '<td width="85" class="table_tc">';

				

				echo '</td></tr>';

			}*/
			//echo '</table>';
		}elseif ($rank_tab == 2){
                    $rankerg1 = lib_dal_ranking_getClanRanking();
                    $smarty->assign('table_rank',htmlentities($lang['rank']));
                    $smarty->assign('table_player',htmlentities($lang['player']));
                    $smarty->assign('table_units',htmlentities($lang['units']));
                    $smarty->assign('table_buildings',htmlentities($lang['buildings']));
                    $smarty->assign('table_total',htmlentities($lang['total']));                    
                    $smarty->assign('rank_list',$rankerg1);		
		}

	include('loggedin/footer.php');        
        $smarty->display($smarty->template_dir[0].'ranking.tpl');