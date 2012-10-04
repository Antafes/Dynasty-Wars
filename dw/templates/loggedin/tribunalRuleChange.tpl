<div class="rules" style="margin-top: 5px;">
	<a href="javascript:;" onclick="slideToggleView('new_rule')">{$lang.new_rule}</a>
	<div id="new_rule" style="display: none;">
		<form method="post" action="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change{if $smarty.get.languages}&amp;languages={$smarty.get.languages}{/if}" name="form_new_rule">
			{$lang.paragraph}: <input type="text" name="paragraph" /><br />
			{$lang.title}: <input type="text" name="title" /><br />
			<div id="clause_0" style="margin-top: 5px;">
				<span style="font-weight: normal;">{$lang.clause} 1:</span><br />
				<textarea name="description[0][text]" rows="5" cols="74"></textarea>
			</div>
			<a href="javascript:;" onclick="cloneClause(this, '{$lang.clause}')">{$lang.new_clause}</a>
			<a href="javascript:;" onclick="cloneSubClause(this, '{$lang.subclause}')">{$lang.new_subclause}</a><br />
			<input type="submit" name="new_rule_sub" value="{$lang.new_rule}" />
		</form>
	</div>
</div>
{section name='rules' loop=$rules start=(($page - 1) * 10 + 1) max=10}
	{assign var='i' value=$smarty.section.rules.index}
	<div class="rules" style="margin-top: 5px;">
		<span><a href="javascript:;" onclick="slideToggleView('paragraph{$i}')">&sect;{$i} {$rules[$i].title}</a></span>
		<a href="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change&amp;delete=rule&amp;id={$rules[$i].ruid}">X</a>
		<div id="paragraph{$i}" style="display: none;">
			<form method="post" action="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change{if $smarty.get.languages}&amp;languages={$smarty.get.languages}{/if}&amp;page={$page}" name="change_rule{$i}">
				<input style="margin-top: 5px;" type="text" name="title" value="{$rules[$i].title}" /><br />
				{foreach from=$rules[$i].texts item='text' name='texts'}
					{assign var='n' value=$smarty.foreach.texts.iteration}
					<div id="clause_{$i}_{$n}" style="margin-top: 5px;">
						<span style="font-weight: normal;">{$lang['clause']} {$n + 1}:</span>
						<a href="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change{if $smarty.get.languages}&amp;languages={$smarty.get.languages}{/if}&amp;page={$page}&amp;delete=clause&amp;id={$text.rutid}">X</a><br />
						<textarea name="description[]" rows="5" cols="74">{$text.text}</textarea><br />
					</div>
					{is_array var=$text.subclauses assign='textCheck'}
					{if $textCheck}
						{foreach from=$text item='subClause' name='subClause'}
							{assign var='m' value=$smarty.foreach.subClause.iteration}
							<div id="subclause_{$i}_{$n}_{$m}" style="margin-top: 5px;">
								<span style="font-weight: normal;">{$lang.subclause} {$m + 1}:</span>
								<a href="index.php?chose=tribunal&amp;sub=rules&amp;rules_sub=change{if $smarty.get.languages}&amp;languages={$smarty.get.languages}{/if}&amp;page={$page}&amp;delete=clause&amp;id={$subClause.rutid}">X</a><br />
								<textarea name="description[]" rows="5" cols="74">{$subclauses.text}</textarea><br />
							</div>
						{/foreach}
					{/if}
				{/foreach}
				<a href="javascript:;" onclick="cloneClause(this, '{$lang.clause}')">{$lang.new_clause}</a>
				<a href="javascript:;" onclick="cloneSubClause(this, '{$lang.subclause}')">{$lang.new_subclause}</a><br />
				<input style="margin-top: 5px;" type="submit" name="change_rule" value="{$lang.change_rule}" />
			</form>
		</div>
	</div>
{sectionelse}
	<div class="info">{$lang.noRulesDefined}</div>
{/section}
{if $pageLinks}
	<div class="pagelinks">{$pageLinks}</div>
{/if}