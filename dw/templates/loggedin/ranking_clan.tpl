<table width="100%">
<tr><td width="90" class="table_tc">{$lang.rank|htmlentities}</td>
<td width="140" class="table_tc">{$lang.player|htmlentities}</td>
<td width="90" class="table_tc">{$lang.units|htmlentities}</td>
<td width="90" class="table_tc">{$lang.buildings|htmlentities}</td>
<td width="90" class="table_tc">{$lang.total|htmlentities}</td>

{foreach from=$rank_list item=value key=nr}
    <tr><td width="90" class="table_tc">{$nr+1}</td>
    <td width="140" class="table_tc">
    <a href="index.php?chose=clan&amp;cid={$value['cid']}">{$value['clanname']}</a></td>
    <td width="90" class="table_tc">{$value['unitPoints']}</td>
    <td width="90" class="table_tc">{$value['buildingPoints']}</td>
    <td width="90" class="table_tc">{$value['points']}</td></tr>
{/foreach}
</table>