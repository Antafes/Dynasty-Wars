<div class="subheading">{$lang.hearings}</div>
{assign var='i' value=0}
{foreach from=$hearings item=hearing}
	{if $i == 0}
	<div class="row" style="margin-bottom: 10px;">
	{/if}
		<div class="hearing_column"{if $i > 0} style="margin-left: 10px;"{/if}>
			<div class="row">
				<div class="both">
					<a href="index.php?chose=tribunal&amp;sub=hearings&amp;id={$hearing.tid}">{$lang.hearing_title|sprintf:$hearing.cause.cause}</a>
				</div>
			</div>
			<div class="row">
				<div class="left">{$lang.suitor_text}:</div>
				<div class="right">{$hearing.suitorNick}</div>
			</div>
			<div class="row">
				<div class="left">{$lang.accused_text}:</div>
				<div class="right">{$hearing.accusedNick}</div>
			</div>
			<div class="row" style="margin-top: 10px; height: auto;">
				<div class="both" style="text-align: left;">{$hearing.parsedCutOffDescription}</div>
			</div>
		</div>
	{assign var='i' value=$i+1}
	{if $i > 2 || $i > $hearingsCount}
	{assign var='i' value=0}
		<div class="clear"></div>
	</div>
	{/if}
{foreachelse}
	{$lang.no_hearings}
{/foreach}
{if $i != 0}
	<div class="clear"></div>
</div>
{/if}