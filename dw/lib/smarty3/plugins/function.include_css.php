<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {include_js} function plugin
 *
 * Type:     function<br>
 * Name:     include_css<br>
 * Date:     September 16, 2011<br>
 * Purpose:  load a given js-file<br>
 *
 * Examples:<br>
 * <pre>
 * {include_css}
 * </pre>
 * @author Neithan
 * @version  1.0
 * @param array $params
 * @param Smarty_Internal_Template $template
 */
function smarty_function_include_css($params, $template)
{
	if ($_SESSION['css']['file'])
	{
		foreach ($_SESSION['css']['file'] as $file)
		{
			echo '<link rel="stylesheet" type="text/css" href="css/'.$file.'.css" />';
		}
	}

	if ($_SESSION['css']['script'])
	{
		foreach ($_SESSION['css']['script'] as $script)
		{
			echo '<style type="text/css">';
			echo $script;
			echo '</style>';
		}
	}
}