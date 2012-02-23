<div id="user_info" class="hidden">
    <div class="row">
        <div class="left">{$lang.since|htmlentities}: </div>
        <div class="r" id="register"></div>
    </div>
    <div class="row">
        <div class="left">{$lang.position|htmlentities}: </div>
        <div class="r" id="pos"></div>
    </div>
    <div class="row">
        <div class="left">{$lang.points|htmlentities}: </div>
        <div class="r" id="points"></div>
    </div>
    <div class="row">
        <div class="left">{$lang.clan|htmlentities}: </div>
        <div class="r" id="clan"></div>
    </div>
</div>
<table width="100%">
	<tr>
		<th>{$lang.rank|htmlentities}</th>
		<th>{$lang.player|htmlentities}</th>
		<th>{$lang.units|htmlentities}</th>
		<th>{$lang.buildings|htmlentities}</th>
		<th>{$lang.total|htmlentities}</th>
		<th>&nbsp;</th>
	</tr>
    {foreach from=$rank_list item=value key=nr}
        <tr>
			<td width="90" class="table_tc">{$nr+1}</td>
			<td width="140" class="table_tc">
				<a href="javascript:;" onClick="showUserInfo(this,'user_info','{$value['registration_datetime']}','{$value['city']}','{$value['points']}','{$value['clanname']}')">
					{$value['nick']}
				</a>
			</td>
			<td width="90" class="table_tc">{$value['unit_points']}</td>
			<td width="90" class="table_tc">{$value['building_points']}</td>
			<td width="90" class="table_tc">{$value['points']}</td>
			<td width="85" class="table_tc">
			{if $smarty.session.user->getUID() != $value.uid}
				<a class="message" href="index.php?chose=messages&amp;mmode=new&amp;recipient={$value.uid}">
					<div style="" class="send_msg">&nbsp;</div>
				</a>
			{/if}
			</td>
		</tr>
    {/foreach}
</table>