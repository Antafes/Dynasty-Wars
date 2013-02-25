<?php
namespace util\html;
/**
 * creates a jQuery ready script
 * @author Neithan
 * @param string $script
 * @return string
 */
function createReadyScript($script)
{
	return '<script language="javascript" type="text/javascript">
		$(function(){
			'.$script.'
		});
	</script>';
}

/**
 * creates a link for hyperlinks or redirects
 * @author Neithan
 * @param array $valuelist array([chose, ][parameter, ][file])
 * @param boolean $for_redirect
 * @return string
 */
function createLink($valuelist = '', $for_redirect = false)
{
	$separator = '&amp;';
	if ($for_redirect)
		$separator = '&';

	if (!$valuelist['file'])
		$link = 'index.php';
	else
		$link = $valuelist['file'];

	if (is_array($valuelist['parameter']) || isset($valuelist['chose']))
	{
		$link .= '?chose='.$valuelist['chose'];
		if (is_array($valuelist['parameter']))
			foreach ($valuelist['parameter'] as $key => $value)
				$link .= $separator.$key.'='.$value;
	}
	return $link;
}

/**
 * load a js file in smarty
 * use only the filename without ending
 * @param String $file
 */
function load_js($file)
{
	if (!is_array($_SESSION['scripts']['file']))
		$_SESSION['scripts']['file'] = array();

	if (!in_array($file, $_SESSION['scripts']['file']))
		$_SESSION['scripts']['file'][] = $file;
}

/**
 * load a js script in smarty
 * @param String $script contains only the js!
 */
function load_js_script($script)
{
	$_SESSION['scripts']['script'][] = $script;
}

/**
 * load a js ready script in smarty
 * @param String $script contains only the js!
 */
function load_js_ready_script($script)
{
	$_SESSION['scripts']['ready_script'][] = $script;
}

/**
 * load a css file in smarty
 * use only the filename without ending
 * @param String $file
 */
function load_css($file)
{
	if (!is_array($_SESSION['css']['file']))
		$_SESSION['css']['file'] = array();

	if (!in_array($file, $_SESSION['css']['file']))
		$_SESSION['css']['file'][] = $file;
}