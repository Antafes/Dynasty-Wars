<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {load_css} function plugin
 *
 * Type:     function<br>
 * Name:     load_css<br>
 * Date:     September 16, 2011<br>
 * Purpose:  load a given js-file<br>
 * Input:
 *         - file = file to load
 *         - script = script to load
 *
 * Examples:<br>
 * <pre>
 * {load_css file="jquery-1.5.1"}
 * {load_css script="$('#test').submit();"}
 * </pre>
 * @author Neithan
 * @version  1.0
 * @param array $params
 * @param Smarty_Internal_Template $template
 */
function smarty_function_load_css($params, Smarty_Internal_Template $template)
{
	if (!in_array('file', array_keys($params)) && !in_array('script', array_keys($params)))
		$template->smarty->trigger_error('load_js: missing "file" or "script" parameter');

	if (in_array('file', array_keys($params)) && in_array('script', array_keys($params)))
			$template->smarty->trigger_error('load_js: only "file" or "script" parameter allowed');

	if (!is_array($_SESSION['css']['file']))
		$_SESSION['css']['file'] = array();

	if (!is_array($_SESSION['css']['script']))
		$_SESSION['css']['script'] = array();

	if ($params['file'] && !in_array($params['file'], $_SESSION['css']['file']))
		$_SESSION['css']['file'][] = $params['file'];
	elseif ($params['script'])
		$_SESSION['css']['script'][] = $params['scipt'];
}