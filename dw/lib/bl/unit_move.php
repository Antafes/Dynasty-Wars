<?php
namespace bl\unit\move;
/**
 * a-star algorithm
 * @author Neithan
 * @param int $start_x
 * @param int $start_y
 * @param int $target_x
 * @param int $target_y
 * @return int
 */
function aStar($start_x, $start_y, $target_x, $target_y)
{
	//creating the openlist
	$openlist = new \bl\unit\OpenList;
	//creating the closedlist
	$closedlist = new \bl\unit\ClosedList;
	/*
	* setting the g values for each terrain type
	* the g value is the "weight" of the field
	*/
	$movement = array(1 => 50, 2 => 5, 3 => 10, 4 => 15, 5 => 50);
	$d = min($movement);
	//add the start to the closedlist
	$closedlist->x[0] = $start_x;
	$closedlist->y[0] = $start_y;
	$finish = 0;
	$m = 1;
	//loop until the target is reached
	while ($finish < 1)
	{
		unset($add);
		$lc_count = $closedlist->getLength();
		//search the surrounding fields
   		$add = giveSurrounding($closedlist->x[$lc_count-1], $closedlist->y[$lc_count-1]);
		//remove the fields from $add that are in the closedlist
   		$add = checkClosedList($add, $closedlist);
   		if ($m > 1)
			//if this is the second loop, check whether the coordinates are allready in the openlist
    		$openlist = checkOpenList($add, $openlist, $closedlist->x[$lc_count-1], $closedlist->y[$lc_count-1], $openlist->getLength());
   		$count = count($add);
		$o_count = $openlist->getLength();
   		$n = 0;
		//add all coordinates that are left in $add to the openlist
   		foreach ($add as $part)
   		{
			$c = $o_count+$n;
		    $openlist->x[$c] = $part["map_x"];
		    $openlist->y[$c] = $part["map_y"];
		    $openlist->g[$c] = $movement[$part["t"]];
			/*
			* calculating the h value for this coordinate
			* the h value is the approximated cost to get from A to B
			*/
			$openlist->h[$c] = $d*max(abs($openlist->x[$c]-$target_x), abs($openlist->y[$c]-$target_y));
   			$openlist->f[$c] = $openlist->g[$c]+$openlist->h[$c];
			if ($closedlist->getLength() < 2)
			{
			    $openlist->px[$c] = $start_x;
			    $openlist->py[$c] = $start_y;
			} else
			{
				$openlist->px[$c] = $closedlist->x[$closedlist->getLength()-1];
				$openlist->py[$c] = $closedlist->y[$closedlist->getLength()-1];
			}
    		$n++;
   		}
		/*
		* search for the smallest f value
		* f = g + h
		*/
   		$sf = searchF($openlist, $closedlist->x[$lc_count-1], $closedlist->y[$lc_count-1]);
		//adding the coordinates with the smallest f value to the closedlist
		$closedlist->x[$m] = $openlist->x[$sf];
		$closedlist->y[$m] = $openlist->y[$sf];
		$closedlist->g[$m] = $openlist->g[$sf];
		$closedlist->h[$m] = $openlist->h[$sf];
		$closedlist->f[$m] = $openlist->f[$sf];
		$closedlist->px[$m] = $openlist->px[$sf];
		$closedlist->py[$m] = $openlist->py[$sf];
		$m++;
		//remove the coordinates with the smallest f value from the openlist
   		$openlist = removeFromList($openlist, $sf);
   		$c_count = $closedlist->getLength();
		//check whether the target is reached
   		if ($target_x == $closedlist->x[$c_count-1] && $target_y == $closedlist->y[$c_count-1])
   		{
    		$finish = 1;
			//sum up the g values of all coordinates in the closedlist
    		$g_move = calculateG($closedlist);
   		}
  	}
	//calculate the time (in s) needed to get from A to B
  	return calcTime($g_move);
}

/**
 * searches for the surrounding fields on the map
 * @author Neithan
 * @param int $x
 * @param int $y
 * @return array
 */
function giveSurrounding($x, $y)
{
  	$lx = $x-1;
  	$hx = $x+1;
  	$ly = $y-1;
  	$hy = $y+1;
	//getting the sql-query with the surrounding fields
  	$surrounding = \dal\troops\surrounding(intval($x-1), intval($x+1), intval($y-1), intval($y+1), intval($x), intval($y));
  	if (count($surrounding) > 0)
  	{
		foreach ($surrounding as &$part)
		{
			$part['t'] = $part['terrain']; //COMPAT: reassign because of the prior script
			unset($part['terrain']);
		}
  	}
  	return $surrounding;
}

/**
 * searches the smallest f
 * @author Neithan
 * @param object $openlist
 * @param int $x
 * @param int $y
 * @return int
 */
function searchF($openlist, $x, $y)
{
	if (is_array($openlist->x))
	{
	  	$count = $openlist->getlength();
  		$keys = $openlist->getkeys();
		$small_f = $keys[0];
	  	for ($n = 0; $n < $count; $n++)
	  	{
			if (($x-1 <= $openlist->x[$keys[$n]] && $x+1 >= $openlist->y[$keys[$n]]) && ($y-1 <= $openlist->y[$keys[$n]] && $y+1 >= $openlist->y[$keys[$n]]))
				if ($openlist->f[$keys[$small_f]] > $openlist->f[$keys[$n]])
		    		$small_f = $keys[$n];
	  	}
	  	return $small_f;
  	}
}

/**
 * remove the coordinate from the openlist
 * @author Neithan
 * @param object $openlist
 * @param string $rkey
 * @return object
 */
function removeFromList($openlist, $rkey)
{
  	$count = $openlist->getlength();
  	$o_keys = $openlist->getkeys();
	for ($n = 0, $m = 0; $n < $count; $n++)
	{
   		if ($o_keys[$n] != $rkey)
   		{
    		$x[$m] = $openlist->x[$o_keys[$n]];
    		$y[$m] = $openlist->y[$o_keys[$n]];
    		$g[$m] = $openlist->g[$o_keys[$n]];
    		$h[$m] = $openlist->h[$o_keys[$n]];
    		$f[$m] = $openlist->f[$o_keys[$n]];
    		$px[$m] = $openlist->px[$o_keys[$n]];
    		$py[$m] = $openlist->py[$o_keys[$n]];
			$m++;
   		}
  	}
	$openlist->clear();
	$count = count($x);
	for ($n = 0; $n < $count; $n++)
	{
   		$openlist->x[$n] = $x[$n];
   		$openlist->y[$n] = $y[$n];
   		$openlist->g[$n] = $g[$n];
   		$openlist->h[$n] = $h[$n];
   		$openlist->f[$n] = $f[$n];
   		$openlist->px[$n] = $px[$n];
   		$openlist->py[$n] = $py[$n];
  	}
  	return $openlist;
}

/**
 * remove the coordinates from the addlist that are in the closedlist
 * @author Neithan
 * @param array $add
 * @param object $closedlist
 * @return array
 */
function checkClosedList($add, $closedlist)
{
  	$c_count = $closedlist->getlength();
  	$keys = $closedlist->getkeys();
  	foreach ($add as &$part)
  	{
   		for ($m = 0; $m < $c_count; $m++)
   		{
    		if (($part['x'] == $closedlist->x[$keys[$m]]) || ($part['y'] == $closedlist->y[$keys[$m]]))
				if (($part['x'] == $return[$r_count-1]["x"]) || ($part['y'] == $return[$r_count-1]["y"]))
					unset($part);
    		else
     			$m = $m+$c_count;
   		}
  	}
  	sort($add);
  	return $add;
}

/**
 * checks if there are coordinates in the openlist that are in the addlist
 * @author Neithan
 * @param array $add
 * @param object $openlist
 * @param int $cx
 * @param int $cy
 * @param int $o_count
 * @return object
 */
function checkOpenList($add, $openlist, $cx, $cy, $o_count)
{
	if ($o_count)
	  	$o_keys = $openlist->getkeys();
  	foreach ($add as $part)
  	{
   		$r_count = count($return);
   		for ($m = 0; $m < $o_count; $m++)
   		{
			if (($part["x"] == $openlist->x[$o_keys[$m]]) && ($part["y"] == $openlist->y[$o_keys[$m]]))
			{
				$openlist->px[$o_keys[$m]] = $cx;
     			$openlist->py[$o_keys[$m]] = $cy;
    		}
   		}
  	}
  	return $openlist;
}

/**
 * calculate the needed g value
 * @author Neithan
 * @param object $closedlist
 * @return int
 */
function calculateG($closedlist)
{
  	$count = $closedlist->getlength()-1;
  	$keys = $closedlist->getkeys();
  	for (;$count >= 0; $count--)
   		$gcalc = $gcalc+$closedlist->g[$keys[$count]];
  	return $gcalc;
}

/**
 * calculate the needed time to get from A to B
 * @author Neithan
 * @param int $g
 * @return int
 */
function calcTime($g)
{
	return $g*150;
}