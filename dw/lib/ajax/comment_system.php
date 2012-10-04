<?php
session_start();
require_once(__DIR__.'/../config.php');

header('Content-Type: application/json');

require_once(__DIR__.'/../bl/general.inc.php');
require_once(__DIR__.'/../bl/login.php');
require_once(__DIR__.'/../dal/tribunal.php');
require_once(__DIR__.'/../bl/tribunal.php');

$parser = new bl\wikiParser\WikiParser();

$_SESSION['user'] = new bl\user\UserCls();
$_SESSION['user']->loadByUID($_SESSION['user']->getUIDFromId($_SESSION['lid']));

$lang['lang'] = $_SESSION['user']->getLanguage();
bl\general\loadLanguageFile('general', '', true);
bl\general\loadLanguageFile('tribunal', 'loggedin', true);

$comments = bl\tribunal\getComments($_GET['tid']);
$hearing = bl\tribunal\getHearing($_GET['tid']);

if (is_array($comments) and count($comments) > 0)
{
	$html = '';
	foreach ($comments as $comment)
	{
		$html .= '
			<div class="comment">
				'.sprintf($lang['comment_from'], bl\general\uid2nick($comment['writer'])).'
				<div class="comment_at">'.sprintf($lang['comment_at'], $comment['create_datetime']->format($lang['acptimeformat'])).'</div>
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
					'.sprintf($lang['last_changed'], $changed_count, bl\general\uid2nick($comment['last_changed_from'], $comment['changed_datetime']->format($lang['acptimeformat']))).'
				</div>';
		}
		if ($comment['writer'] == $_SESSION['user']->getUID() || bl\general\getGameRank($_SESSION['user']->getUID()) > 0)
		{
			$html .= '<div class="comment_options">
				<a href="javascript:;" onclick="editComment(this, \'comment_dialog\', '.$comment['tcoid'].', \'lib/ajax/edit_comment.php\', \''.$lang['save'].'\', \''.$lang['cancel'].'\', 400)">'.$lang['edit'].'</a>
				<a href="javascript:;" onclick="deleteComment(this, '.$comment['tcoid'].')">'.$lang['delete'].'</a>
			</div>';
		}
		$html .= '
				<hr />
			</div>
		';
	}
}

if (!$hearing['block_comments'])
{
	$html .= '
		<div class="comment" style="text-align: center;">
			<a href="javascript:;" onclick="showEditingDialog(this, \'comment_dialog\', \'lib/ajax/new_comment.php\', \'' .$lang['save'].'\', \''.$lang['cancel'].'\', 400)">'.$lang['create_comment'].'</a>
			<div class="hidden" id="comment_dialog" title="'.$lang['create_comment'].'">
				<form method="post" action="index.php?chose=tribunal">
					<div>'.$lang['comment'].':</div>
					<div>
						<textarea name="comment_text" rows="10" cols="50"></textarea>
						<input type="hidden" name="ajax_id" value="'.$_GET['tid'].'" />
					</div>
				</form>
			</div>
		</div>
	';
}

echo json_encode(array(
	'status' => 'ok',
	'html' => $html,
));