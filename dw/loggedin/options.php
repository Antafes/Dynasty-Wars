<?php
	include("loggedin/header.php");
	include('lib/bl/options.inc.php');
	include_once('lib/bl/register.inc.php');

	lib_bl_general_loadLanguageFile('options');

//abfrage GET, POST variablen
	$changepw = $_POST["changepw"];
	$oldpw = md5($_POST["oldpw"]);
	$newpw = md5($_POST["newpw"]);
	$newpww = md5($_POST["newpww"]);
	$textchange = $_GET["textchange"];
	$description = $_POST["description"];
	$changeemail = $_POST["changeemail"];
	$newemail = $_POST["email"];
	$leave = $_GET["leave"];
	$del = $_GET["del"];
	if ($del) {
		$delcheck = $_POST["delcheck"];
		if ($delcheck) {
			$helperg = mysql_query('SELECT nick FROM dw_user WHERE uid="'.$_SESSION['user']->getUid().'"', $con);
			if ($helperg) {
				$nick = mysql_result($helperg, 0);
			}
			$header = "From: Dynasty Wars <support@dynastywars.wafriv.de>";
			mail ($email, $lang["deltitle"], sprintf($lang["delmsg"], $nick, $_SESSION['user']->getUID()), $header);
		}
	}
//db: passwortänderung
	if ($changepw == 1)
	{
		$password = lib_bl_options_getOldPassword($_SESSION['user']->getUID());
		if (($password == $oldpw) and ($newpw == $newpww))
		{
			$ergchange = lib_bl_options_changePassword($newpw, $_SESSION['user']->getUID());
			$id = lib_bl_login_createId($_SESSION['user']->getUID());
			if ($_SESSION['lid'])
				$_SESSION['lid'] = $id;
			else
				setcookie("lid", $id, time()+604800, "", ".dynasty-wars.de");
			if ($ergchange)
			{
				$change = 1;
				$err["pwchange"] = 1;
			}
		}
	}
//description
	if ($textchange) {
		$ergchange = mysql_query("UPDATE dw_user SET description='".mysql_real_escape_string($description)."', language='$language' WHERE uid='$_SESSION['user']->getUid()'", $con);
		if ($ergchange) {
			$change = 2;
			$err["descr"] = 1;
			$_SESSION["lang"] = $language;
		}
	}
	if ($changeemail == 1)
	{
		$check_mail = lib_bl_register_checkMail($newemail);
		if (!$check_mail) {
			$err["emailformat"] = 1;
			unset($newemail);
		}
//email adresse ändern
		if ($newemail) {
			$ergchange = mysql_query("UPDATE dw_user SET email='".mysql_real_escape_string($newemail)."' WHERE uid='$_SESSION['user']->getUid()'", $con);
			if ($ergchange and $newerg) {
				$change = 3;
				$err["email"] = 1;
			}
		} else {
			$change = 3;
			$err["noemail"] = 1;
		}
	}
	if ($leave == 2) {
//clan verlassen
		$checkerg = mysql_query("SELECT users FROM dw_clan WHERE cid='$_SESSION['user']->getCID()'", $con);
		$checkerg2 = mysql_query("SELECT dw_user.rankid, nick, dw_clan_rank.admin FROM dw_user LEFT OUTER JOIN dw_clan_rank ON dw_user.rankid=dw_clan_rank.rankid WHERE uid='$_SESSION['user']->getUid()'", $con);
		$checkerg3 = mysql_query("SELECT unit_points, building_points FROM dw_points WHERE uid='$_SESSION['user']->getUid()'", $con);
		if ($checkerg and $checkerg2 and $checkerg3) {
			$users = mysql_result($checkerg, 0, 0);
			$unit_points = mysql_result($checkerg3, 0, 0);
			$building_points = mysql_result($checkerg, 0, 1);
			if (mysql_result($checkerg2, 0, 2) == 1) {
				$err["cadmin"] = 1;
			}
			if ($users > 1) {
				$newusers = $users - 1;
				$updateerg = mysql_query("UPDATE dw_user SET cid='0', rankid='0' WHERE uid='$_SESSION['user']->getUid()'", $con);
				if ($updateerg) {
					$err["clanleave"] = 1;
				}
			} else {
				$delerg = mysql_query("DELETE FROM dw_clan WHERE cid='$_SESSION['user']->getCID()'", $con);
				$delerg2 = mysql_query("DELETE FROM dw_clan_rank WHERE cid='$_SESSION['user']->getCID()'", $con);
				$usererg = mysql_query("UPDATE dw_user SET cid='0', rankid='0' WHERE uid='$_SESSION['user']->getUid()'", $con);
				if ($delerg and $delerg2 and $usererg) {
					$err["clanleave"] = 1;
				}
			}
		}
	}
//db: abfrage der userdaten
	$starterg = mysql_query("SELECT cid, rankid FROM dw_user WHERE uid='$_SESSION['user']->getUid()'", $con);
	if ($starterg) {
		$start = mysql_fetch_array($starterg);
		$_SESSION['user']->getCID() = $start["cid"];
		$rankid = $start["rankid"];
	}
	$ergebnis = mysql_query ("SELECT password, email, description, language FROM dw_user WHERE uid='$_SESSION['user']->getUid()'", $con);
	if ($ergebnis) {
		$_SESSION['user'] = mysql_fetch_array($ergebnis);
		$password = $_SESSION['user']["password"];
		$email = $_SESSION['user']["email"];
		$description = $_SESSION['user']["description"];
		$language = $_SESSION['user']["language"];
	}
?>
									<form name="form1" method="post" action="index.php?chose=options">
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" class="table_cm" colspan="4">
												<font size="5"><?php echo htmlentities($lang["options"]) ?></font>
											</th>
										</tr>
										<tr>
											<td width="660" class="no_content" colspan="4">&nbsp;</td>
										</tr>
<?php
	if ($err["pwchange"] or $err["email"] or $err["noemail"] or $err["descr"] or $err["clanleave"] or $err["emailformat"]) {
?>
										<tr>
											<td width="660" class="table_tc" colspan="4">
<?php
	if ($err["pwchange"]) {
		echo htmlentities($lang["pwchanged"]);
	} elseif ($err["email"]) {
		echo htmlentities($lang["emailchanged"]);
	} elseif ($err["noemail"]) {
		echo htmlentities($lang["noemail"]);
	} elseif ($err["descr"]) {
		echo htmlentities($lang["descrchanged"]);
	} elseif ($err["clanleave"]) {
		echo htmlentities($lang["clanleft"]);
	} elseif ($err["emailformat"]) {
		echo htmlentities($lang["emailformat"]);
	}
?>
											</td>
										</tr>
<?php
	}
?>
										<tr>
											<td width="160" rowspan="5" class="no_content">&nbsp;</td>
											<th width="350" colspan="2" class="table_tc"><?php echo htmlentities($lang["changepw"]) ?></th>
											<td width="160" rowspan="5" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td width="140" class="table_tc">
												<?php echo htmlentities($lang["oldpw"]) ?>:
											</td>
											<td width="210" class="table_tl">
												<input type="password" name="oldpw" />
											</td>
										</tr>
										<tr>
											<td width="140" class="table_tc">
												<?php echo htmlentities($lang["newpw"]) ?>:
											</td>
											<td width="210" class="table_tl">
												<input name="newpw" type="password" id="newpw" />
											</td>
										</tr>
										<tr>
											<td width="140" class="table_tc">
												<?php echo htmlentities($lang["reppw"]) ?>:
											</td>
											<td width="210" class="table_tl">
												<input name="newpww" type="password" id="newpww" />
											</td>
										</tr>
										<tr>
											<td width="350" colspan="2" class="table_tc">
												<input name="change" type="submit" value="<?php echo htmlentities($lang["change"]) ?>"<?php if ($own_uid) { ?> disabled="disabled"<?php } ?> />
												<input type="hidden" name="changepw" value="1" />
											</td>
										</tr>
									</table>
									</form><br/>
									<form method="post" action="index.php?chose=options">
									<table width="660" border="1" class="no_content">
										<tr>
											<td width="160" class="no_content">&nbsp;</td>
											<th width="350" colspan="2" class="table_tc"><?php echo htmlentities($lang["changeemail"]) ?></th>
											<td width="160" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td class="no_content" width="160">&nbsp;</td>
											<td class="table_tc" width="140"><?php echo htmlentities($lang["email"]) ?>:</td>
											<td class="table_tl" width="210">
												<input type="text" name="email" value="<?php echo $email ?>" />
											</td>
											<td class="no_content" width="160">&nbsp;</td>
										</tr>
										<tr>
											<td width="660" class="table_tc" colspan="4">
												<input type="submit" name="emailsub" value="<?php echo htmlentities($lang["change"])?>"<?php if ($own_uid) { ?> disabled="disabled"<?php } ?> />
												<input type="hidden" name="changeemail" value="1" />
											</td>
										</tr>
									</table>
									</form>
									<form method="post" action="index.php?chose=options&amp;textchange=1">
									<table width="660" class="no_content" border="1">
										<tr>
											<th width="660" class="table_tc">
												<?php echo htmlentities($lang["description"]) ?>
											</th>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<textarea name="description" cols="50" rows="10"><?php echo $description ?></textarea>
											</td>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<select name="language">
													<option value="de"<?php if ($language == "de") { ?> selected="selected"<?php } ?>>Deutsch</option>
													<option value="en"<?php if ($language == "en") { ?> selected="selected"<?php } ?>>English</option>
												</select>
											</td>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<input type="submit" name="subtext" value="<?php echo htmlentities($lang["save"]) ?>"<?php if ($own_uid) { ?> disabled="disabled"<?php } ?> />
											</td>
										</tr>
									</table>
									</form>
<?php
	if ($_SESSION['user']->getCID() and !$own_uid) {
?>
									<table width="660" border="1" class="no_content">
										<tr>
											<th width="660" class="table_tc">Clan</th>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<a href="index.php?chose=options&amp;leave=1"><?php echo htmlentities($lang["clanleave"]) ?></a>
											</td>
										</tr>
<?php
		if ($leave == 1) {
?>
										<tr>
											<td width="660" class="table_tc"><?php echo htmlentities($lang["reallyleave"]) ?></td>
										</tr>
										<tr>
											<td width="660" class="table_tc">
												<a href="index.php?chose=options&amp;leave=2"><?php echo htmlentities($lang["yes"]) ?></a>&nbsp;/&nbsp;<a href="index.php?chose=options"><?php echo htmlentities($lang["no"]) ?></a>
											</td>
										</tr>
<?php
		}
?>
									</table>
<?php
	}
?>
									<form method="post" action="index.php?chose=options&amp;del=1">
									<table width="660" border="1" class="no_content">
										<tr>
											<td width="160" class="no_content">&nbsp;</td>
											<th width="350" colspan="2" class="table_tc"><?php echo htmlentities($lang["delacc"]) ?></th>
											<td width="160" class="no_content">&nbsp;</td>
										</tr>
										<tr>
											<td class="no_content" width="160">&nbsp;</td>
											<td class="table_tc" width="350" colspan="2">
												<input type="checkbox" name="delcheck" /> <input type="submit" name="delsub" value="<?php echo htmlentities($lang["delete"]) ?>"<?php if ($own_uid) { ?> disabled="disabled"<?php } ?> />
											</td>
											<td class="no_content" width="160">&nbsp;</td>
										</tr>
<?php
	if ($delcheck) {
?>
										<tr>
											<td width="660" class="table_tc" colspan="4">
												<?php nl2br(htmlentities($lang["acdelmsg"])) ?>
											</td>
										</tr>
<?php
	}
?>
									</table>
									</form>
<?php
	include("loggedin/footer.php");
?>