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
 * Name:     include_js<br>
 * Date:     September 16, 2011<br>
 * Purpose:  load a given js-file<br>
 *
 * Examples:<br>
 * <pre>
 * {include_js}
 * </pre>
 * @author Neithan
 * @version  1.0
 * @param array $params
 * @param Smarty_Internal_Template $template
 */
function smarty_function_include_js($params, $template)
{
	if ($_SESSION['scripts']['file'])
	{
		foreach ($_SESSION['scripts']['file'] as $file)
		{
			echo '<script language="javascript" type="text/javascript" src="lib/js/'.$file.'.js"></script>';
		}
	}

	if ($_SESSION['scripts']['script'] || $_SESSION['scripts']['ready_script'])
	{
		echo '<script language="javascript" type="text/javascript">';

		if ($_SESSION['scripts']['script'])
			foreach ($_SESSION['scripts']['script'] as $script)
				echo $script;

		if ($_SESSION['scripts']['ready_script'])
		{
			echo '$(function() {';

			foreach ($_SESSION['scripts']['ready_script'] as $script)
				echo $script;

			echo '});';
		}

		echo '</script>';
	}
}