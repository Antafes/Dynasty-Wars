<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */
if (!isset($_GET['user'])) die ("no user set");

if (!lib_bl_user_exists($_GET['user'])) die("no such user");

lib_bl_map_drawUserCities(dal\user\nick2uid($nick), "pictures/dynamic_map.png", 2)
?>
