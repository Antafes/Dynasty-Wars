#!/usr/bin/php5
<?php
include('lib/db.php');
include('lib/bl/map.php');
include('lib/dal/map.php');
include('lib/util/mysql.php');

/**
 * @todo: finish
 */
function image2map($filePath) {
        $im = imagecreatefromjpeg($filePath);
        if (!$im)
                die('could not read image: $filePath');

        $imageHeight = imagesy($im);
        $imageWidth = imagesx($im);

        for ($x = 0; $x < $imageWidth; $x++) {
                for ($y = 0; $y < $imageHeight; $y++) {
			if ($x > 0 && $x < 293 && $y > 0 && $y < 90) continue; // skip 1:1 to 293:90
                        dal\map\setTerrainType($x, $y, bl\map\getTerrainTypeByColor(imagecolorat($im,$x,$y)));
		}
	}
}

$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) || die('Fehler, keine Datenbank!');

?>
