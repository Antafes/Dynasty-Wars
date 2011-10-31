<?php
include('lib/bl/tribunal.inc.php');
include('loggedin/header.php');

lib_bl_general_loadLanguageFile('tribunal');

$parser = new wikiparser;
?>
									<div class="heading">
										<?php echo htmlentities($lang["tribunal"]) ?>
									</div>
									<div class="submenu">
										<a href="index.php?chose=tribunal&amp;sub=hearings"><?php echo htmlentities($lang['hearings']); ?></a> |
										<a href="index.php?chose=tribunal&amp;sub=newhearing"><?php echo htmlentities($lang['new_hearing']); ?></a> |
										<a href="index.php?chose=tribunal&amp;sub=rules"><?php echo htmlentities($lang['rules']); ?></a>
									</div>
<?php
if ($_GET['sub'] == 'hearings' or !$_GET['sub'])
{
	if (!$_GET['id'])
	{
		$hearings = lib_bl_tribunal_getAllHearings();
?>
									<div class="inner_content">
										<div class="subheading">
											<?php echo htmlentities($lang['hearings']); ?>
										</div>
<?php
		if (count($hearings) > 0)
		{
			$n = 0;
			$count = count($hearings) - 1;
			foreach($hearings as $hearing)
			{
				if ($n == 0)
				{
?>
										<div class="row">
<?php
				}
				$cause = lib_bl_tribunal_getCause($hearing['cause']);
?>
											<div class="hearing_column"<?php if ($n > 0) { ?> style="margin-left: 15px;"<?php } ?>>
												<div class="row">
													<div class="both">
														<a href="index.php?chose=tribunal&amp;sub=hearings&amp;id=<?php echo $hearing['tid']; ?>"><?php echo sprintf($lang['hearing_title'], $cause['cause']); ?></a>
													</div>
												</div>
												<div class="row">
													<div class="left">
														<?php echo htmlentities($lang['suitor_text'].':'); ?>
													</div>
													<div class="right">
														<?php echo htmlentities(lib_dal_user_uid2nick($hearing['suitor'])); ?>
													</div>
												</div>
												<div class="row">
													<div class="left">
														<?php echo htmlentities($lang['accused_text'].':'); ?>
													</div>
													<div class="right">
														<?php echo htmlentities(lib_dal_user_uid2nick($hearing['accused'])); ?>
													</div>
												</div>
												<div class="row" style="margin-top: 10px; height: auto;">
													<div class="both" style="text-align: left;">
														<?php echo $parser->parseIt(lib_bl_general_cutOffText($hearing['description'], 100)); ?>
													</div>
												</div>
											</div>
<?php
				if ($n >= 2 or $n >= $count)
				{
					$n = 0;
?>
										</div>
<?php
				}
				else
					$n++;
			}
		}
		else
		{
			echo htmlentities($lang['no_hearings']);
		}
?>
									</div>
<?php
	}
	elseif ($_GET['id'])
	{
		if ($_GET['action'])
		{
			if ($_GET['action'] == 'recall')
			{
				lib_bl_tribunal_recallHearing($_GET['id'], $_SESSION['user']->getUID());
				if ($_SESSION['user']->getGameRank() >= 1)
				{
					$h = lib_bl_tribunal_getHearing($_GET['id']);
					lib_bl_log_saveLog(25, $_SESSION['user']->getUID(), $h['suitor'], $_GET['id']);
				}
				lib_bl_general_redirect('index.php?chose=tribunal&sub=hearings');
			}
		}
		$hearing = lib_bl_tribunal_getHearing($_GET['id']);
		$cause = lib_bl_tribunal_getCause($hearing['cause']);
?>
									<div class="inner_content">
										<div class="hearing">
											<div class="subheading">
												<?php echo htmlentities($lang['hearing']); ?>
											</div>
											<div class="row">
												<div class="left">
													<?php echo htmlentities($lang['suitor_text'].':'); ?>
												</div>
												<div class="middle">
													<?php echo htmlentities(lib_bl_general_uid2nick($hearing['suitor'])); ?>
												</div>
												<div class="right">
<?php
		if ((($hearing['suitor'] == $_SESSION['user']->getUID() and !$hearing['judge']) or $_SESSION['user']->getGameRank() >= 1) and !$own_uid and !$hearing['decision'])
		{
?>
													<a href="index.php?chose=tribunal&amp;sub=hearings&amp;id=<?php echo $hearing['tid'] ?>&amp;action=recall"><?php echo htmlentities($lang['recall'])?></a>
<?php
		}
?>
												</div>
											</div>
											<div class="row">
												<div class="left">
													<?php echo htmlentities($lang['accused_text'].':'); ?>
												</div>
												<div class="middle">
													<?php echo htmlentities(lib_bl_general_uid2nick($hearing['accused'])); ?>
												</div>
												<div class="right">
<?php
		if (($hearing['judge'] == $_SESSION['user']->getUID() or $_SESSION['user']->getGameRank() >= 1) and !$own_uid and !$hearing['decision'])
		{
?>
													<a href="javascript:;" onclick="showDecisionDialog(this, 'make_decission', 'lib/ajax/make_decision.php', '<?php echo htmlentities($lang['save']); ?>', '<?php echo htmlentities($lang['cancel']); ?>', 500)"><?php echo htmlentities($lang['decide'])?></a>
													<div id="make_decission" class="hidden" title="<?php echo htmlentities($lang['decide']); ?>">
														<form method="post" action="index.php?chose=tribunal" name="decide">
															<div class="row">
																<div class="left">
																	<?php echo htmlentities($lang['decision']); ?>:
																</div>
																<div class="right">
																	<select name="decision">
																		<option value="nocent"><?php echo htmlentities($lang['nocent']); ?></option>
																		<option value="innocent"><?php echo htmlentities($lang['innocent']); ?></option>
																		<option value="rejected"><?php echo htmlentities($lang['rejected']); ?></option>
																		<option value="other"><?php echo htmlentities($lang['other']); ?></option>
																	</select>
																</div>
															</div>
															<div class="row">
																<div class="left">
																	<?php echo htmlentities($lang['reason']); ?>:
																</div>
																<div class="right">
																	<textarea name="reason" rows="10" cols="50"></textarea>
																</div>
															</div>
															<input type="hidden" name="ajax_id" value="<?php echo $_GET['id']; ?>" />
														</form>
													</div>
<?php
		}
?>
												</div>
											</div>
											<div class="row">
												<div class="left">
													<?php echo htmlentities($lang['cause_text'].':'); ?>
												</div>
												<div class="middle">
													<?php echo htmlentities($cause['cause']); ?>
												</div>
												<div class="right">
<?php
		if ($_SESSION['user']->getGameRank() >= 1 and !$own_uid and !$hearing['decision'])
		{
?>
													<a href="javascript:;" onclick="blockComments(this, 'lib/ajax/block_comments.php', <?php if ($hearing['block_comments'] == 1) echo 0; else echo 1; ?>, <?php echo $_GET['id']; ?>)"><?php echo htmlentities($lang[($hearing['block_comments'] == 1 ? 'unblock_comments' : 'block_comments')]); ?></a>
<?php
		}
?>
												</div>
											</div>
											<div class="row">
												<div class="left">
													<?php echo htmlentities($lang['description'].':'); ?>
												</div>
												<div class="right" style="width: 500px;">
													<?php echo nl2br($parser->parseIt($hearing['description'])); ?>
												</div>
											</div>
<?php
		if ($hearing['decision'])
		{
?>
											<div class="row">
												<div class="left">
													<?php echo htmlentities($lang['decision'].':'); ?>
												</div>
												<div class="right" style="width: 500px;">
													<?php echo htmlentities($lang[$hearing['decision']]); ?>
												</div>
											</div>
											<div class="row">
												<div class="left">
													<?php echo htmlentities($lang['reason'].':'); ?>
												</div>
												<div class="right" style="width: 500px;">
													<?php echo nl2br($parser->parseIt($hearing['reason'])); ?>
												</div>
											</div>
<?php
		}
		if (count($hearing['arguments']) > 0 or $_SESSION['user']->getGameRank() > 1)
		{
?>
											<div class="row">
												<div class="left" style="font-weight: bold; text-align: center; width: 290px;">
													<?php echo htmlentities($lang['arguments_text']); ?>
												</div>
												<div class="right" style="font-weight: bold; text-align: center;">
													<?php echo htmlentities($lang['comments_text']); ?>
												</div>
											</div>
											<div class="row">
												<div class="left" style="text-align: left; width: 300px;">
<?php
			foreach ($hearing['arguments'] as $argument)
			{
				$message = lib_bl_general_getMessage($argument['msgid']);
				if ($_SESSION['user']->getGameRank() > 1 and !$own_uid)
				{
					if ($argument['approved'] == 1)
						$approved = ' ['.$lang['approved'].']';
					elseif ($argument['approved'] == -1)
						$approved = ' ['.$lang['not_approved'].']';
					else
						$approved = ' ['.$lang['no_approve'].']';
				}
?>
													<div class="argument">
														<?php echo htmlentities($lang['argument'].' #'.$argument['aid']); ?><span id="argument_approved<?php echo $argument['aid']; ?>"><?php echo htmlentities($approved); ?></span><br />
														<a href="javascript:;" onclick="showDialog('argument_<?php echo $argument['aid']; ?>', 500)"><?php echo htmlentities($message['title']); ?></a><br />
														<div id="argument_<?php echo $argument['aid']; ?>" class="argument_text" title="<?php echo htmlentities($message['title']); ?>">
															<?php echo nl2br($message['message']); ?>
														</div>
														<?php echo htmlentities(sprintf($lang['added_by'], date($lang['acptimeformat'], $argument['date_added']), lib_dal_user_uid2nick($argument['from']))); ?>
														<?php if ($_SESSION['user']->getGameRank() > 1 and !$own_uid and !$hearing['decision']) { ?><br />
														<span id="approval_links<?php echo $argument['aid']; ?>">
															<?php if (($argument['approved'] == -1 or !$argument['approved']) and !$own_uid) { ?><a href="javascript:;" onclick="argumentApproval('lib/ajax/argument_approval.php', 'accept', <?php echo $argument['aid']; ?>);"><?php } echo htmlentities($lang['accept']); if ($argument['approved'] == -1 or !$argument['approved']) { ?></a><?php } ?>
															<?php if (($argument['approved'] == 1 or !$argument['approved']) and !$own_uid) { ?><a href="javascript:;" onclick="argumentApproval('lib/ajax/argument_approval.php', 'decline', <?php echo $argument['aid']; ?>);"><?php } echo htmlentities($lang['decline']); if ($argument['approved'] == 1 or !$argument['approved']) { ?></a><?php } ?>
														</span>
														<?php } ?>
													</div>
<?php
			}
			if (!$hearing['decision'] and !$own_uid)
			{
				$messages = lib_bl_tribunal_getAllMessages($_SESSION['user']->getUID());
?>
													<div class="argument" style="text-align: center;" id="new_argument">
														<?php if ($hearing['suitor'] == $_SESSION['user']->getUID() or $hearing['accused'] == $_SESSION['user']->getUID() or $_SESSION['user']->getGameRank() >= 1) { ?><a href="javascript:;" onclick="showEditingDialog(this, 'new_argument_dialog', 'lib/ajax/add_argument.php', '<?php echo htmlentities($lang['save']); ?>', '<?php echo htmlentities($lang['cancel']); ?>')"><?php echo htmlentities($lang['add_argument'])?></a><?php } ?>
														<div id="new_argument_dialog" class="hidden" title="<?php echo htmlentities($lang['add_argument']); ?>">
															<form method="post" action="index.php?chose=tribunal" name="new_argument_form">
																<div>
																	<?php echo htmlentities($lang['argument']); ?>:
																	<select name="msgid">
																		<option value="0">&nbsp;</option>
<?php
				foreach ($messages as $message)
				{
?>
																		<option value="<?php echo $message['msgid']; ?>"<?php if(is_array($_POST['arguments']) and in_array($message['msgid'], $_POST['arguments'])) { ?> selected="selected"<?php } ?>><?php echo htmlentities($message['title']); ?></option>
<?php
				}
?>
																	</select>
																</div>
<?php
				if ($_SESSION['user']->getGameRank() >= 1)
				{
?>
																<div>
																	<?php echo htmlentities($lang['add_msgid']); ?>:
																	<input type="text" name="msgid_manual" />
																</div>
<?php
				}
?>
																<input type="hidden" name="ajax_id" value="<?php echo $_GET['id']; ?>" />
															</form>
														</div>
													</div>
<?php
			}
?>
												</div>
												<?php echo lib_util_html_createReadyScript('showCommentList("comments", '.$_GET['id'].')'); ?>
												<div class="right" style="width: 300px;" id="comments"></div>
											</div>
<?php
		}
?>
										</div>
									</div>
<?php
	}
}
elseif ($_GET['sub'] == 'newhearing')
{
	if ($_POST['nh_sub'])
		$errors = lib_bl_tribunal_insertHearing($_SESSION['user']->getUID(), $_POST['accused'], $_POST['causes'], $_POST['cause_description'], $_POST['arguments']);
	if ($errors == false and $_POST['nh_sub'])
		lib_bl_general_redirect('index.php?chose=tribunal&sub=newhearing&successful=1');
	$causes = lib_bl_tribunal_getAllCauses($lang['lang']);
	$messages = lib_bl_tribunal_getAllMessages($_SESSION['user']->getUID());
?>
									<div class="inner_content" id="new_hearing">
										<div class="subheading">
											<?php echo htmlentities($lang['new_hearing']); ?>
										</div>
<?php
	if ($_GET['successful'])
	{
?>
										<div class="info">
											<?php echo htmlentities($lang['created']); ?>
										</div>
<?php
	}
	elseif (is_array($errors))
	{
?>
										<div class="info">
<?php
		foreach ($errors as $key => $error)
		{
?>
											<?php echo htmlentities($lang[$key]); ?><br />
<?php
		}
?>
										</div>
<?php
	}
?>
										<form method="post" action="index.php?chose=tribunal&amp;sub=newhearing" name="form_new_hearing">
											<div class="row">
												<div class="nh_column left">
													<?php echo htmlentities($lang['accused_text']); ?>:
												</div>
												<div class="nh_column right">
													<input type="text" name="accused" value="<?php echo $_POST['accused']; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="nh_column left">
													<?php echo htmlentities($lang['cause_text']); ?>:
												</div>
												<div class="nh_column right">
													<select name="causes">
														<option value="0">&nbsp;</option>
<?php
	foreach ($causes as $cause)
	{
?>
														<option value="<?php echo $cause['tcid']; ?>"<?php if($cause['tcid'] == $_POST['causes']) {?> selected="selected"<?php } ?>><?php echo htmlentities($cause['cause']); ?></option>
<?php
	}
?>
													</select>
													<textarea name="cause_description" cols="30" rows="6" style="margin-top: 2px;"><?php echo $_POST['cause_description']; ?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="nh_column left">
													<?php echo htmlentities($lang['arguments_text']); ?>:
												</div>
												<div class="nh_column right">
													<select multiple="multiple" size="6" name="arguments[]" class="multi_select">
<?php
	foreach ($messages as $message)
	{
?>
														<option value="<?php echo $message['msgid']; ?>"<?php if(is_array($_POST['arguments']) and in_array($message['msgid'], $_POST['arguments'])) { ?> selected="selected"<?php } ?>><?php echo htmlentities($message['title']); ?></option>
<?php
	}
?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="nh_column both">
													<input type="submit" name="nh_sub" value="<?php echo htmlentities($lang['new_hearing']); ?>" />
												</div>
											</div>
										</form>
									</div>
<?php
}
elseif($_GET['sub'] == 'rules')
{
?>
									<div class="inner_content" id="rules">
										<div class="subheading">
											<?php echo htmlentities($lang['rules']); ?>
										</div>
<?php
	if (($_SESSION['user']->getGameRank() == 2 or $_SESSION['user']->getGameRank() == 3) and !$own_uid)
	{
?>
										<div class="sub_menu">
											<a href="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=show<?php if ($_GET['languages']) { ?>&amp;languages=<?php echo $_GET['languages']; } ?>"><?php echo htmlentities($lang['show_rules']); ?></a> |
											<a href="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change<?php if ($_GET['languages']) { ?>&amp;languages=<?php echo $_GET['languages']; } ?>"><?php echo htmlentities($lang['change_rules']); ?></a>
											<br />
											<form method="get" action="index.php?chose=tribunal&amp;sub=rules" name="change_lang">
												<input type="hidden" name="chose" value="<?php echo $_GET['chose']; ?>" />
												<input type="hidden" name="sub" value="<?php echo $_GET['sub']; ?>" />
												<input type="hidden" name="rules_sub" value="<?php echo $_GET['rules_sub']; ?>" />
												<?php echo htmlentities($lang['languages'].':'); ?>
												<select name="languages" onchange="form.submit();">
													<option value="de"<?php if ($lang['lang'] == 'de' or $_GET['languages'] == 'de') { ?> selected="selected"<?php } ?>><?php echo htmlentities($lang['german']); ?></option>
													<option value="en"<?php if ($lang['lang'] == 'en' or $_GET['languages'] == 'en') { ?> selected="selected"<?php } ?>><?php echo htmlentities($lang['english']); ?></option>
												</select>
											</form>
										</div>
<?php
	}
	if (!$_GET['rules_sub'] or $_GET['rules_sub'] == 'show')
	{
		$rules = lib_bl_tribunal_getAllRules($_GET['languages']);
		$page = $_GET['page'];
		if (!$page)
			$page = 1;
		$pagelinks = '';
		if (count($rules) > 5)
		{
			$pages = ceil(count($rules) / 5);
			$pagelinks = lib_bl_general_createPageLinks($_GET['chose'], $pages, 'sub=rules&rules_sub=show&languages='.$_GET['languages']);
		}
		for ($i = ($page - 1) * 5 + 1; $i <= $page * 5; $i++)
		{
			if (!isset($rules[$i]['title']))
				break;
?>
										<div class="rules">
											<span><?php echo htmlentities('§'.$i.' '.$rules[$i]['title']); ?></span><br />
											<ol>
<?php
			foreach ($rules[$i]['texts'] as $text)
			{
?>
												<li>
													<?php echo nl2br(htmlentities($text['text'])); ?>
<?php
				if (is_array($text['subclauses']))
				{
?>
													<ol>
<?php
					foreach ($text['subclauses'] as $subclause)
					{
?>
														<li><?php echo nl2br(htmlentities($subclause['text'])); ?></li>
<?php
					}
?>
													</ol>
<?php
				}
?>
												</li>
<?php
			}
?>
											</ol>
										</div>
<?php
		}
?>
										<div class="pagelinks">
											<?php echo $pagelinks; ?>
										</div>
<?php
	}
	elseif ($_GET['rules_sub'] == 'change')
	{
		if ($_GET['delete'])
		{
			switch ($_GET['delete'])
			{
				case 'rule':
					lib_bl_tribunal_deleteRule($_GET['id']);
					break;
				case 'clause':
					lib_bl_tribunal_deleteClause($_GET['id']);
					break;
			}
		}
		if ($_POST['new_rule_sub'])
		{
			$error = array(
				'paragraph' => 0,
				'title' => 0,
				'clause1' => 0,
			);
			if (!$_POST['paragraph'])
				$error['paragraph'] = 1;
			if (!$_POST['title'])
				$error['title'] = 1;
			if (!$_POST['description'][0])
				$error['clause1'] = 1;
			if ($error['paragraph'] != 1 and $error['title'] != 1 and $error['clause1'] != 1)
				lib_bl_tribunal_insertRule(array(
					'paragraph' => $_POST['paragraph'],
					'title' => $_POST['title'],
					'clauses' => $_POST['description'],
					'language' => ($_GET['languages'] ? $_GET['languages'] : $lang['lang']),
				));
		}
?>
										<div class="rules" style="margin-top: 5px;">
											<a href="javascript:;" onclick="slideToggleView('new_rule')"><?php echo htmlentities($lang['new_rule']);?></a>
											<div id="new_rule" style="display: none;">
												<form method="post" action="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change<?php if ($_GET['languages']) { ?>&amp;languages=<?php echo $_GET['languages']; } ?>" name="form_new_rule">
													<?php echo htmlentities($lang['paragraph'].':'); ?> <input type="text" name="paragraph" /><br />
													<?php echo htmlentities($lang['title'].':'); ?> <input type="text" name="title" /><br />
													<div id="clause_0" style="margin-top: 5px;">
														<span style="font-weight: normal;"><?php echo htmlentities($lang['clause'].' 1:'); ?></span><br />
														<textarea name="description[0][text]" rows="5" cols="74"></textarea>
													</div>
													<a href="javascript:;" onclick="cloneClause(this, '<?php echo htmlentities($lang['clause']); ?>')"><?php echo htmlentities($lang['new_clause']); ?></a> <a href="javascript:;" onclick="cloneSubClause(this, '<?php echo htmlentities($lang['subclause']); ?>')"><?php echo htmlentities($lang['new_subclause']); ?></a><br />
													<input type="submit" name="new_rule_sub" value="<?php echo htmlentities($lang['new_rule']); ?>" />
												</form>
											</div>
										</div>
<?php
		$rules = lib_bl_tribunal_getAllRules($_GET['languages']);
		$page = $_GET['page'];
		if (!$page)
			$page = 1;
		$pagelinks = '';
		if (count($rules) > 10)
		{
			$pages = ceil(count($rules) / 10);
			$pagelinks = lib_bl_general_createPageLinks($_GET['chose'], $pages, 'sub=rules&rules_sub=show&languages='.$_GET['languages']);
		}
		for ($i = 1; $i < $page * 10; $i++)
		{
			if (!isset($rules[$i]['title']))
				break;
?>
										<div class="rules" style="margin-top: 5px;">
											<span><a href="javascript:;" onclick="slideToggleView('paragraph<?php echo $i; ?>')"><?php echo htmlentities('§'.$i.' '.$rules[$i]['title']); ?></a></span>
											<a href="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change&amp;delete=rule&amp;id=<?php echo $rules[$i]['ruid']; ?>">X</a>
											<div id="paragraph<?php echo $i; ?>" style="display: none;">
												<form method="post" action="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change<?php if ($_GET['languages']) { ?>&amp;languages=<?php echo $_GET['languages']; } ?>&amp;page=<?php echo $_GET['page']; ?>" name="change_rule<?php echo $i; ?>">
													<input style="margin-top: 5px;" type="text" name="title" value="<?php echo $rules[$i]['title']; ?>" /><br />
<?php
			$texts_count = count($rules[$i]['texts']);
			$c_keys = array_keys($rules[$i]['texts']);
			for ($n = 0; $n < $texts_count; $n++)
			{
?>
													<div id="clause_<?php echo $i; ?>_<?php echo $n; ?>" style="margin-top: 5px;">
														<span style="font-weight: normal;"><?php echo htmlentities($lang['clause'].' '.($n + 1).':'); ?></span>
														<a href="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change&amp;delete=clause&amp;id=<?php echo $rules[$i]['texts'][$c_keys[$n]]['rutid']; ?>">X</a><br />
														<textarea name="description[]" rows="5" cols="74"><?php echo $rules[$i]['texts'][$c_keys[$n]]['text']; ?></textarea><br />
													</div>
<?php
				if (is_array($rules[$i]['texts'][$c_keys[$n]]['subclauses']))
				{
					$subclauses_count = count($rules[$i]['texts'][$c_keys[$n]]['subclauses']);
					$sc_keys = array_keys($rules[$i]['texts'][$c_keys[$n]]['subclauses']);
					for ($m = 0; $m < $subclauses_count; $m++)
					{
?>
													<div id="subclause_<?php echo $i; ?>_<?php echo $n; ?>_<?php echo $m; ?>" style="margin-top: 5px;">
														<span style="font-weight: normal;"><?php echo htmlentities($lang['subclause'].' '.($m + 1).':'); ?></span>
														<a href="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change&amp;delete=clause&amp;id=<?php echo $rules[$i]['texts'][$c_keys[$n]]['subclauses'][$sc_keys[$m]]['rutid']; ?>">X</a><br />
														<textarea name="description[]" rows="5" cols="74"><?php echo $rules[$i]['texts'][$c_keys[$n]]['subclauses'][$sc_keys[$m]]['text']; ?></textarea><br />
													</div>
<?php
					}
				}
			}
?>
													<a href="javascript:;" onclick="cloneClause(this, '<?php echo htmlentities($lang['clause']); ?>')"><?php echo htmlentities($lang['new_clause']); ?></a> <a href="javascript:;" onclick="cloneSubClause(this, '<?php echo htmlentities($lang['subclause']); ?>')"><?php echo htmlentities($lang['new_subclause']); ?></a><br />
													<input style="margin-top: 5px;" type="submit" name="change_rule" value="<?php echo htmlentities($lang['change_rule']);?>" />
												</form>
											</div>
										</div>
<?php
		}
		if ($pagelinks)
		{
?>
										<div class="pagelinks">
											<?php echo $pagelinks; ?>
										</div>
<?php
		}
	}
?>
									</div>
<?php
}
include('loggedin/footer.php');
?>