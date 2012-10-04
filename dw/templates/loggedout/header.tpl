<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="Marian Pollzien" />
<meta http-equiv="cache-control" content="no-cache" />
<meta name="copyright" content="&copy; 2005 - {$copy_year} by Neithan" />
<title>{$title}</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/x-icon" href="pictures/favicon.ico" />
<script language="javascript" type="text/javascript" src="lib/js/jquery-1.4.1.min.js"></script>
<script language="javascript" type="text/javascript" src="lib/js/several.js"></script>
</head>
<body>
	<div class="wrapper">
	<div class="top_border"></div>
	<div class="left_border left">&nbsp;</div>
	<div class="background">
		<div class="header">
			<img src="{$logo_image}" alt="{$title}"/>
		</div>
		<div class="menu margin_top_16">
			<div class="menu2">
				<a href="index.php?chose=home" class="a2">{$menu_home}</a><br/>
				<a href="index.php?chose=news" class="a2">{$menu_news}</a><br/>
				{if $menu_board_link}<a href="{$menu_board_link}" target="_blank" class="a2">{/if}{$menu_board}{if $menu_board_link}</a>{/if}<br />
			</div>
			<div class="menu2">
				<a href="index.php?chose=login" class="a2">{$menu_login}</a><br/>
				<a href="index.php?chose=registration" class="a2">{$menu_register}</a><br/>
				<a href="index.php?chose=imprint" class="a2">{$menu_imprint}</a><br/>
			</div>
		</div>
		<div class="content margin_top_16">