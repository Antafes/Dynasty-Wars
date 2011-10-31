<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {explode} function plugin
 *
 * Type:     function<br>
 * Name:     explode<br>
 * Date:     September 16, 2011<br>
 * Purpose:  explode a given value<br>
 * Input:
 *         - value = value to explode
 *         - delimiter = boundary string
 *         - assign = assigns to template var
 *
 * Examples:<br>
 * <pre>
 * {explode value="hey you" delimiter=" " assign="splitted_string"}
 * </pre>
 * @author Neithan
 * @version  1.0
 * @param array
 * @param Smarty
 */
function smarty_function_explode($params, $smarty)
{
	if (!in_array('value', array_keys($params)))
		$smarty->trigger_error('explode: missing "value" parameter');

	if (!in_array('delimiter', array_keys($params)))
		$smarty->trigger_error('explode: missing "delimiter" parameter');

	if (!in_array('assign', array_keys($params)))
		$smarty->trigger_error('explode: missing "assign" parameter');

	$exploded = explode($params['delimiter'], $params['value']);
	$smarty->assign($params['assign'], $exploded);
}