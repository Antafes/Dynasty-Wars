<?php
include('loggedin/header.php');
bl\general\loadLanguageFile('home');

$smarty->assign('messages', bl\general\getUnreadMessageCount($_SESSION['user']->getUID()));
$smarty->assign('missionary', bl\general\getMissionary($_SESSION['user']->getUID()));
$smarty->assign('welcome_message', htmlentities($lang['welcome']));
$smarty->assign('nick', $_SESSION['user']->getNick());
$smarty->assign('missionary_info', htmlentities($lang['missionary']));
$smarty->assign('missionary_accept_link', util\html\createLink(array(
	'chose' => 'missionary',
	'parameter' => array(
		'religion' => 'accept',
		'uid' => $_SESSION['user']->getUID(),
	),
)));
$smarty->assign('missionary_accept', htmlentities($lang['accept']));
$smarty->assign('missionary_decline_link', util\html\createLink(array(
	'chose' => 'missionary',
	'parameter' => array(
		'religion' => 'decline',
		'uid' => $_SESSION['user']->getUID(),
	),
)));
$smarty->assign('missionary_decline', htmlentities($lang['decline']));
$messages = '';
if ($new_msg)
{
	$messages = htmlentities($lang['youhave']);
	if (bl\gameOptions\getMenuEntry('messages'))
		$messages .= ' <a href="'.util\html\createLink(array('chose' => 'messages')).'">';
	if ($new_msg == 1)
		$messages .= htmlentities($lang['newmsg']);
	elseif ($new_msg > 1)
	{
		$messages .= $new_msg;
		$messages .= htmlentities($lang['newmsgs']);
	}
	if (bl\gameOptions\getMenuEntry('messages'))
		$messages .= '</a>';
	$smarty->assign('messages_info', $messages);
}
include ('loggedin/footer.php');
$smarty->display($smarty->template_dir[0].'home.tpl');