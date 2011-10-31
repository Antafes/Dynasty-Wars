<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Calculates the percentage of $fragment in $total
 * @author siyb
 * @param <float> $total the total amount
 * @param <float> $fragment the fragment that should be represented as percentage
 * @return <float> percentage
 */
function lib_util_math_calcPercentage($total, $fragment) {
    return $fragment * 100 / $total;
}

/**
 * Calculates the fragment from $total, specified by the given percentage
 * @param <float> $total the total amount
 * @param <float> $percentage the percentage
 * @return <float> the fragment calculated from the percentage of total
 */
function lib_util_math_calcFragment($total, $percentage) {
    return $percentage / 100 * $total;
}

/**
 * formats a number according to the specifications in the language array
 * @author Neithan
 * @global array $lang
 * @param number $number
 * @param int $decimals
 * @return string
 */
function lib_util_math_numberFormat($number, $decimals = 2)
{
	global $lang;

	return number_format($number, $decimals, $lang['decimal'], $lang['thousands']);
}
?>
