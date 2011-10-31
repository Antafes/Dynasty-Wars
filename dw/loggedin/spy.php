<?php
namespace loggedin\spy;
$x = $_POST['x'];
$y = $_POST['y'];
$units = $_POST['units'];
$build_eco = $_POST['building_economy'];
$build_mil = $_POST['building_military'];
$build_def= $_POST['building_defense'];
$build_sci = $_POST['building_science'];
$resource = $_POST['resources'];
$noSpies = $_POST['nospies'];
echo "x " . $x;
echo "y " . $y;
if ( $x == "" || $y == "" || $no) {
	drawSpyForm();
} else {
	$continue = 1;
	if ($noSpies == "") 
		$continue = 0;
	if (!is_int($noSpies))
		$continue = 0;
	if ($x == "")
		$continue = 0;
	if (!is_int($x))
		$continue = 0;
	if ($y == "")
		$continue = 0;
	if (!is_int($y))
		$continue = 0;
}


function drawSpyForm() {
?>
<form name="input" action="index.php?chose=spy" method="post">
	<table>
		<tr>
			<td>Target:</td>
			<td><input type="text" name="x"/>:<input type="text" name="y"/></td>
		</tr>
		<tr>
			<td>Units:</td>
			<td><input type="checkbox" name="units" value="1" /></td>
		</tr>
		<tr>
			<td>Buildings (economic):</td>
			<td><input type="checkbox" name="building_economy" value="1" /></td>
		</tr>
		<tr>
			<td>Buildings (military):</td>
			<td><input type="checkbox" name="building_military" value="1" /></td>
		</tr>
		<tr>
			<td>Buildings (defense):</td>
			<td><input type="checkbox" name="building_defense" value="1" /></td>
		</tr>
		<tr>
			<td>Buildings (science):</td>
			<td><input type="checkbox" name="building_science" value="1" /></td>
		</tr>
		<tr>
			<td>Resources:</td>
			<td><input type="checkbox" name="resources" value=1" /></td>
		</tr>
		<tr>
			<td>Amout of spies</td>
			<td><input type="text" name="nospies" /></td>
		</tr>		 
	 </table>
	<input type="submit" value="submit" />
</form>
<?php
}
?>
