<?php
/**
* Smarty plugin
*
* @package Smarty
* @subpackage PluginsModifier
*/

/**
 * sprintf modifier plugin
 *
 * Type:     modifier<br>
 * Name:     sprintf<br>
 * Purpose:  format strings via sprintf
 *
 * @author Neithan
 * @param string $format format string
 * @return string formatted string
 */
function smarty_modifier_sprintf()
{
	$args = func_get_args();
	$format = $args[0];
	unset($args[0]);
	$params = $args;
	return vsprintf($format, $params);
}