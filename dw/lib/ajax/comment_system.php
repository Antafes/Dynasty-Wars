<?php
session_start();
include_once('../config.php');

$con = @mysql_connect($server, $seruser, $serpw);
if ($con)
{
	mysql_select_db($serdb, $con) or die('Fehler, keine Datenbank!');

	include_once('../bl/general.ajax.inc.php');
	include_once('../bl/login.php');
	include_once('../dal/tribunal.php');
	include_once('../bl/tribunal.php');

	$parser = new wikiparser();

	$item_list = json_decode($_GET['items']);
	$new_item_list = array();
	foreach ($item_list as $part)
		$new_item_list[$part->name] = utf8_decode($part->value);
	$item_list = $new_item_list;
	$lang['lang'] = lib_bl_general_getLanguage($_SESSION['user']->getUID());
	include('../../language/'.$lang['lang'].'/ingame/tribunal.php');
	include('../../language/'.$lang['lang'].'/general.php');

	$comments = lib_bl_tribunal_getComments($item_list['tid']);

	if (is_array($comments) and count($comments) > 0)
	{
		$html = '';
		foreach ($comments as $comment)
		{
			$html .= '
				<div class="comment">
					'.htmlentities(sprintf($lang['comment_from'], lib_bl_general_uid2nick($comment['writer']))).'
					<div class="comment_at">'.htmlentities(sprintf($lang['comment_at'], date($lang['acptimeformat'], $comment['date_added']))).'</div>
					<div class="comment_content">
						'.nl2br($parser->parseIt($comment['comment'])).'
					</div>';
			if ($comment['changed_count'] > 0)
			{
				if ($comment['changed_count'] == 1)
					$changed_count = $lang['one'];
				else
					$changed_count = $comment['changed_count'];
				$html .= '
					<div class="comment_changed">
						'.htmlentities(sprintf($lang['last_changed'], $changed_count, lib_bl_general_uid2nick($comment['last_changed_from']), date($lang['acptimeformat'], $comment['date_last_changed']))).'
					</div>';
			}
			if ($comment['writer'] == $_SESSION['user']->getUID() or lib_bl_general_getGameRank($_SESSION['user']->getUID()) > 0)
			{
				$html .= '<div class="comment_options">
					<a href="javascript:;" onclick="editComment(this, \'comment_dialog\', '.$comment['tcoid'].', \'lib/ajax/edit_comment.php\', \''.$lang['save'].'\', \''.$lang['cancel'].'\', 400)">'.htmlentities($lang['edit']).'</a>
					<a href="javascript:;" onclick="deleteComment(this, '.$comment['tcoid'].')">'.htmlentities($lang['delete']).'</a>
				</div>';
			}
			$html .= '
					<hr />
				</div>
			';
		}
	}
	$html .= '
		<div class="comment" style="text-align: center;">
			<a href="javascript:;" onclick="showEditingDialog(this, \'comment_dialog\', \'lib/ajax/new_comment.php\', \'' .$lang['save'].'\', \''.$lang['cancel'].'\', 400)">'.$lang['create_comment'].'</a>
			<div class="hidden" id="comment_dialog" title="'.$lang['create_comment'].'">
				<form method="post" action="index.php?chose=tribunal">
					<div>'.$lang['comment'].':</div>
					<div>
						<textarea name="comment_text" rows="10" cols="50"></textarea>
						<input type="hidden" name="ajax_id" value="'.$item_list['tid'].'" />
					</div>
				</form>
			</div>
		</div>
	';

	echo json_encode(array(
		'status' => 'ok',
		'html' => $html,
	));
}