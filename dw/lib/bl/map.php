<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */

/**
 * Returns the color of the specified terrain type
 * @author siyb
 * @param <image> $im the image of the map
 * @param <int> $type the terrain type
 */
function lib_bl_map_getColorByTerrainType($im, $type) {

	// butt ugly, define in a namespace or something
	$mColor['forest'] = imagecolorallocate($im, 0x33, 0x33, 0x00);
	$mColor['water'] = imagecolorallocate($im, 0x00, 0x99, 0xCC);
	$mColor['grassland'] = imagecolorallocate($im, 0x00, 0x99, 0x00);
	$mColor['mountain'] = imagecolorallocate($im, 0x66, 0x66, 0x66);
	//$mColor['herebedragons'] = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
	$mColor['herebedragons'] = $mColor['water'];
	$mColor['city'] = imagecolorallocate($im, 0xFF, 0x00, 0x00);
	$mColor['kaiser'] = imagecolorallocate($im, 0xFF, 0xFF, 0x00);

	switch ($type) {
		case 0: // here be dragons
			return $mColor['herebedragons'];
		case 1: // water
			return $mColor['water'];
		case 2: // grass
			return $mColor['grassland'];
		case 3: // forest
			return $mColor['forest'];
		case 4: // mountain
			return $mColor['mountain'];
		case 5: // palace of the kaiser
			return $mColor['kaiser'];
		case 6: // city
			return $mColor['city'];
		default: // this should never happen
			die('seriouse fuckup man oO');
	}
}

/**
 * Returns the terrain typ of the specified color
 * @author siyb
 * @param <image> $im the image of the map
 * @param <color> $color the color of the terrain
 */
function lib_bl_map_getTerrainTypeByColor($im, $color) {

	// butt ugly, define in a namespace or something
	$mColor['forest'] = imagecolorallocate($im, 0x33, 0x33, 0x00);
	$mColor['water'] = imagecolorallocate($im, 0x00, 0x99, 0xCC);
	$mColor['grassland'] = imagecolorallocate($im, 0x00, 0x99, 0x00);
	$mColor['mountain'] = imagecolorallocate($im, 0x66, 0x66, 0x66);
	//$mColor['herebedragons'] = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
	$mColor['herebedragons'] = $mColor['water'];
	$mColor['city'] = imagecolorallocate($im, 0xFF, 0x00, 0x00);
	$mColor['kaiser'] = imagecolorallocate($im, 0xFF, 0xFF, 0x00);

	switch ($color) {
		case $mColor['herebedragons']: // here be dragons
			return 0;
		case $mColor['water']: // water
			return 1;
		case $mColor['grassland']: // grass
			return 2;
		case $mColor['forest']: // forest
			return 3;
		case $mColor['mountain']: // mountain
			return 4;
		case $mColor['kaiser']: // palace of the kaiser
			return 5;
		case $mColor['city']: // city
			return 6;
		default: // this should never happen
			die('seriouse fuckup man oO');
	}
}

/**
 * Draws the map into a png file in $mapFile.
 * @author siyb
 * @param <string> $mapFile the path to the mapfile
 * @param <int> $sizeFactor map scaling factor, currently only 1 and 2 are supported
 * @todo add variable scaling factor
 */
function lib_bl_map_drawMapImage($mapFile, $sizeFactor = 1) {
	if ($sizeFactor != 2 &&  $sizeFactor != 1) die ('sizeFactor must be 1 or 2');

	// map data
	$mapData = lib_bl_map_formatMapArray(dal\map\getSortedMapData());

	// our image
	$im = @imagecreate(400*$sizeFactor, 250*$sizeFactor)
	or die('Cannot Initialize new GD image stream.');

	// mapping magic happens here :>
	for ($iterX = 1; $iterX <= 400; $iterX++) { // loop x coord
		for ($iterY = 1; $iterY <= 250; $iterY++) { // loop y coord

			// check if there is no data for this field and set terrain to 0 if so
			if (!array_key_exists($iterY, $mapData[$iterX])) $mapData[$iterX][$iterY] = 0;

			// set color according to terrain type
			$color = lib_bl_map_getColorByTerrainType($im, $mapData[$iterX][$iterY]);

			// draw pixels
			lib_bl_map_drawSomethingOnMap($im, $iterX, $iterY, $color, $sizeFactor);
		}
	}
	// draw image
	imagepng($im, $mapFile);
	imagedestroy($im);
}

/**
 * Formats the mysql output array of the map into a 2dim array $foo[x][y] = $terrain;
 * @author siyb
 * @return <mysqlresultset> mapdata as returned by lib_dal_map_getSortedMapData()
 * @return <array> 2dim array containing the mapdata
 */
function lib_bl_map_formatMapArray($mapData) {
    foreach ($mapData as $row)
    {
        // if there is a city on the field, create a virtual terrain type
        if ($row['city'] != "" && $row['city'] != "-") $row['terrain'] = 6;

        // set terraintype for $retArray[x][y]
        $retArray[$row['map_x']][$row['map_y']] = $row['terrain'];
    }
    return $retArray;
}

/**
 * Draws a 2*2 pixel field on the map starting at x and y
 * @author siyb
 * @param <image> $im the image of the map
 * @param <int> $x x coord to draw
 * @param <int> $y y coord to draw
 * @param <type> $color
 */
function lib_bl_map_drawSomethingOnMap($im, $x, $y, $color, $sizeFactor) {
    $x *= $sizeFactor; $y*= $sizeFactor;
    imagesetpixel($im, $x, $y, $color);
    imagesetpixel($im, $x+1, $y, $color);
    imagesetpixel($im, $x+1, $y+1, $color);
    imagesetpixel($im, $x, $y+1, $color);
}

/**
 * Creates a PNG file and sends it to the browser
 * @param <string> $mapFile the path to the mapfile
 * @param <type> $sizeFactor the sizefactor of the map
 * @param <type> $data mapdata to be drawn, must be assoc array with elements
 * map_y and map_x in order to draw!!!
 */
function lib_bl_map_createPNGMap($mapFile, $sizeFactor, $data = -1) {
    header('Content-type: image/png');
    $im = imagecreatefrompng($mapFile);
    $cityColor = imagecolorallocate($im, 0xFF, 0x00, 0x00);

    if ($data != -1) // draw data if there is any
        foreach ($data as $row)
        {
            lib_bl_map_drawSomethingOnMap($im, $row['map_x'], $row['map_y'],
                $cityColor, $sizeFactor);
        }

    imagepng($im);
    imagedestroy($im);
}

/**
 * Draws all cities of the user with $uid on the map (creates a png)
 * @author siyb
 * @param <int> $uid userid
 * @param <string> $mapFile the path to the mapfile
 * @param <int> $sizeFactor the sizefactor of the map
 */
function lib_bl_map_drawUserCities($uid, $mapFile, $sizeFactor) {
    lib_bl_map_createPNGMap($mapFile, $sizeFactor,
        dal\user\returnAllCities($uid));
}

/**
 * Draws all cities of the clan with $cid on the map (creates a png)
 * @author siyb
 * @param <int> $cid clanid
 * @param <string> $mapFile the path to the mapfile
 * @param <int> $sizeFactor the sizefactor of the map
 */
function lib_bl_map_drawClanCities($cid, $mapFile, $sizeFactor) {
    lib_bl_map_createPNGMap($mapFile, $sizeFactor,
        dal\clan\returnAllCities($cid));
}

/**
 * Draws all cities on the map (creates a png)
 * @author siyb
 * @param <string> $mapFile
 * @param <int> $sizeFactor
 */
function lib_bl_map_drawAllCities($mapFile, $sizeFactor) {
    lib_bl_map_createPNGMap($mapFile, $sizeFactor,
        dal\map\returnAllCities());
}

/**
 * returns the name of the terrain
 * @author Neithan
 * @param int $type
 * @return string
 */
function lib_bl_mapTerrain($type)
{
	switch($type)
	{
		case 1:
			return 'water';
			break;
		case 2:
			return 'grass';
			break;
		case 3:
			return 'trees';
			break;
		case 4:
			return 'mountain_single';
			break;
	}
}

?>
