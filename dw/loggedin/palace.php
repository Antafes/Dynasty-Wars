<br/><br/>
<?php
//-------------
//nur nen dummy
//-------------
 $startsqlstring = "SELECT clan, rang FROM dw_user WHERE usernr='$usernr'";
 $starterg = mysql_query($startsqlstring, $verbindung);
 if ($starterg) {
  $start = mysql_fetch_array($starterg);
  $userclannr = $start["clannr"];
  $rang = $start["rang"];
 }
?>
<table width="80%" border="1" class="box_3">
 <tr>
  <td width="100%" class="box_6"><font size="5"><strong>Palast</strong></font></td>
 </tr>
 <tr>
  <td width="100%" class="box_3">&nbsp;</td>
 </tr>
 <tr>
  <td width="100%" class="box_4">
   &nbsp;
   <!-- Palast -->
  </td>
 </tr>
</table>