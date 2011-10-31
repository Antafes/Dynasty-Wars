<?php
 include("dw/lib/unit_move_functions.php");
 include("dw/lib/db.php");
 $con = @mysql_connect($server, $seruser, $serpw);
 mysql_select_db($serdb, $con) or die($lang["no_db"]);
 echo a_star("379:20", "377:20", $con);
?>