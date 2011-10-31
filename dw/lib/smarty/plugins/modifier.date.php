<?php
/**
* Smarty plugin
*
* @package Smarty
* @subpackage PluginsModifier
*/

/**
* Smarty date modifier plugin
*
* Type:     modifier<br>
* Name:     date<br>
* Purpose:  format datestamps via DateTime<br>
* Input:<br>
*          - string: input date string
*          - format: date format for output
*
* @link http://smarty.php.net/manual/en/language.modifier.date.format.php date_format (Smarty online manual)
* @author Monte Ohrt <monte at ohrt dot com>
* @param string $
* @param string $
* @param string $
* @return string |void
* @uses smarty_make_timestamp()
*/
function smarty_modifier_date($timestamp, $format = 'Y-m-d H:i:s')
{
	if (preg_match('/^\d$/', $timestamp))
		return date($format, $timestamp);
	else
	{
		$date = new DateTime($timestamp);
		return $date->format($format);
	}
}