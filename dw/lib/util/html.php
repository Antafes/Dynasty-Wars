<?php
/**
 * creates a jQuery ready script
 * @author Neithan
 * @param string $script
 * @return string
 */
function lib_util_html_createReadyScript($script)
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
 * @param array $valuelist
 * @param boolean $for_readirect
 * @return string
 */
function lib_util_html_createLink($valuelist = '', $for_readirect = false)
{
	$separator = '&amp;';
	if ($for_readirect)
		$separator = '&';

	if (!$valuelist['file'])
		$link = 'index.php';
	else
		$link = $valuelist['file'];

	if (is_array($valuelist['parameter']) or isset($valuelist['chose']))
	{
		$link .= '?chose='.$valuelist['chose'];
		if (is_array($valuelist['parameter']))
			foreach ($valuelist['parameter'] as $key => $value)
				$link .= $separator.$key.'='.$value;
	}
	return $link;
}