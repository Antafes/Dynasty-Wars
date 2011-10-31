<div class="subheading">
	{$textUnitMove|htmlentities}
</div>
<div class="unit_move">
	<div class="row">
		<div class="both">
			<a href="index.php?chose=units&amp;sub=move&amp;mode=create">{$textCreateTroop|htmlentities}</a>
		</div>
	</div>
	{if $sent}
	<div class="row">
		<div class="both">
			{$textSent|htmlentities}
		</div>
	</div>
	{/if}
	<div class="row">
		<div class="left">
			{$textTroops|htmlentities}
		</div>
		<div class="middle">
			{$quantity|htmlentities}
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
			{$troop.name|htmlentities}
			{if $troop.res}<img src="pictures/ressources/{$troop.res}.png" height="15" alt="{$troop.loaded|htmlentities}" title="{$troop.loaded|htmlentities}" />{/if}
		</div>
		<div class="middle">
			{$troop.count}
		</div>
		<div class="right">
			{if $troop.onmoving.endtime && $troop.onmoving.type < 5}
			{$textOnMoving}
			{elseif $troop.onmoving.endtime && $troop.onmoving.type == 5}
			{$textComingBack}
			{else}
			{strip}
			{if $troop.atHome != 1}<a href="index.php?chose=units&amp;sub=move&amp;mode=goback&amp;tid={$troop.tid}&amp;pos={$position.x}:{$position.y}">{/if}
				<img src="pictures/goback{if $troop.atHome == 1}_disabled{/if}.png"
					 alt="{if $troop.atHome != 1}{$textGoBack|htmlentities}{else}{$textGoBackDisabled|htmlentities}{/if}"
					 title="{if $troop.atHome != 1}{$textGoBack|htmlentities}{else}{$textGoBackDisabled|htmlentities}{/if}"
					 {if $troop.atHome != 1}name="goback{$troop.tid}" onmouseover="changePic('goback{$troop.tid}', 'i31');"
					 onmouseout="changePic('goback{$troop.tid}', 'i31');" onclick="changePic('goback{$troop.tid}', 'i31');"{/if} />
			{if $troop.atHome != 1}</a>{/if}&nbsp;
			<a href="index.php?chose=units&amp;sub=move&amp;mode=send&amp;tid={$troop.tid}">
				<img src="pictures/send.png" alt="{$textSend|htmlentities}" title="{$textSend|htmlentities}"
					 name="send{$troop.tid}" onmouseover="changePic('send{$troop.tid}', 'i25');"
					 onmouseout="changePic('send{$troop.tid}', 'i26');" onclick="changePic('send{$troop.tid}', 'i27');" />
			</a>&nbsp;
			{if $troop.res}<a href="index.php?chose=units&amp;sub=move&amp;do=unload&amp;tid={$troop.tid}">{/if}
				<img src="pictures/unload{if !$troop.res}_disabled{/if}.png"
					 alt="{if $troop.res}{$textUnload|htmlentities}{else}{$textNoRessources|htmlentities}{/if}"
					 title="{if $troop.res}{$textUnload|htmlentities}{else}{$textNoRessources|htmlentities}{/if}"
					 {if $troop.res}name="unload{$troop.tid}" onmouseover="changePic('unload{$troop.tid}', 'i37');"
					 onmouseout="changePic('unload{$troop.tid}', 'i38');" onclick="changePic('unload{$troop.tid}', 'i39');"{/if} />
			{if $troop.res}</a>{/if}
			<a href="index.php?chose=units&amp;sub=move&amp;mode=edit&amp;tid={$troop.tid}">
				<img src="pictures/edit.png" alt="{$textEdit|htmlentities}" title="{$textEdit|htmlentities}"
					 name="edit{$troop.tid}" onmouseover="changePic('edit{$troop.tid}', 'i28');"
					 onmouseout="changePic('edit{$troop.tid}', 'i29');" onclick="changePic('edit{$troop.tid}', 'i30');" />
			</a>
			<a href="index.php?chose=units&amp;sub=move&amp;do=disband&amp;tid={$troop.tid}">
				<img src="pictures/delete.png" alt="{$textDisband|htmlentities}" title="{$textDisband|htmlentities}"
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
			{$textNoTroops|htmlentities}
		</div>
	</div>
	{/if}
</div>