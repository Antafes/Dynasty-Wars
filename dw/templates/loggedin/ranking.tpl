{include file='header.tpl'}

<div class="heading">{$heading}</div>

<div class="ranking_content">
    <div class="left_head">{$link_player}</div>
    <div class="right_head">{$link_clans}</div>        
    <div class="div_breaker">&nbsp;</div>
    
    {if !$smarty.get.rank_tab || $smarty.get.rank_tab == 1}
        {include file='ranking_user.tpl'}
    {else}
        {include file='ranking_clan.tpl'}
    {/if}    
</div>
{include file='footer.tpl'}
<!--   
        
    
        <div class="div_breaker">&nbsp;</div>                
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
                
        <table width="100%" class="no_content">
            <tr><td width="90" class="table_tc">{$table_rank}</td>
            <td width="140" class="table_tc">{$table_player}</td>
            <td width="90" class="table_tc">{$table_units}</td>
            <td width="90" class="table_tc">{$table_buildings}</td>
            <td width="90" class="table_tc">{$table_total}</td>
            <td width="85" class="table_tc">&nbsp;</td></tr>
        
        
        
        </table>
    </div>-->
