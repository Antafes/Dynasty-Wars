<table width="100%">
<tr><td width="90" class="table_tc">{$table_rank}</td>
<td width="140" class="table_tc">{$table_player}</td>
<td width="90" class="table_tc">{$table_units}</td>
<td width="90" class="table_tc">{$table_buildings}</td>
<td width="90" class="table_tc">{$table_total}</td>
<td width="85" class="table_tc">&nbsp;</td></tr>

{foreach from=$rank_list item=value key=nr}
    <tr><td width="90" class="table_tc">{$nr+1}</td>
    <td width="140" class="table_tc">
    <a href="index.php?chose=clan&amp;cid={$value['cid']}">{$value['clanname']}</a></td>
    <td width="90" class="table_tc">{$value['unitPoints']}</td>
    <td width="90" class="table_tc">{$value['buildingPoints']}</td>
    <td width="90" class="table_tc">{$value['points']}</td></tr>
{/foreach}
</table>