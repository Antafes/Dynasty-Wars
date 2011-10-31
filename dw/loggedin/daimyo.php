<br/><br/>
<?php
//-------------
//only a dummy
//-------------
 $startsqlstring = "SELECT clan, rang FROM dw_user WHERE usernr='$usernr'";
 $starterg = mysql_query($startsqlstring, $verbindung);
 if ($starterg) {
  $start = mysql_fetch_array($starterg);
  $userclannr = $start["clannr"];
  $rang = $start["rang"];
 }
?>
<form method="post" action="index.php?chose=daimyo">
<table width="700" border="1" class="box_3">
 <tr>
  <td width="210" class="box_6">
   <img src="bilder/daimyo/daimyo1.png" alt="Daimyo" />
  </td>
  <td width="490" class="box_4">
   <font size="5"><b>Daimyo</b></font><br/>
   <br/>
   <table width="490" border="1" class="box_3">
    <tr>
     <th width="98" class="box_4">Eigenschaften</th>
     <td width="196" class="box_3">&nbsp;</td>
     <td width="196" class="box_3">&nbsp;</td>
    </tr>
    <tr>
     <td width="98" class="box_4">Eigenschaft 1</td>
     <th width="196" class="box_4">Waffe</th>
     <td width="196" class="box_3">&nbsp;</td>
    </tr>
    <tr>
     <td width="98" class="box_4">Eigenschaft 2</td>
     <td width="196" class="box_4">
      <select name="weapon">
       <option selected="selected">Einfaches Schwert</option>
       <option>Schwert 2</option>
       <option>Schwert 3</option>
       <option>Schwert 4</option>
       <option>Schwert 5</option>
      </select>
     </td>
     <td width="196" class="box_3">&nbsp;</td>
    </tr>
    <tr>
     <td width="98" class="box_4">Eigenschaft 3</td>
     <th width="196" class="box_4">Schild</th>
     <td width="196" class="box_3">&nbsp;</td>
    </tr>
    <tr>
     <td width="98" class="box_4">Eigenschaft 4</td>
     <td width="196" class="box_4">
      <select name="shield">
       <option selected="selected">Einfacher Schild</option>
       <option>Schild 2</option>
       <option>Schild 3</option>
       <option>Schild 4</option>
       <option>Schild 5</option>
      </select>
     </td>
     <td width="196" class="box_3">&nbsp;</td>
    </tr>
    <tr>
     <td width="98" class="box_4">Eigenschaft 5</td>
     <th width="196" class="box_4">R&uuml;stung</th>
     <td width="196" class="box_3">&nbsp;</td>
    </tr>
    <tr>
     <td width="98" class="box_4">Eigenschaft 6</td>
     <td width="196" class="box_4">
      <select name="armor">
       <option selected="selected">Einfache R&uuml;stung</option>
       <option>R&uuml;stung 2</option>
       <option>R&uuml;stung 3</option>
       <option>R&uuml;stung 4</option>
       <option>R&uuml;stung 5</option>
      </select>
     </td>
     <td width="196" class="box_3">&nbsp;</td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>