<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty string_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     rawurlencode<br>
 * Purpose:  encode a string with rawurlencode
 *
 * @author Neithan
 * @param number $string input number
 * @return string formatted string
 */
function smarty_modifier_rawurlencode($string)
{
	return rawurlencode($string);
}