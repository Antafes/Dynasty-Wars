<?php
function lib_bl_ajax_buildArray($valuelist, $is_recursion = false)
{
	$new_valuelist = array();

	if (!$is_recursion)
		$valuelist = json_decode($valuelist);

	foreach ($valuelist as $key => $part)
	{
		if ($part->name)
		{
			if (is_array($part->value) || is_object($part->value))
			{
				$value = lib_bl_ajax_buildArray($part->value, true);
				$new_valuelist[$part->name] = $value;
			}
			else
				$new_valuelist[$part->name] = utf8_decode($part->value);
		}
		else
		{
			if (is_array($part) || is_object($part))
			{
				$value = lib_bl_ajax_buildArray($part, true);
				$new_valuelist[$key] = $value;
			}
			else
				$new_valuelist[$key] = utf8_decode($part);
		}
	}

	return $new_valuelist;
}

function lib_bl_ajax_prepareOutput($valuelist, $is_recursion = false)
{
	$new_valuelist = array();
	foreach ($valuelist as $key => $part)
	{
		if (is_array($part) || is_object($part))
			$value = lib_bl_ajax_prepareOutput($part, true);
		else
			$value = utf8_encode($part);

		$new_valuelist[$key] = $value;
	}

	if ($is_recursion)
		return $new_valuelist;
	else
		return json_encode ($new_valuelist);
}