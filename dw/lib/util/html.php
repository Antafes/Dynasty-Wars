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