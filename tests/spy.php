<?php

namespace dwars\lib\bl;

include "../dw/lib/dal/user.php";
include "../dw/lib/bl/resource.php";
include "../dw/lib/dal/unit.php";
include "../dw/lib/bl/buildings.php";
include "../dw/lib/dal/buildings.php";
include "../dw/lib/bl/spy.php";
include "../dw/lib/config.php";
include "../dw/lib/util/mysql.php";
$con = @mysql_connect($server, $seruser, $serpw);
mysql_select_db($serdb, $con) or die("Fehler, keine Datenbank!");

$lookFor = array(LOOKFOR_UNITS, LOOKFOR_BUILDINGS_ECONOMY, LOOKFOR_BUILDINGS_MILITARY);
/*
const MF_UNITS = 1.3;
const MF_BUILDINGS_ECONOMY = 1.1;
const MF_BUILDINGS_MILITARY = 1.5;
const MF_BUILDINGS_DEFENSE = 1.5;
const MF_BUILDINGS_SCIENCE = 1.2;
const MF_RESOURCES = 1.1;
*/


for ($i = 1; $i < 11; $i++) {

if ($i < 5) {
	$attackerNoSpies = log(100 * $i)*10;
	$victimNoSpies = log(300 + $i * $i)*20;
} else {
	$attackerNoSpies = log(300 * $i)*20;
	$victimNoSpies = log(100 + $i * $i)*10;
}
	echo "Attacker " . $attackerNoSpies . " Defender " . $victimNoSpies . "<br />";

	echo "cost (attacker)<br />";
	echo calculateSpyCost($lookFor);

	echo "<br />chance<br />";
	echo calculateDisclosureChance($lookFor);

	echo "<br />Attacker victim ratio<br />";
	echo getAttackerVictimSpyRatio($attackerNoSpies, $victimNoSpies);

	echo "<br />Number of riskchecks<br />";
	echo calculateRiskcheckAmount($attackerNoSpies, $victimNoSpies);

	echo "<br />Risk checking (returns attacking spies alive after attack)<br />";
	echo doRiskChecks($attackerNoSpies, $victimNoSpies, $lookFor);
	
	echo "<br /><br /><br />";

}
echo "<br /><br /><br />DATABASE SHIT<br /><br /><br />";

echo "<br />Defense siyb<br />";
echo calculateTotalDefense(10,393,84);

?>