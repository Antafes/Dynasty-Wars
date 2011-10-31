<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */

/**
 * Spy algorithm explanation:
 * 
 * 1) Calculate the total defense of the victim -> (number of spies) + DEF_STANDARD + (DEF_TOWERFACT * towerlevel)
 * 2) Calculate the disclosure chance -> (sum of all requested LOOKFOR) / (sum of all LOOKFORS)
 * 3) Calculate the ratio of attacking spies and defending spies
 * 4) Calculate how many spies will be riskchecked  -> (amount of attacking spies) * (ratio calculated in 3)
 * 5) Calculate enemy spy survivors -> (amount of spies that are riskchecked) * (disclosure chance)
 * 
 */
 namespace dwars\lib\bl;

//include "../dal/user.php";
//include "../bl/resource.php";
//include "../dal/unit.php";
//include "../bl/buildings.php";

// some consts used to determine what the spy should be looking for
const LOOKFOR_UNITS = 1;
const LOOKFOR_BUILDINGS_ECONOMY = 2;
const LOOKFOR_BUILDINGS_MILITARY = 3;
const LOOKFOR_BUILDINGS_DEFENSE = 4;
const LOOKFOR_BUILDINGS_SCIENCE = 5;
const LOOKFOR_RESOURCES = 6;

// cost / risk multiplication factors
const MF_UNITS = 1.3;
const MF_BUILDINGS_ECONOMY = 1.1;
const MF_BUILDINGS_MILITARY = 1.5;
const MF_BUILDINGS_DEFENSE = 1.5;
const MF_BUILDINGS_SCIENCE = 1.2;
const MF_RESOURCES = 1.1;

define('dwars\lib\bl\MF_TOTAL', namespace\MF_UNITS + namespace\MF_BUILDINGS_ECONOMY + namespace\MF_BUILDINGS_MILITARY + namespace\MF_BUILDINGS_DEFENSE + namespace\MF_BUILDINGS_SCIENCE + namespace\MF_RESOURCES);

// constant defensive values
const DEF_STANDARD = 50; // counts as extra spies for the player defending against a spy attack
const DEF_TOWERFACTOR = 30; // multiplicationfactor of the tower (tower level * factor = additional spies)

// initial cost
const INITIAL_COST = 1000;

function spyOn($uid, $numberOfSpies, $x, $y, $lookFor) {
        if (!hasEnoughSpies($uid, $numberOfSpies, $x, $y)) return 0;
	
	$spyCost = calculateSpyCost($lookFor) * numberOfSpies;
	
	if (!lib_bl_resource_hasEnoughOf($x, $y, "koku", $spyCost)) return -1;
	
	$victimUid = lib_dal_user_getUIDFromMapPosition($x, $y);
	
	$noSpiesOfVictim = calculateTotalDefense($victimUid, $x, $y);
	
	$remainingSpies = doRiskChecks($numberOfSpies, $noSpiesOfVictim, $lookFor);
	
	if ($remainingSpies <= 0) return -2; // all spies are dead, no intelligence data for you!
	
	generateSpyReport($victim, $x, $y, $lookFor);
	return 1;
}

/**
 * Calculates the cost for a single spy
 * @author siyb
 * @param int array of LOOKFOR ints
 */
function calculateSpyCost($lookFor) {
	$cost = INITIAL_COST;

	for ($i = 0; $i < sizeof($lookFor); $i++) {
		switch ($lookFor[$i]) {
			case LOOKFOR_UNITS:
				$cost *= MF_UNITS;
				break;
			case LOOKFOR_BUILDINGS_ECONOMY:
				$cost *= MF_BUILDINGS_ECONOMY;
				break;
			case LOOKFOR_BUILDINGS_MILITARY:
				$cost *= MF_BUILDINGS_MILITARY;
				break;
			case LOOKFOR_BUILDINGS_DEFENSE:
				$cost *= MF_BUILDINGS_DEFENSE;
				break;
			case LOOKFOR_BUILDINGS_SCIENCE:
				$cost *= MF_BUILDINGS_SCIENCE;
				break;
			case LOOKFOR_RESOURCES:
				$cost *= MF_RESOURCES;
				break;
			default:
				die("fuckup..");
		}
	}
	return $cost;
}

/**
 * Calculates the chance of disclosure for a single spy
 * @author siyb
 * @param int array of LOOKFOR ints
 */
function calculateDisclosureChance($lookFor) {
	$risk = 0;// inital risk of 0/sum(LOOKFOR)

	for ($i = 0; $i < sizeof($lookFor); $i++) {
		switch ($lookFor[$i]) {
			case LOOKFOR_UNITS:
				$risk += MF_UNITS;
				break;
			case LOOKFOR_BUILDINGS_ECONOMY:
				$risk += MF_BUILDINGS_ECONOMY;
				break;
			case LOOKFOR_BUILDINGS_MILITARY:
				$risk += MF_BUILDINGS_MILITARY;
				break;
			case LOOKFOR_BUILDINGS_DEFENSE:
				$risk += MF_BUILDINGS_DEFENSE;
				break;
			case LOOKFOR_BUILDINGS_SCIENCE:
				$risk += MF_BUILDINGS_SCIENCE;
				break;
			case LOOKFOR_RESOURCES:
				$risk += MF_RESOURCES;
				break;
			default:
				die("fuckup..");
		}
	}
	return $risk == namespace\MF_TOTAL ? 1 : ($risk / namespace\MF_TOTAL);
}

/**
 * Calculates the total definsive power of the victim
 * @author siyb
 * @param int uid
 * @param int x
 * @param int y
 */
function calculateTotalDefense($uid, $x, $y) {
	$towerInfo = lib_bl_buildings_getBuildingByKind(24, $x.":".$y);
	return noOfSpiesAtLocation($victimUid, $x, $y) + DEF_STANDARD + ($towerInfo['lvl'] * DEF_TOWERFACTOR);
}

/**
 * Does riskchecking and returns the number of spies that are still alive
 * @author siyb
 * @param int attackerNoSpies
 * @param int victimNoSpies
 * @param intarray lookFor
 */
function doRiskChecks($attackerNoSpies, $victimNoSpies, $lookFor) {
	$disclosureChance = calculateDisclosureChance($lookFor);
	$noRiskChecks = calculateRiskcheckAmount($attackerNoSpies, $victimNoSpies);
	return floor($noRiskChecks * $disclosureChance);
}

/**
 * Calculate the number of riskchecks that will be made.
 * @author siyb
 * @param int attackerNoSpies
 * @param int victimNoSpies
 */
function calculateRiskcheckAmount($attackerNoSpies, $victimNoSpies) {
	return floor($attackerNoSpies * getAttackerVictimSpyRatio($attackerNoSpies, $victimNoSpies));
}

/**
 * Returns the ratio that determines how often the risk will be applies
 * per attacking spy.
 * @author siyb
 * @param int attackerNoSpies
 * @param int victimNoSpies
 */
function getAttackerVictimSpyRatio($attackerNoSpies, $victimNoSpies) {
	return$victimNoSpies / $attackerNoSpies;
}

/**
 * @author siyb
 * @param int uid
 * @param int x
 * @param int y
 */
function noOfSpiesAtLocation($uid, $x, $y) {
	return lib_dal_unit_getUnitCountByCoordinates(3, $uid, $x, $y);
}

/**
 * Checks if user by known by uid has an equal or higher amount of
 * spies at x,y
 * @author siyb
 * @param int uid
 * @param int numberOfSpies
 * @param int x
 * @param int y
 */
function hasEnoughSpies($uid, $numberOfSpies, $x, $y) {
	return ($numberOfSpies <= noOfSpiesAtLocation($uid, $x, $y));
}

/**
 * Generates a spy report
 * @author siyb
 */
function generateSpyReport($victim, $x, $y, $lookFor) {
}

?>
