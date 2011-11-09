<div class="subheading">{$lang.hearings|htmlentities}</div>
{counter name='hearings' assign=i start=1 print=false}
{foreach from=$hearings item=hearing}
	{if $i == 0}
	<div class="row">
	{/if}
		<div class="hearing_column"{if $i > 0} style="margin-left: 15px;"{/if}>
			<div class="row">
				<div class="both">
					<a href="index.php?chose=tribunal&amp;sub=hearings&amp;id={$hearing.tid}">{$lang.hearing_title|sprintf:$hearing.cause.cause|htmlentities}</a>
				</div>
			</div>
			<div class="row">
				<div class="left">{$lang.suitor_text|htmlentities}:</div>
				<div class="right">{$hearing.suitorNick|htmlentities}</div>
			</div>
			<div class="row">
				<div class="left">{$lang.accused_text|htmlentities}:</div>
				<div class="right">{$hearing.accusedNick|htmlentities}</div>
			</div>
			<div class="row" style="margin-top: 10px; height: auto;">
				<div class="both" style="text-align: left;">{$hearing.parsedCutOffDescription}</div>
			</div>
		</div>
	{if $i > 2 || $i > $hearingsCount}
	{counter name='hearings' assign=i start=1 print=false}
	</div>
	{else}
		{counter name='hearings'}
	{/if}
{foreachelse}
	{$lang.no_hearings|htmlentities}
{/foreach}