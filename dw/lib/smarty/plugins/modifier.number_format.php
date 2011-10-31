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
 * Name:     number_format<br>
 * Purpose:  format numbers with number_format
 *
 * @author Neithan
 * @param number $number input number
 * @param int $decimals number of decimals
 * @param string $decimal decimal point
 * @param string $thousands thousands separator
 * @return string formatted string
 */
function smarty_modifier_number_format($number, $decimals = 2, $decimal = '.', $thousands = ',')
{
	return number_format($number, $decimals, $decimal, $thousands);
}