<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */
if (!isset($_GET['user'])) die ("no user set");

if (!bl\user\exists($_GET['user'])) die("no such user");

bl\map\drawUserCities(dal\user\nick2uid($nick), "pictures/dynamic_map.png", 2)
?>
