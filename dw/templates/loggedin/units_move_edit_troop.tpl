<div class="subheading">
	{$editTroop}
</div>
<div class="edit_unit">
	<div class="row">
		<div class="left">
			<div class="heading">
				{$units}
			</div>
			{foreach from=$unitList item=unit}
			<div class="row">
				<div class="left">
					{$unit.name}
				</div>
				<div class="right">
					{$unit.count}
				</div>
				<div class="clear"></div>
			</div>
			{/foreach}
			<div class="row">
				<div class="left">&nbsp;</div>
				<div class="right">
					{$unitSum}
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="right">
			<form method="post" action="index.php?chose=units&amp;sub=move&amp;mode=edit&amp;tid={$smarty.get.tid}">
				<div class="heading">&nbsp;</div>
				<div class="row">
					<div class="left">
						{$name}:
					</div>
					<div class="right">
						<input type="text" name="tname" value="{$troop.name}" maxlength="20" size="15" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="both">
						<input type="submit" name="change" value="{$change}" />
					</div>
				</div>
			</form>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row" style="margin-top: 10px;">
		<div class="left" style="text-align: center;">
			<a href="index.php?chose=units&amp;sub=move&amp;mode=edit&amp;tid={$smarty.get.tid}&amp;do=add">
				{$add}
			</a>
		</div>
		<div class="right" style="text-align: center;">
			<a href="index.php?chose=units&amp;sub=move&amp;mode=edit&amp;tid={$smarty.get.tid}&amp;do=remove">
				{$remove}
			</a>
		</div>
		<br class="clear" />
	</div>
	<div class="row">
		<div class="both">
			<a href="index.php?chose=units&amp;sub=move">{$back}</a>
		</div>
	</div>
</div>