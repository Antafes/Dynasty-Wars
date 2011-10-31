{if $sent}
<div class="row">
	<div class="both">
		{$textSent|htmlentities}
	</div>
</div>
<div class="row">
	<div class="both">
		<a href="index.php?chose=units&amp;sub=move">{$back|htmlentities}</a>
	</div>
</div>
{/if}
<div class="subheading">
	{$troop.name|htmlentities}
</div>
{if $errors}
<div class="unit_send_errors">
	{foreach from=$errors item=error key=type}
	<div class="row">
		<div class="both">
			{if $type == 'noTarget'}
				{$textNoUser|htmlentities}
			{elseif $type == 'clanMember'}
				{$textSameClan|htmlentities}
			{elseif $type == 'capacity'}
				{$textOverCapacity|htmlentities}
			{/if}
		</div>
	</div>
	{/foreach}
</div>
{/if}
<div class="unit_send">
	<div class="row">
		<div class="left">
			<div class="row">
				<div class="subheading">
					{$units|htmlentities}
				</div>
			</div>
			{foreach from=$unitList item=unit}
			<div class="row">
				<div class="left">
					{$unit.name|htmlentities}
				</div>
				<div class="right">
					{$unit.count_formatted}
				</div>
				<div class="clear"></div>
			</div>
			{/foreach}
			<div class="row">
				<div class="left">&nbsp;</div>
				<div class="right">
					{$sum}
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="both">
					{$textCapacity|htmlentities}: {$maxCapacity.formatted}
				</div>
			</div>
		</div>
		<div class="right">
			<form method="post" name="tsend" action="index.php?chose=units&amp;sub=move&amp;mode=send&amp;tid={$smarty.get.tid}">
				<div class="subheading">
					{$textMoveOptions|htmlentities}
				</div>
				<div class="row">
					<div class="left">
						{$textPosition|htmlentities}
					</div>
					<div class="right">
						{$troop.pos_x}:{$troop.pos_y}
						<input type="hidden" name="posx" value="{$troop.pos_x}" />
						<input type="hidden" name="posy" value="{$troop.pos_y}" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="left">
						{$textTarget|htmlentities}:
					</div>
					<div class="right">
						<input type="text" name="tx" value="{$smarty.get.tx}" size="3" maxlength="3" />:<input type="text" name="ty" value="{$smarty.get.ty}" size="3" maxlength="3" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="left">
						{$textMoveKind|htmlentities}:
					</div>
					<div class="right">
						<select id="movekind_select" name="movekind">
							<option value="0" selected="selected">&nbsp;</option>
							<option value="1">{$textMoveKindsArray.defend|htmlentities}</option>
							<option value="2">{$textMoveKindsArray.transport|htmlentities}</option>
							<option value="3"{if $canAttack != 1} disabled="disabled"{/if}>{$textMoveKindsArray.attack|htmlentities}</option>
							<option value="4"{if $canAttack != 1 || true} disabled="disabled"{/if}>{$textMoveKindsArray.robbery|htmlentities}</option>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				<div class="row" id="transport_res">
					<div class="left">
						<select id="ressource_select" name="resselect">
							<option value="0" selected="selected">&nbsp;</option>
							<option value="food">{$ressources.food|htmlentities}</option>
							<option value="wood">{$ressources.wood|htmlentities}</option>
							<option value="rock">{$ressources.rock|htmlentities}</option>
							<option value="iron">{$ressources.iron|htmlentities}</option>
							<option value="paper">{$ressources.paper|htmlentities}</option>
							<option value="koku">{$ressources.koku|htmlentities}</option>
						</select>
					</div>
					<div class="right">
						<input id="ressource_amount" type="text" name="rescount" value="0" style="width: 65px;" />
						<a style="text-decoration: none;" href="javascript:;" onclick="$('#ressource_amount').val({$maxCapacity.plain});">
							{$textMax|htmlentities}
						</a>
					</div>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="both">
						<input id="send_button" type="submit" name="send" value="{$textSend|htmlentities}" />
					</div>
				</div>
			</form>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div class="both">
			<a href="index.php?chose=units&amp;sub=move">{$back|htmlentities}</a>
		</div>
	</div>
</div>