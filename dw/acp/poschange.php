<!-- popup für admins um die position und den namen der stadt zu aendern -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>Dynasty Wars</title>
<link href="../css/main.css" rel="stylesheet" type="text/css"/>
<script language="javascript" type="text/javascript">
function ClosePopUp() {
	if (!subwindow)
		return;
		if (subwindow.closed)
			return;
	subwindow.close();
}
</script>
</head>
<body background="../pictures/bg.jpg">
<?php
//db zugangsdaten
	include("../lib/db.php");
	include('../lib/bl/general.inc.php');
	include("../lib/log_functions.php");
//language
	$lang["lang"] = $_SESSION["language"];
	if (!$lang["lang"]) {
		$lang["lang"] = bl\general\getLanguage();
	}
	include("../language/".$lang["lang"]."/acp/poschange.php");
//abfrage GET, POST variablen
	$uid = $_SESSION["uid"];
	if (!$uid) {
		$uid = $_COOKIE["uid"];
	}
	$reguid = $_GET["reguid"];
	$change = $_POST["change"];
	$nx = $_POST["x"];
	$ny = $_POST["y"];
	$ncity = $_POST["city"];
	$oldx = $_GET["oldx"];
	$oldy = $_GET["oldy"];
//db: con zur datenbank
	$con = @mysql_connect($server, $seruser, $serpw);
	mysql_select_db($serdb, $con) || die($lang["nodb"]);
//db: auslesen von nick und insel
	$erg1 = mysql_query("SELECT nick FROM dw_user WHERE uid='$reguid'", $con);
	if ($erg1) {
		$nick = mysql_result($erg1, 0, 0);
	}
	if ($change) {
//nachricht über positionsaenderung
		$helpstring = mysql_query("SELECT city FROM dw_map WHERE uid='$reguid'", $con);
		if ($helpstring) {
			$regcity = mysql_result($helpstring, 0);
		}
		$poschange = bl\general\changePosition($reguid, $nx, $ny, $regcity, $con);
		if ($poschange == 1) {
			bl\general\changePosition(0, $reguid, $lang["poschangedtitle"], sprintf($lang["poschangedmsg"], $nick, $oldx, $oldy, $nx, $ny), 3);
			bl\log\saveLog(14, $uid, $reguid, "[$nx:$ny]");
		}
	}
	$erg2 = mysql_query("SELECT map_x, map_y, city FROM dw_map WHERE uid='$reguid'", $con);
	if ($erg2) {
		$map_x = mysql_result($erg2, 0, 0);
		$map_y = mysql_result($erg2, 0, 1);
		$city = mysql_result($erg2, 0 ,2);
	}
//db: auslesen der userspezifischen inseldaten
?>
<form method="post" action="poschange.php?uid=<?php echo $uid ?>&amp;reguid=<?php echo $reguid ?>&amp;oldx=<?php echo $map_x ?>&amp;oldy=<?php echo $map_y ?>">
<table width="440" border="1" class="no_content">
	<tr>
		<td width="440" colspan="4" class="table_tc"><?php echo htmlentities(sprintf($lang["changepos"], $nick)) ?></td>
	</tr>
	<tr>
		<td width="440" colspan="4" class="no_content">&nbsp;</td>
	</tr>
	<tr>
		<td width="50" class="no_content">&nbsp;</td>
		<td width="125" class="table_tc"><?php echo htmlentities($lang["currentpos"]) ?>:</td>
		<td width="155" class="table_tc">[<?php echo $map_x?>:<?php echo $map_y ?>] <?php echo $city ?></td>
		<td width="50" class="no_content">&nbsp;</td>
	</tr>
	<tr>
		<td width="50" class="no_content">&nbsp;</td>
		<td width="125" class="table_tc"><?php echo htmlentities($lang["newpos"]) ?>:</td>
		<td width="155" class="table_tc">
			[<input name="x" type="text" size="3" maxlength="3" value="<?php echo $map_x ?>"/>:<input type="text" name="y" size="3" maxlength="3" value="<?php echo $map_y ?>"/>]
		</td>
		<td width="50" class="no_content">&nbsp;</td>
	</tr>
	<tr>
		<td width="50" class="no_content">&nbsp;</td>
		<td width="125" class="table_tc"><?php echo htmlentities($lang["city"]) ?>:</td>
		<td width="155" class="table_tc">
			<input type="text" name="city" value="<?php echo htmlentities($city) ?>"/>
		</td>
		<td width="50" class="no_content">&nbsp;</td>
	</tr>
	<tr>
		<td width="50" class="no_content">&nbsp;</td>
		<td width="220" colspan="2" class="table_tc">
			<input type="submit" name="sub" value="<?php echo htmlentities($lang["change"]) ?>"/>
			<input type="hidden" name="change" value="1"/>
			<input type="hidden" name="oldpos" value="<?php echo $x?>:<?php echo $y ?>"/>
		</td>
		<td width="50" class="no_content">&nbsp;</td>
	</tr>
	<tr>
		<td width="50" class="no_content">&nbsp;</td>
		<td width="220" colspan="2" class="table_tc">
			<input type="button" name="close" value="<?php echo htmlentities($lang["close"]) ?>" onclick="javascript:window.close()"/>
		</td>
		<td width="50" class="no_content">&nbsp;</td>
	</tr>
<?php
	if ($poschange == 1) {
?>
	<tr>
		<td width="50" class="no_content">&nbsp;</td>
		<td width="220" colspan="2" class="table_tc"><?php echo htmlentities($lang["poschanged"]) ?></td>
		<td width="50" class="no_content">&nbsp;</td>
	</tr>
<?php
	} elseif ($poschange == 2) {
?>
	<tr>
		<td width="50" class="no_content">&nbsp;</td>
		<td width="220" colspan="2" class="table_tc"><?php echo htmlentities($lang["posblocked"]) ?></td>
		<td width="50" class="no_content">&nbsp;</td>
	</tr>
<?php
	} elseif ($poschange == 3) {
?>
	<tr>
		<td width="50" class="no_content">&nbsp;</td>
		<td width="220" colspan="2" class="table_tc"><?php echo htmlentities($lang["fieldnotuseable"]) ?></td>
		<td width="50" class="no_content">&nbsp;</td>
	</tr>
<?php
	} elseif ($poschange == 4) {
?>
	<tr>
		<td width="50" class="no_content">&nbsp;</td>
		<td width="220" colspan="2" class="table_tc"><?php echo htmlentities($lang["failed"]) ?></td>
		<td width="50" class="no_content">&nbsp;</td>
	</tr>
<?php
	}
?>
</table>
</form>
</body>
</html>