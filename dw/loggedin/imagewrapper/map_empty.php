<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */
//include('../../lib/dal/user.php');
//include('../../lib/dal/clan.php');
//include('../../lib/dal/map.php');
//include('../../lib/bl/map.php');
//include('lib/bl/mapincludes.php');
include('lib/dal/user.php');
include('lib/dal/clan.php');
include('lib/dal/map.php');
include('lib/bl/map.php');
lib_bl_map_createPNGMap($con, "pictures/dynamic_map.png", 2); // display the map to the user
?>