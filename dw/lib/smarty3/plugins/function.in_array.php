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
 * Name:     in_array<br>
 * Date:     November 9th, 2011<br>
 * Purpose:  check if the given value is in the array<br>
 * Input:
 *		   - value = the value to search for
 *         - var = the array to check
 *		   - assign = assigns to template var
 *
 * Examples:<br>
 * <pre>
 * {is_array var=$variable value="blub" assign="check_result"}
 * </pre>
 * @author Neithan
 * @version  1.0
 * @param array $params
 * @param Smarty_Internal_Template $template
 */
function smarty_function_in_array($params, Smarty_Internal_Template $template)
{
	if (!in_array('var', array_keys($params)))
		$template->smarty->trigger_error('explode: missing "var" parameter');

	if (!in_array('value', array_keys($params)))
		$template->smarty->trigger_error('explode: missing "value" parameter');

	if (!in_array('assign', array_keys($params)))
		$template->smarty->trigger_error('explode: missing "assign" parameter');

	if (!$params['var'])
		$template->assign($params['assign'], false);
	else
		$template->assign($params['assign'], in_array($params['value'], $params['var']));
}