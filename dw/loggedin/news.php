<?php
	include ("loggedin/header.php");
	
	lib_bl_general_loadLanguageFile('news');
	
//db: auslesen der news
	$parser = new wikiparser();
	$starterg = mysql_query("SELECT * FROM dw_news ORDER BY nid DESC", $con);
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" colspan="3" class="table_tc"><?php echo htmlentities($lang["news"]) ?></th>
										</tr>
<?php
	if (mysql_num_rows($starterg)) {
		$zeilen = mysql_num_rows($starterg);
		$pages = ceil($zeilen/5);
		$page = $_GET["page"];
		if (!$page) {
			$page = 1;
		}
		$n = 5 * $page - 5;
		$p = 5 * $page;
		while ($n < $p and $n < $zeilen) {
?>
										<tr>
											<td width="135" class="no_content">&nbsp;</td>
											<td width="400" class="table_tl">
												<table width="400" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td width="33" height="68" class="news_top_left">&nbsp;</td>
														<td width="334" height="68" class="news_top">
															<span class="news1">Dynasty Wars</span> - <?php echo htmlentities(mysql_result($starterg, $n, 3)) ?><br />
<?php
//auslesen des nicks und der email des verfassers
			$nreguid = mysql_result($starterg, $n, 1);
			$nickerg = mysql_query("SELECT nick, email FROM dw_user where uid=$nreguid", $con);
			if ($nickerg) {
				$nicks = mysql_fetch_array($nickerg);
				$regnick = $nicks["nick"];
			}
?>
															<span class="span3"><?php echo htmlentities($lang["from"]) ?> <a href="index.php?chose=usermap&amp;reguid=<?php echo $nreguid ?>&amp;fromc=<?php echo rawurlencode('news§page=1') ?>"><?php echo htmlentities($regnick) ?></a> (<?php echo date($lang["acptimeformat"], mysql_result($starterg, $n, 5)) ?>)</span>
														</td>
														<td width="33" height="68" class="news_top_right">&nbsp;</td>
													</tr>
													<tr>
														<td width="33" class="news_left">&nbsp;</td>
														<td width="334" class="news_middle">
															<?php echo nl2br($parser->parseIt(mysql_result($starterg, $n, 4))) ?>
<?php
			if (mysql_result($starterg, $n, 6)) {
				$helperg = mysql_query("SELECT nick FROM dw_user WHERE uid='".mysql_result($starterg, $n, 8)."'", $con);
				if ($helperg) {
					$changer = mysql_result($helperg, 0);
				}
				if (mysql_result($starterg, $n, 6) > 1) {
					$count = mysql_result($starterg, $n, 6);
				} else {
					$count = "ein";
				}
?>
															<br /><br />
															<span class="news_edited">
																<?php echo htmlentities(sprintf($lang["newschanged"], $count, date($lang['timeformat'], mysql_result($starterg, $n, 7)), $changer)) ?>
															</span>
<?php
			}
?>
														</td>
														<td width="33" class="news_right">&nbsp;</td>
													</tr>
													<tr>
														<td width="33" height="25" class="news_bottom_left">&nbsp;</td>
														<td width="334" height="25" class="news_bottom">&nbsp;</td>
														<td width="33" height="25" class="news_bottom_right">&nbsp;</td>
													</tr>
												</table>
											</td>
											<td width="135" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="660" colspan="3" class="no_content">&nbsp;</td>
										</tr>
<?php
			$n++;
		}
?>
										<tr>
											<td width="660" colspan="3" class="table_tc">
<?php
		$m = 1;
		while ($m <= $pages) {
?>
												<a href="index.php?chose=news&amp;page=<?php echo $m ?>"><?php echo $m ?></a>
<?php
			$m++;
		}
?>
											</td>
										</tr>
<?php
	} else {
?>
										<tr>
											<td width="660" class="table_tc" colspan="3">
												<?php echo htmlentities($lang["nonews"]) ?>
											</td>
										</tr>
<?php
	}
?>
									</table>
<?php
	include ("loggedin/footer.php");
?>