<?php
	include('loggedin/header.php');
	include("lib/bl/ranking.inc.php");

	lib_bl_general_loadLanguageFile('ranking');

	$rank_tab = $_GET["rank_tab"];

	echo '<div class="heading" style="float: middle;">';
		echo htmlentities($lang['ranking']);
	echo '</div>';

	echo '<div style="margin: 0 80px 100px;">';

		echo '<div style="float: left; text-align: center; width: 50%;">';
			echo '<a href="index.php?chose=ranking&amp;rank_tab=1">'.htmlentities($lang["player"]).'</a>';
		echo '</div>';

		echo '<div style="float: right; text-align: center; width: 50%;">';
			echo '<a href="index.php?chose=ranking&amp;rank_tab=2">'.htmlentities($lang["clans"]).'</a>';
		echo '</div>';

		echo '<div style="margin-top: 20px; clear: both";>&nbsp;</div>';

		echo '<div id="user_info" class="hidden">';
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
		echo '</div>';



		echo '<table width="100%" class="no_content">';
		echo '<tr><td width="90" class="table_tc">'.htmlentities($lang['rank']).'</td>';
		echo '<td width="140" class="table_tc">'.htmlentities($lang['player']).'</td>';
		echo '<td width="90" class="table_tc">'.htmlentities($lang['units']).'</td>';
		echo '<td width="90" class="table_tc">'.htmlentities($lang["buildings"]).'</td>';
		echo '<td width="90" class="table_tc">'.htmlentities($lang["total"]).'</td>';
		echo '<td width="85" class="table_tc">&nbsp;</td></tr>';


		if (($rank_tab == 1) or (!$rank_tab)) {

			$rankerg1 = lib_dal_ranking_getUserRanking();


			foreach ($rankerg1 as $key => $value){
				echo '<tr><td width="90" class="table_tc">'.(int)($key +1).'</td>';
				echo '<td width="140" class="table_tc">';

				echo '<a href="javascript:;" onClick="showUserInfo(this,\'user_info\',\''.date("d.m.Y",$value['regdate']).'\',\''.htmlentities($value['city']).'\','.$value['points'].',\''.$value['clanname'].'\')">';
					echo $value['nick'].'</td>';
				echo '</a>';
				echo '<td width="90" class="table_tc">'.$value['unit_points'].'</td>';
				echo '<td width="90" class="table_tc">'.$value['building_points'].'</td>';
				echo '<td width="90" class="table_tc">'.$value['points'].'</td>';
				echo '<td width="85" class="table_tc">';

				if($_SESSION['user']->getUID() != $value['uid']){
					echo "<a href=\"index.php?chose=messages&amp;mmode=new&amp;recid=".$value['uid']."\">";
						echo '<div style="" class="send_msg">&nbsp;';

				 		echo '</div>';
				 	echo '</a>';
				}

				echo '</td></tr>';

			}
			echo '</table>';
		}elseif ($rank_tab == 2){
			$rankerg1 = lib_dal_ranking_getClanRanking();


			foreach ($rankerg1 as $key => $value){
				echo '<tr><td width="90" class="table_tc">'.(int)($key +1).'</td>';
				echo '<td width="140" class="table_tc">';
				echo '<a href="index.php?chose=clan&amp;cid='.$value['cid'].'">'.$value['clanname'].'</a>';
				echo '<td width="90" class="table_tc">'.$value['unitPoints'].'</td>';
				echo '<td width="90" class="table_tc">'.$value['buildingPoints'].'</td>';
				echo '<td width="90" class="table_tc">'.$value['points'].'</td></tr>';
			}
			echo '</table>';
		}else{
			echo '</table>';
		}


	echo '</div>';



	include("loggedin/footer.php");
?>