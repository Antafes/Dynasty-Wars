<?php
	include("loggedin/header.php");

	lib_bl_general_loadLanguageFile('usermap');

//abfrage GET, POST variablen
	unset($description);
	$reguid = $_GET["reguid"];
	$fromc = $_GET["fromc"];
	$ber = $_GET["ber"];
	if ($fromc) {
		$fromc = urldecode($fromc);
		$parts = explode("§", $fromc);
		$m = count($parts);
		$n = 0;
		unset($fromc);
		while ($n < $m) {
			$fromc .= $parts[$n];
			if ($n < $m-1) {
				$fromc .= "&amp;";
			}
			$n++;
		}
	}
//db: abfrage userdaten
	$starterg = mysql_query('SELECT cid, rankid FROM dw_user WHERE uid='.$_SESSION['user']->getUid().'', $con);
	if ($starterg) {
		$start = mysql_fetch_array($starterg);
		$_SESSION['user']->getCID() = $start["cid"];
		$rankid = $start["rankid"];
	}
	$ergebnis = mysql_query("SELECT uid, nick, game_rank, regdate, description, cid FROM dw_user WHERE uid='$reguid'", $con);
	if ($ergebnis) {
		$_SESSION = mysql_fetch_array($ergebnis);
		$nick = $_SESSION["nick"];
		$admin = $_SESSION["admin"];
		$regdate = $_SESSION["regdate"];
		$description = $_SESSION["description"];
		$cid = $_SESSION["cid"];
	}
//db: abfrage kartenposition
	$positionerg = mysql_query("SELECT map_x, map_y, city FROM dw_map WHERE uid='$reguid'", $con);
	if ($positionerg) {
		$_SESSIONmap_x = mysql_result($positionerg, 0, 0);
		$_SESSIONmap_y = mysql_result($positionerg, 0, 1);
		$_SESSIONcity = mysql_result($positionerg, 0, 2);
	}
	$ergpoints = mysql_query("SELECT unit_points, building_points FROM dw_points WHERE uid='$reguid'", $con);
	if ($ergpoints) {
		$points = mysql_fetch_array($ergpoints);
		$u_points = $points ["unit_points"];
		$b_points = $points ["building_points"];
	}
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" class="table_tc" colspan="4"><?php echo htmlentities(sprintf($lang["profilefrom"], $nick)) ?></th>
										</tr>
										<tr>
											<td width="660" class="table_tc" colspan="4">&nbsp;</td>
										</tr>
										<tr>
											<td width="130" class="no_content">&nbsp;</td>
											<td width="70" class="table_tc"><?php echo htmlentities($lang["playssince"]) ?>:</td>
											<td width="330" class="table_tl"><?php echo date($lang["timeformat"], $regdate) ?></td>
											<td width="130" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="130" class="no_content">&nbsp;</td>
											<td class="table_tc" width="70"><?php echo htmlentities($lang["position"]) ?>:</td>
											<td class="table_tl" width="330">
												<a href="index.php?chose=map&amp;x=<?php echo $_SESSIONmap_x ?>&amp;y=<?php echo $_SESSIONmap_y ?>"><?php echo "[".$_SESSIONmap_x."<strong>:</strong>".$_SESSIONmap_y."] ".htmlentities($_SESSIONcity) ?></a>
											</td>
											<td width="130" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="130" class="no_content">&nbsp;</td>
											<td width="70" class="table_tc"><?php echo htmlentities($lang["points"]) ?>:</td>
											<td width="330" class="table_tl">
												<?php echo $u_points + $b_points ?>
											</td>
											<td width="130" class="no_content">&nbsp;</td>
										</tr>
<?php
	if ($cid) {
//clananzeige
		$clanerg = mysql_query("SELECT clanname, clantag FROM dw_clan WHERE cid='$cid'", $con);
		if ($clanerg) {
			$clan = mysql_fetch_array($clanerg);
			$clan['clanname'] = $clan["clanname"];
			$clantag = $clan["clantag"];
		}
?>
										<tr>
											<td width="130" class="no_content">&nbsp;</td>
											<td width="70" class="table_tc"><?php echo htmlentities($lang["clan"]) ?>:</td>
											<td width="330" class="table_tl">
												<a href="index.php?chose=clan&amp;cid=<?php echo $cid ?>&amp;clanshow=1"><?php echo $clan['clanname'] ?> [<?php echo $clantag ?>]</a>
											</td>
											<td width="130" class="no_content">&nbsp;</td>
										</tr>
<?php
	}
	if ($description) {
?>
										<tr>
											<td width="130" class="no_content">&nbsp;</td>
											<td width="70" class="table_tc">
												<?php echo htmlentities($lang["description"]) ?>:
											</td>
											<td width="330" class="table_tl">
												<?php echo nl2br(htmlentities($description)) ?>
											</td>
											<td width="130" class="no_content">&nbsp;</td>
										</tr>
<?php
	}
?>
										<tr>
											<td width="130" class="no_content">&nbsp;</td>
											<td width="400" class="table_tc" colspan="2">
												<a href="index.php?chose=<?php echo $fromc ?>"><?php echo htmlentities($lang["back"]) ?></a>
											</td>
											<td width="130" class="no_content">&nbsp;</td>
										</tr>
									</table>
<?php
	include("loggedin/footer.php");
?>