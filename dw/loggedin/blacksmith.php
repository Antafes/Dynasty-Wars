<?php
//-------------
//only a dummy
//-------------
//requesting of get and post variables
/* nothing here ^^ */
//selection of the user informations
 $startsqlstring = "SELECT clan, rang FROM dw_user WHERE usernr='$usernr'";
 $starterg = mysql_query($startsqlstring, $verbindung);
 if ($starterg) {
  $start = mysql_fetch_array($starterg);
  $userclannr = $start["clannr"];
  $rang = $start["rang"];
 }
?>
<form method="post" action="index.php?chose=blacksmith">
<table width="670" border="1" class="box_3">
 <tr>
  <td width="670" class="box_6" colspan="3"><font size="5"><b>Schmiede</b></font></td>
 </tr>
 <tr>
  <td width="670" class="box_3" colspan="3">&nbsp;</td>
 </tr>
 <tr>
  <td width="125" class="box_4">Waffen</td>
  <td width="350" class="box_4">
   <select name="weapon">
    <option selected="selected">Waffe 1</option>
    <option>Waffe 2</option>
    <option>Waffe 3</option>
    <option>Waffe 4</option>
    <option>Waffe 5</option>
   </select>
  </td>
  <td width="195" class="box_4">
   <input type="submit" name="make" value="Waffe schmieden"/>
  </td>
 </tr>
 <tr>
  <td width="125" class="box_4">Schilde</td>
  <td width="350" class="box_4">
   <select name="shield">
    <option selected="selected">Schild 1</option>
    <option>Schild 2</option>
    <option>Schild 3</option>
    <option>Schild 4</option>
    <option>Schild 5</option>
   </select>
  </td>
  <td width="195" class="box_4">
   <input type="submit" name="make" value="Schild schmieden"/>
  </td>
 </tr>
 <tr>
  <td width="125" class="box_4">R&uuml;stungen</td>
  <td width="350" class="box_4">
   <select name="armor">
    <option selected="selected">R&uuml;stung 1</option>
    <option>R&uuml;stung 2</option>
    <option>R&uuml;stung 3</option>
    <option>R&uuml;stung 4</option>
    <option>R&uuml;stung 5</option>
   </select>
  </td>
  <td width="195" class="box_4">
   <input type="submit" name="make" value="R&uuml;stung schmieden"/>
  </td>
 </tr>
</table>
</form>