{if $sent}
<div class="row">
	<div class="both">
		{$textSent}
	</div>
</div>
<div class="row">
	<div class="both">
		<a href="index.php?chose=units&amp;sub=move">{$back}</a>
	</div>
</div>
{/if}
<div class="subheading">
	{$troop.name}
</div>
{if $errors}
<div class="unit_send_errors">
	{foreach from=$errors item=error key=type}
	<div class="row">
		<div class="both">
			{if $type == 'noTarget'}
				{$textNoUser}
			{elseif $type == 'clanMember'}
				{$textSameClan}
			{elseif $type == 'capacity'}
				{$textOverCapacity}
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
					{$units}
				</div>
			</div>
			{foreach from=$unitList item=unit}
			<div class="row">
				<div class="left">
					{$unit.name}
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
					{$textCapacity}: {$maxCapacity.formatted}
				</div>
			</div>
		</div>
		<div class="right">
			<form method="post" name="tsend" action="index.php?chose=units&amp;sub=move&amp;mode=send&amp;tid={$smarty.get.tid}">
				<div class="subheading">
					{$textMoveOptions}
				</div>
				<div class="row">
					<div class="left">
						{$textPosition}
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
						{$textTarget}:
					</div>
					<div class="right">
						<input type="text" name="tx" value="{$smarty.get.tx}" size="3" maxlength="3" />:<input type="text" name="ty" value="{$smarty.get.ty}" size="3" maxlength="3" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="left">
						{$textMoveKind}:
					</div>
					<div class="right">
						<select id="movekind_select" name="movekind">
							<option value="0" selected="selected">&nbsp;</option>
							<option value="1">{$textMoveKindsArray.defend}</option>
							<option value="2">{$textMoveKindsArray.transport}</option>
							<option value="3"{if $canAttack != 1} disabled="disabled"{/if}>{$textMoveKindsArray.attack}</option>
							<option value="4"{if $canAttack != 1 || true} disabled="disabled"{/if}>{$textMoveKindsArray.robbery}</option>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				<div class="row" id="transport_res">
					<div class="left">
						<select id="ressource_select" name="resselect">
							<option value="0" selected="selected">&nbsp;</option>
							<option value="food">{$ressources.food}</option>
							<option value="wood">{$ressources.wood}</option>
							<option value="rock">{$ressources.rock}</option>
							<option value="iron">{$ressources.iron}</option>
							<option value="paper">{$ressources.paper}</option>
							<option value="koku">{$ressources.koku}</option>
						</select>
					</div>
					<div class="right">
						<input id="ressource_amount" type="text" name="rescount" value="0" style="width: 65px;" />
						<a style="text-decoration: none;" href="javascript:;" onclick="$('#ressource_amount').val({$maxCapacity.plain});">
							{$textMax}
						</a>
					</div>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="both">
						<input id="send_button" type="submit" name="send" value="{$textSend}" />
					</div>
				</div>
			</form>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div class="both">
			<a href="index.php?chose=units&amp;sub=move">{$back}</a>
		</div>
	</div>
</div>