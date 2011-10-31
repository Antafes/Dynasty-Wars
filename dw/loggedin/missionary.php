<?php
	include("loggedin/header.php");

	lib_bl_general_loadLanguageFile('missionary');

	$religion = $_GET["religion"];
	$ruid = $_GET["uid"];
	if (!$ruid or !$religion) {
?>
											<meta http-equiv="refresh" content="0;URL=index.php?chose=home" />
<?php
	} else {
		if ($religion == "accept") {
			$erg1 = mysql_query("UPDATE dw_user SET religion=2 WHERE uid='$ruid'", $con);
			echo htmlentities($lang["acceptmsg"]);
			$erg2 = mysql_query("DELETE FROM dw_missionary WHERE uid='$ruid'", $con);
		} elseif ($religion == "decline") {
			echo htmlentities($lang["declinemsg"]);
			$erg2 = mysql_query("DELETE FROM dw_missionary WHERE uid='$ruid'", $con);
		}
	}
	include("loggedin/footer.php");
?>