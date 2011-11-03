<div id="user_info" class="hidden">
    <div class="row">
        <div class="left">{$user_info_since}: </div>
        <div class="r" id="register"></div>
    </div>
    <div class="row">
        <div class="left">{$user_info_position}: </div>
        <div class="r" id="pos"></div>
    </div>
    <div class="row">
        <div class="left">{$user_info_points}: </div>
        <div class="r" id="points"></div>
    </div>
    <div class="row">
        <div class="left">{$user_info_clan}: </div>
        <div class="r" id="clan"></div>
    </div>
</div>
<table width="100%">
    {foreach from=$rank_list item=value key=nr}
        <tr><td width="90" class="table_tc">{$nr+1}</td>
        <td width="140" class="table_tc">
            <!--<a href="javascript:;" onClick="showUserInfo(this,'user_info',{$value['regdate']},{$value['city']},{$value['points']},{$value['clanname']})">-->
             <a href="javascript:;" onClick="showUserInfo(this,'user_info','{$value['regdate']}','{$value['city']}','{$value['points']}','{$value['clanname']}')">
                {$value['nick']}
            </a>
        </td>
        <td width="90" class="table_tc">{$value['unit_points']}</td>
        <td width="90" class="table_tc">{$value['building_points']}</td>
        <td width="90" class="table_tc">{$value['points']}</td>
        <td width="85" class="table_tc">
        {if $smarty.session.user->getUID() != $value.uid}
            <a href="index.php?chose=messages&amp;mmode=new&amp;recipient={$value.uid}">
                <div style="" class="send_msg">&nbsp;</div>
            </a>
        {/if}
        </td></tr>


    {/foreach}
</table>