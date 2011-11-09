{section name='rules' loop=$rules start=(($page - 1) * 5 + 1) max=5}
	{assign var='i' value=$smarty.section.rules.index}
	<div class="rules">
		<span>&sect;{$i} {$rules[$i].title|htmlentities}</span><br />
		<ol>
			{foreach from=$rules[$i].texts item='text'}
				<li>
					{$text.text|htmlentities|nl2br}
					{is_array var=$text.subclauses assign='textCheck'}
					{if $textCheck}
						<ol>
							{foreach from=$text.subclauses item='subclause'}
								<li>{$subclause.text|htmlentities|nl2br}</li>
							{/foreach}
						</ol>
					{/if}
				</li>
			{/foreach}
		</ol>
	</div>
{sectionelse}
	<div class="info">{$lang.noRulesDefined|htmlentities}</div>
{/section}
{if $pageLinks}
	<div class="pagelinks">
		{$pageLinks}
	</div>
{/if}