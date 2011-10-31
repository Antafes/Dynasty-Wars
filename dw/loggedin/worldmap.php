<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */
include('lib/bl/map.inc.php');

if (!isset($_GET['p'])) { // display an empty map to the user
?>
    <img src="loggeding/imagewrapper/map_empty.php"/>
<?php
} else {
   if ($_POST['type'] == "clan") {// clanmap
?>
   <img src=<?php echo "loggeding/imagewrapper/map_clancities.php?clan=" . $_POST['uscl'] ?>/>
<?php
   } else {// usermap
?>
<img src=<?php echo "loggeding/imagewrapper/map_usercities.php?user=" . $_POST['uscl'] ?>/>
<?php
   }
}
?>
<form method="post" action="index.php?chose=worldmap&amp;p=1">
    User / Clan: <input type="text" name="uscl" />
    <select name="type">
                    <option value=""></option>
                    <option value="user">User</option>
                    <option value="clan">Clan</option>
    </select>
    <input type="submit" name="submit" />
</form>
