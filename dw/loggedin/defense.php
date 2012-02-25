<?php
	include('loggedin/header.php');
//language
	if ($lang['lang']) {
		include ('language/'.$lang['lang'].'/ingame/defense.php');
	} else {
		include ('language/en/ingame/defense.php');
	}
//requesting of get and post variables
	$construct = $_POST['construct'];
	$upgrade = $_POST['upgrade'];
	$building = $_GET['building'];
	$bid = $_GET['bid'];
//selection of the user informations
	$starterg = mysql_query('SELECT cid, rankid, religion FROM dw_user WHERE uid='.$user->getUID().'', $con);
	if ($starterg) {
		$start = mysql_fetch_array($starterg);
		$user->getCID() = $start['cid'];
		$rankid = $start['rankid'];
		$religion = $start['religion'];
	}
//start building
	if ($construct) {
		$erg2 = build(2, mysql_real_escape_string($building), 0, mysql_real_escape_string($bid), $user->getUID(), $city, $con);
		$ressources = bl\resource\newResources($user->getUID(), $range, $lumberjack, $quarry, $ironmine, $papermill, $tradepost, $harbour, $con);
		$food = $ressources['food'];
		$wood = $ressources['wood'];
		$rock = $ressources['rock'];
		$iron = $ressources['iron'];
		$paper = $ressources['paper'];
		$koku = $ressources['koku'];
		$build_check = check_build($user->getUID(), $city, 2, $con);
	} elseif ($upgrade) {
		$erg2 = build(2, mysql_real_escape_string($building), 1, mysql_real_escape_string($bid), $user->getUID(), $city, $con);
		$ressources = bl\resource\newResources($user->getUID(), $range, $lumberjack, $quarry, $ironmine, $papermill, $tradepost, $harbour, $con);
		$food = $ressources['food'];
		$wood = $ressources['wood'];
		$rock = $ressources['rock'];
		$iron = $ressources['iron'];
		$paper = $ressources['paper'];
		$koku = $ressources['koku'];
		$build_check = check_build($user->getUID(), $city, 2, $con);
	}
	if ($build_check['ok'] and ($construct xor $upgrade)) {
?>
									<meta http-equiv="refresh" content="0;URL=index.php?chose=defense" />
<?php
	}
	$main = building(20, 1, $user->getUID(), $city, $con);
?>
									<table width="670" border="0" class="box_3">
										<tr>
											<th width="670" class="box_8"><font size="5"><?php echo htmlentities($lang['b_defense']) ?></font></th>
										</tr>
										<tr>
											<td width="670" class="box_3">&nbsp;</td>
										</tr>
<?php
	if ($main['upgrade_lvl'] < 2) {
?>
										<tr>
											<td width="670" class="box_8">
												<?php echo htmlentities($lang['nobuild']) ?>
											</td>
										</tr>
<?php
	} elseif ($main['upgrade_lvl'] >= 2) {
?>
										<tr>
											<td width="670" class="box_8">
<?php
	$wall = building(1, 2, $user->getUID(), $city, $con);
	$prices = prices(1, 2, $wall['lvl']);
	if ($wall['upgrade_lvl'] < 4) {
		$prices_upgr = upgrade_prices("1.".$wall['upgrade_lvl'], 2, $wall['lvl'], $wall['upgrade_lvl'], $con);
	}
?>
												<form method="post" action="index.php?chose=defense&amp;build=1&amp;building=1&amp;bid=<?php echo $wall['bid'] ?>">
												<table width="660" border="0" class="box_3">
													<tr>
														<td width="140" class="box_6">
															<img src="pictures/defense/wall.png" alt="<?php echo htmlentities($lang['wall'][1]) ?>" title="<?php echo htmlentities($lang['wall'][1]) ?>"/>
														</td>
														<td width="520" class="box_2">
															<strong><?php
	if ($wall['upgrade_lvl'] <= 1) {
		echo htmlentities($lang['wall'][1]);
	} else {
		echo htmlentities($lang['wall'][$wall['upgrade_lvl']]);
	}
?></strong> (<?php echo htmlentities($lang['b_level']) ?>: <?php if ($wall['lvl']) { echo $wall['lvl']; } else {  echo '0'; }?>)<br />
															<?php echo nl2br(htmlentities($lang["b_descr"][1])) ?><br />
															<table width="510" height="100" border="0" class="box_3">
																<tr>
																	<td width="510" colspan="2" class="box_3">&nbsp;</td>
																</tr>
																<tr>
																	<td width="410" height="50" class="box_7">
																		<strong><?php echo htmlentities($lang["b_lvlup"]) ?>:</strong><br />
<?php
	$check = rescheck($food, $wood, $rock, $iron, $paper, $koku, $prices["food"], $prices["wood"], $prices["rock"], $prices["iron"], $prices["paper"], $prices["koku"]);
?>
																		<strong><?php echo htmlentities($lang["food"]) ?>:&nbsp;<?php echo number_format($prices["food"], 0, ",", ".") ?> <?php echo htmlentities($lang["wood"]) ?>:&nbsp;<?php echo number_format($prices["wood"], 0, ",", ".") ?> <?php echo htmlentities($lang["rock"]) ?>:&nbsp;<?php echo number_format($prices["rock"], 0, ",", ".") ?><br/>
																		<?php echo htmlentities($lang["iron"]) ?>:&nbsp;<?php echo number_format($prices["iron"], 0, ",", ".") ?> <?php echo htmlentities($lang["paper"]) ?>:&nbsp;<?php echo number_format($prices["paper"], 0, ",", ".") ?> <?php echo htmlentities($lang["koku"]) ?>:&nbsp;<?php echo number_format($prices["koku"], 0, ",", ".") ?></strong>
																	</td>
<?php
	if ($build_check["ok"] and $build_check["class"] == 2) {
?>
																	<td width="100" class="box_6" rowspan="2">
																		<strong><span id="1"></span></strong>
<?php
	} else {
?>
																	<td width="100" class="box_6">
																		<input type="submit" name="construct" value="<?php echo htmlentities($lang["b_build"]) ?>"<?php if (($check == 0 or $wall["upgrade_lvl"] < 1) or $own_uid) {?> disabled="disabled"<?php } ?>/><br/>
																		<strong><?php echo htmlentities($lang["b_time"]) ?>:<br/><?php echo format_time(build_time(2, 1, $wall["lvl"], $con), "h:m:s") ?></strong>
<?php
	}
?>
																	</td>
																</tr>
																<tr>
																	<td width="420" height="50" class="box_7">
																		<strong><?php echo htmlentities($lang["b_upgrade"]) ?>:</strong><br />
<?php
	$check2 = rescheck($food, $wood, $rock, $iron, $paper, $koku, $prices_upgr["food"], $prices_upgr["wood"], $prices_upgr["rock"], $prices_upgr["iron"], $prices_upgr["paper"], $prices_upgr["koku"]);
?>
																		<strong><?php echo htmlentities($lang["food"]) ?>:&nbsp;<?php echo number_format($prices_upgr["food"], 0, ",", ".") ?> <?php echo htmlentities($lang["wood"]) ?>:&nbsp;<?php echo number_format($prices_upgr["wood"], 0, ",", ".") ?> <?php echo htmlentities($lang["rock"]) ?>:&nbsp;<?php echo number_format($prices_upgr["rock"], 0, ",", ".") ?><br/>
																		<?php echo htmlentities($lang["iron"]) ?>:&nbsp;<?php echo number_format($prices_upgr["iron"], 0, ",", ".") ?> <?php echo htmlentities($lang["paper"]) ?>:&nbsp;<?php echo number_format($prices_upgr["paper"], 0, ",", ".") ?> <?php echo htmlentities($lang["koku"]) ?>:&nbsp;<?php echo number_format($prices_upgr["koku"], 0, ",", ".") ?></strong>
																	</td>
<?php
	if (!$build_check["ok"] or $build_check["class"] != 2) {
?>
																	<td width="100" class="box_8">
																		<input type="submit" name="upgrade" value="<?php echo htmlentities($lang["b_build"]) ?>"<?php if (($check2 == 0 or $wall["upgrade_lvl"] >= 4 or $wall["lvl"]<$wall["upgrade_lvl"]*10) or $own_uid) {?> disabled="disabled"<?php }?>/><br/>
<?php
		if ($wall["upgrade_lvl"] < 4) {
?>
																		<strong><?php echo htmlentities($lang["b_time"]) ?>:<br/><?php echo format_time(build_time(2, "1.".$wall["upgrade_lvl"], $wall["lvl"], $con), "h:m:s") ?></strong>
<?php
		}
?>
																	</td>
<?php
	}
?>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												</form>
											</td>
										</tr>
										<tr>
											<td width="670" class="box_8">
<?php
	$tower = building(2, 2, $user->getUID(), $city, $con);
	$prices = prices(2, 2, $tower["lvl"]);
	if ($tower["upgrade_lvl"] < 4) {
		$prices_upgr = upgrade_prices("2.".$tower["upgrade_lvl"], 2, $tower["lvl"], $tower["upgrade_lvl"], $con);
	}
?>
												<form method="post" action="index.php?chose=defense&amp;build=1&amp;building=2&amp;bid=<?php echo $tower["bid"] ?>">
												<table width="660" border="0" class="box_3">
													<tr>
														<td width="140" class="box_6">
															<img src="pictures/defense/tower.png" alt="<?php echo htmlentities($lang["tower"][1]) ?>" title="<?php echo htmlentities($lang["tower"][1]) ?>"/>
														</td>
														<td width="520" class="box_2">
															<strong><?php
	if ($tower["upgrade_lvl"] <= 1) {
		echo htmlentities($lang["tower"][1]);
	} else {
		echo htmlentities($lang["tower"][$tower["upgrade_lvl"]]);
	}
?></strong> (<?php echo htmlentities($lang["b_level"]) ?>: <?php if ($tower["lvl"]) { echo $tower["lvl"]; } else {  echo "0"; }?>)<br />
															<?php echo nl2br(htmlentities($lang["b_descr"][2])) ?><br />
															<table width="510" height="100" border="0" class="box_3">
																<tr>
																	<td width="510" colspan="2" class="box_3">&nbsp;</td>
																</tr>
																<tr>
																	<td width="410" height="50" class="box_7">
																		<strong><?php echo htmlentities($lang["b_lvlup"]) ?>:</strong><br />
<?php
	$check = rescheck($food, $wood, $rock, $iron, $paper, $koku, $prices["food"], $prices["wood"], $prices["rock"], $prices["iron"], $prices["paper"], $prices["koku"]);
?>
																		<strong><?php echo htmlentities($lang["food"]) ?>:&nbsp;<?php echo number_format($prices["food"], 0, ",", ".") ?> <?php echo htmlentities($lang["wood"]) ?>:&nbsp;<?php echo number_format($prices["wood"], 0, ",", ".") ?> <?php echo htmlentities($lang["rock"]) ?>:&nbsp;<?php echo number_format($prices["rock"], 0, ",", ".") ?><br/>
																		<?php echo htmlentities($lang["iron"]) ?>:&nbsp;<?php echo number_format($prices["iron"], 0, ",", ".") ?> <?php echo htmlentities($lang["paper"]) ?>:&nbsp;<?php echo number_format($prices["paper"], 0, ",", ".") ?> <?php echo htmlentities($lang["koku"]) ?>:&nbsp;<?php echo number_format($prices["koku"], 0, ",", ".") ?></strong>
																	</td>
<?php
	if ($build_check["ok"] and $build_check["class"] == 2) {
?>
																	<td width="100" class="box_6" rowspan="2">
																		<strong><span id="2"></span></strong>
<?php
	} else {
?>
																	<td width="100" class="box_6">
																		<input type="submit" name="construct" value="<?php echo htmlentities($lang["b_build"]) ?>"<?php if (($check == 0 or $tower["upgrade_lvl"] < 1) or $own_uid) {?> disabled="disabled"<?php } ?>/><br/>
																		<strong><?php echo htmlentities($lang["b_time"]) ?>:<br/><?php echo format_time(build_time(2, 2, $tower["lvl"], $con), "h:m:s") ?></strong>
<?php
	}
?>
																	</td>
																</tr>
																<tr>
																	<td width="420" height="50" class="box_7">
																		<strong><?php echo htmlentities($lang["b_upgrade"]) ?>:</strong><br />
<?php
	$check2 = rescheck($food, $wood, $rock, $iron, $paper, $koku, $prices_upgr["food"], $prices_upgr["wood"], $prices_upgr["rock"], $prices_upgr["iron"], $prices_upgr["paper"], $prices_upgr["koku"]);
?>
																		<strong><?php echo htmlentities($lang["food"]) ?>:&nbsp;<?php echo number_format($prices_upgr["food"], 0, ",", ".") ?> <?php echo htmlentities($lang["wood"]) ?>:&nbsp;<?php echo number_format($prices_upgr["wood"], 0, ",", ".") ?> <?php echo htmlentities($lang["rock"]) ?>:&nbsp;<?php echo number_format($prices_upgr["rock"], 0, ",", ".") ?><br/>
																		<?php echo htmlentities($lang["iron"]) ?>:&nbsp;<?php echo number_format($prices_upgr["iron"], 0, ",", ".") ?> <?php echo htmlentities($lang["paper"]) ?>:&nbsp;<?php echo number_format($prices_upgr["paper"], 0, ",", ".") ?> <?php echo htmlentities($lang["koku"]) ?>:&nbsp;<?php echo number_format($prices_upgr["koku"], 0, ",", ".") ?></strong>
																	</td>
<?php
	if (!$build_check["ok"] or $build_check["class"] != 2) {
?>
																	<td width="100" class="box_8">
																		<input type="submit" name="upgrade" value="<?php echo htmlentities($lang["b_build"]) ?>"<?php if (($check2 == 0 or $tower["upgrade_lvl"] >= 4 or $tower["lvl"]<$tower["upgrade_lvl"]*10) or $own_uid) {?> disabled="disabled"<?php }?>/><br/>
<?php
		if ($tower["upgrade_lvl"] < 4) {
?>
																		<strong><?php echo htmlentities($lang["b_time"]) ?>:<br/><?php echo format_time(build_time(2, "2.".$tower["upgrade_lvl"], $tower["lvl"], $con), "h:m:s") ?></strong>
<?php
		}
?>
																	</td>
<?php
	}
?>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												</form>
											</td>
										</tr>
<?php
		if ($main["upgrade_lvl"] >= 3) {
?>
										<tr>
											<td width="670" class="box_8">
<?php
	$camp = building(3, 2, $user->getUID(), $city, $con);
	$prices = prices(3, 2, $camp["lvl"]);
	if ($camp["upgrade_lvl"] < 4) {
		$prices_upgr = upgrade_prices("3.".$camp["upgrade_lvl"], 2, $camp["lvl"], $camp["upgrade_lvl"], $con);
	}
?>
												<form method="post" action="index.php?chose=defense&amp;build=1&amp;building=3&amp;bid=<?php echo $camp["bid"] ?>">
												<table width="660" border="0" class="box_3">
													<tr>
														<td width="140" class="box_6">
															<img src="pictures/defense/camp.png" alt="<?php echo htmlentities($lang["camp"][1]) ?>" title="<?php echo htmlentities($lang["camp"][1]) ?>"/>
														</td>
														<td width="520" class="box_2">
															<strong><?php
	if ($camp["upgrade_lvl"] <= 1) {
		echo htmlentities($lang["camp"][1]);
	} else {
		echo htmlentities($lang["camp"][$camp["upgrade_lvl"]]);
	}
?></strong> (<?php echo htmlentities($lang["b_level"]) ?>: <?php if ($camp["lvl"]) { echo $camp["lvl"]; } else {  echo "0"; }?>)<br />
															<?php echo nl2br(htmlentities($lang["b_descr"][3])) ?><br />
															<table width="510" height="100" border="0" class="box_3">
																<tr>
																	<td width="510" colspan="2" class="box_3">&nbsp;</td>
																</tr>
																<tr>
																	<td width="410" height="50" class="box_7">
																		<strong><?php echo htmlentities($lang["b_lvlup"]) ?>:</strong><br />
<?php
	$check = rescheck($food, $wood, $rock, $iron, $paper, $koku, $prices["food"], $prices["wood"], $prices["rock"], $prices["iron"], $prices["paper"], $prices["koku"]);
?>
																		<strong><?php echo htmlentities($lang["food"]) ?>:&nbsp;<?php echo number_format($prices["food"], 0, ",", ".") ?> <?php echo htmlentities($lang["wood"]) ?>:&nbsp;<?php echo number_format($prices["wood"], 0, ",", ".") ?> <?php echo htmlentities($lang["rock"]) ?>:&nbsp;<?php echo number_format($prices["rock"], 0, ",", ".") ?><br/>
																		<?php echo htmlentities($lang["iron"]) ?>:&nbsp;<?php echo number_format($prices["iron"], 0, ",", ".") ?> <?php echo htmlentities($lang["paper"]) ?>:&nbsp;<?php echo number_format($prices["paper"], 0, ",", ".") ?> <?php echo htmlentities($lang["koku"]) ?>:&nbsp;<?php echo number_format($prices["koku"], 0, ",", ".") ?></strong>
																	</td>
<?php
	if ($build_check["ok"] and $build_check["class"] == 2) {
?>
																	<td width="100" class="box_6" rowspan="2">
																		<strong><span id="3"></span></strong>
<?php
	} else {
?>
																	<td width="100" class="box_6">
																		<input type="submit" name="construct" value="<?php echo htmlentities($lang["b_build"]) ?>"<?php if (($check == 0 or $camp["upgrade_lvl"] < 1) or $own_uid) {?> disabled="disabled"<?php } ?>/><br/>
																		<strong><?php echo htmlentities($lang["b_time"]) ?>:<br/><?php echo format_time(build_time(2, 3, $camp["lvl"], $con), "h:m:s") ?></strong>
<?php
	}
?>
																	</td>
																</tr>
																<tr>
																	<td width="420" height="50" class="box_7">
																		<strong><?php echo htmlentities($lang["b_upgrade"]) ?>:</strong><br />
<?php
	$check2 = rescheck($food, $wood, $rock, $iron, $paper, $koku, $prices_upgr["food"], $prices_upgr["wood"], $prices_upgr["rock"], $prices_upgr["iron"], $prices_upgr["paper"], $prices_upgr["koku"]);
?>
																		<strong><?php echo htmlentities($lang["food"]) ?>:&nbsp;<?php echo number_format($prices_upgr["food"], 0, ",", ".") ?> <?php echo htmlentities($lang["wood"]) ?>:&nbsp;<?php echo number_format($prices_upgr["wood"], 0, ",", ".") ?> <?php echo htmlentities($lang["rock"]) ?>:&nbsp;<?php echo number_format($prices_upgr["rock"], 0, ",", ".") ?><br/>
																		<?php echo htmlentities($lang["iron"]) ?>:&nbsp;<?php echo number_format($prices_upgr["iron"], 0, ",", ".") ?> <?php echo htmlentities($lang["paper"]) ?>:&nbsp;<?php echo number_format($prices_upgr["paper"], 0, ",", ".") ?> <?php echo htmlentities($lang["koku"]) ?>:&nbsp;<?php echo number_format($prices_upgr["koku"], 0, ",", ".") ?></strong>
																	</td>
<?php
	if (!$build_check["ok"] or $build_check["class"] != 2) {
?>
																	<td width="100" class="box_8">
																		<input type="submit" name="upgrade" value="<?php echo htmlentities($lang["b_build"]) ?>"<?php if (($check2 == 0 or $camp["upgrade_lvl"] >= 3 or $camp["lvl"]<$camp["upgrade_lvl"]*10) or $own_uid) {?> disabled="disabled"<?php }?>/><br/>
<?php
		if ($camp["upgrade_lvl"] < 3) {
?>
																		<strong><?php echo htmlentities($lang["b_time"]) ?>:<br/><?php echo format_time(build_time(2, "3.".$camp["upgrade_lvl"], $camp["lvl"], $con), "h:m:s") ?></strong>
<?php
		}
?>
																	</td>
<?php
	}
?>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												</form>
											</td>
										</tr>
<?php
		}
	}
?>
									</table>
<?php
	include("loggedin/footer.php");
?>