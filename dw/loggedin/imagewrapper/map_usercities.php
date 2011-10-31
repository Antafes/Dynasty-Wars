<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */
if (!isset($_GET['user'])) die ("no user set");

if (!lib_dal_user_userExists($con, $_GET['user'])) die("no such user");

lib_bl_map_drawUserCities($con, lib_dal_user_nick2uid($con, $nick), "pictures/dynamic_map.png", 2)
?>
