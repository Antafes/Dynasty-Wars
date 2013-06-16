<div class="subheading">
	{$textUnitMove}
</div>
<div class="unit_move">
	<div class="row">
		<div class="both">
			<a href="index.php?chose=units&amp;sub=move&amp;mode=create">{$textCreateTroop}</a>
		</div>
	</div>
	{if $sent}
	<div class="row">
		<div class="both">
			{$textSent}
		</div>
	</div>
	{/if}
	<div class="row">
		<div class="left">
			{$textTroops}
		</div>
		<div class="middle">
			{$quantity}
		</div>
		<div class="right">&nbsp;</div>
		<div class="clear"></div>
	</div>
	{if $positionList}
	{foreach from=$positionList item=position}
	<div class="row">
		<div class="both bold" style="text-align: left;">
			{$position.x}:{$position.y}
		</div>
	</div>
	{foreach from=$position.troops item=troop}
	<div class="row">
		<div class="left">
			{$troop.name}
			{if $troop.res}<img src="pictures/ressources/{$troop.res}.png" height="15" alt="{$troop.loaded}" title="{$troop.loaded}" />{/if}
		</div>
		<div class="middle">
			{$troop.count}
		</div>
		<div class="right">
			{if $troop.onmoving.tmid && $troop.onmoving.type < 5}
			{$textOnMoving}
			{elseif $troop.onmoving.tmid && $troop.onmoving.type == 5}
			{$textComingBack}
			{else}
			{strip}
			{if $troop.atHome != 1}<a href="index.php?chose=units&amp;sub=move&amp;mode=goback&amp;tid={$troop.tid}&amp;pos={$position.x}:{$position.y}">{/if}
				<img src="pictures/goback{if $troop.atHome == 1}_disabled{/if}.png"
					 alt="{if $troop.atHome != 1}{$textGoBack}{else}{$textGoBackDisabled}{/if}"
					 title="{if $troop.atHome != 1}{$textGoBack}{else}{$textGoBackDisabled}{/if}"
					 {if $troop.atHome != 1}name="goback{$troop.tid}" onmouseover="changePic('goback{$troop.tid}', 'i31');"
					 onmouseout="changePic('goback{$troop.tid}', 'i31');" onclick="changePic('goback{$troop.tid}', 'i31');"{/if} />
			{if $troop.atHome != 1}</a>{/if}&nbsp;
			<a href="index.php?chose=units&amp;sub=move&amp;mode=send&amp;tid={$troop.tid}">
				<img src="pictures/send.png" alt="{$textSend}" title="{$textSend}"
					 name="send{$troop.tid}" onmouseover="changePic('send{$troop.tid}', 'i25');"
					 onmouseout="changePic('send{$troop.tid}', 'i26');" onclick="changePic('send{$troop.tid}', 'i27');" />
			</a>&nbsp;
			{if $troop.res}<a href="index.php?chose=units&amp;sub=move&amp;do=unload&amp;tid={$troop.tid}">{/if}
				<img src="pictures/unload{if !$troop.res}_disabled{/if}.png"
					 alt="{if $troop.res}{$textUnload}{else}{$textNoRessources}{/if}"
					 title="{if $troop.res}{$textUnload}{else}{$textNoRessources}{/if}"
					 {if $troop.res}name="unload{$troop.tid}" onmouseover="changePic('unload{$troop.tid}', 'i37');"
					 onmouseout="changePic('unload{$troop.tid}', 'i38');" onclick="changePic('unload{$troop.tid}', 'i39');"{/if} />
			{if $troop.res}</a>{/if}
			<a href="index.php?chose=units&amp;sub=move&amp;mode=edit&amp;tid={$troop.tid}">
				<img src="pictures/edit.png" alt="{$textEdit}" title="{$textEdit}"
					 name="edit{$troop.tid}" onmouseover="changePic('edit{$troop.tid}', 'i28');"
					 onmouseout="changePic('edit{$troop.tid}', 'i29');" onclick="changePic('edit{$troop.tid}', 'i30');" />
			</a>
			<a href="index.php?chose=units&amp;sub=move&amp;do=disband&amp;tid={$troop.tid}">
				<img src="pictures/delete.png" alt="{$textDisband}" title="{$textDisband}"
					 name="disband{$troop.tid}" onmouseover="changePic('disband{$troop.tid}', 'i34');"
					 onmouseout="changePic('disband{$troop.tid}', 'i35');" onclick="changePic('disband{$troop.tid}', 'i36');" />
			</a>
			{/strip}
			{/if}
			</div>
		<div class="clear"></div>
	</div>
	{/foreach}
	{/foreach}
	{else}
	<div class="row">
		<div class="both">
			{$textNoTroops}
		</div>
	</div>
	{/if}
</div>