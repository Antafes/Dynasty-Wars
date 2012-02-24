<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */

/**
 * Places resources on the market.
 * @author siyb
 * @param <int> $uid the id of the user that sells the resource
 * @param <string> $sellResource the name of the resource to be sold
 * @param <float> $sellAmount the amount of the resource
 * @param <string> $exchangeResource the resource requested in exchange
 * @param <float> $exchangeAmount the amount of the resource requested in exchange
 * @return <int> 0 if the user does not have the disired amount of the resource
 * 1 if the goods have been places on the market successfully
 */
function lib_bl_market_placeResourceOnMarket($uid, $sellResource, $sellAmount,
	$exchangeResource, $exchangeAmount, $tax, $city)
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];

	// check if the user has enough resources
	if (dal\resource\returnResourceAmount($x, $y, $sellResource) < $sellAmount) {
		return 0;
	}

	// remove the specified amount of the resource from the user
	dal\resource\addToResources($sellResource, $sellAmount * -1, $x, $y);

	// place resource on market
	dal\market\placeOnMarket(
		$uid,
		$x,
		$y,
		$sellResource,
		$sellAmount,
		$exchangeResource,
		$exchangeAmount,
		$tax
	);

	return 1;
}

/**
 * This function allows players to respond to an open offer on the market. All
 * transactions will be executed within this function, no other precautions need
 * to be taken.
 * @author siyb
 * @param <int> $uid the userid of the buyer
 * @param <int> $mid the offer's id
 * @return <int> 0 if the user owes the offer, -1 if the offer is closed,
 * -2 if the user does not have the capital to purchase the offer, 1 if the pur-
 * chase was successful
 */

function lib_bl_market_buy($uid, $mid, $city)
{
	$cityexp = explode(":", $city);
	$x = $cityexp[0];
	$y = $cityexp[1];
	if (dal\market\getOwner($mid) == $uid) // check if the user owns the offer
		return 0;
	if (!dal\market\isOpen($mid)) // check if the offer is still open
		return -1;
	$offerDetails = dal\market\getOfferDetails($mid);
	if (!$offerDetails['sx'] || !$offerDetails['sy'])
	{
		$seller = dal\map\getUsersMainCity($offerDetails['sid']);
		$offerDetails['sx'] = $seller['map_x'];
		$offerDetails['sy'] = $seller['map_y'];
	}

	// check if the buyer has enough resourses to pay for the offer
	if (dal\resource\returnResourceAmount($x, $y, $offerDetails['e_resource'])
		< $offerDetails['e_amount']) {
		return -2;
	}

	dal\market\removeFromMarket($mid, $uid); // change the offer's flag to finished

	// transfer the goods to the buyer
	dal\resource\addToResources(
		$offerDetails['s_resource'],
		$offerDetails['s_amount'] - $offerDetails['tax'],
		$x,
		$y
	);

	// remove the payment from the buyers account
	dal\resource\addToResources(
		$offerDetails['e_resource'],
		$offerDetails['e_amount'] * -1,
		$x,
		$y
	);

	// ... and transfer it to the seller
	dal\resource\addToResources(
		$offerDetails['e_resource'],
		$offerDetails['e_amount'],
		$offerDetails['sx'],
		$offerDetails['sy']
	);

	// send a message to the seller
	$userlang = lib_bl_general_getLanguage($offerDetails['sid']);
	$lang = array();
	$lang += lib_bl_general_loadLanguageFile('event_messages', 'loggedin', false, $userlang);
	$lang += lib_bl_general_loadLanguageFile('main', 'loggedin', false, $userlang);
	lib_bl_general_sendMessage(
		0,
		$offerDetails['sid'],
		$lang["buy_title"],
		sprintf(
			$lang["buy_msg"],
			dal\user\uid2nick($uid),
			$offerDetails['s_amount'],
			htmlentities($lang[$offerDetails['s_resource']]),
			$offerDetails['e_amount'],
			htmlentities($lang[$offerDetails['e_resource']])
		),
		3
	);

	return 1;
}

/**
 * Anull an offer on the market
 * @param <int> $uid the users id
 * @param <int> $mid the id of the offer
 * @return <int> 0 if the user doesn't owe the offer, -1 if the offer is closed
 * 1 if the anullment was successful
 */
function lib_bl_market_anull($uid, $mid, $city)
{
	$city_exp = explode(':', $city);
	if (dal\market\getOwner($mid) != $uid)
		return 0; // check if the user owns the offer
	if (!dal\market\isOpen($mid))
		return -1; // check if the offer is still open
	$offerDetails = dal\market\getOfferDetails($mid);
	dal\market\removeFromMarket($mid, $uid); // change the offer's flag to finished

	// lets transfer the offers resources back to the owner
	dal\resource\addToResources(
		$offerDetails['s_resource'],
		$offerDetails['s_amount'],
		$city_exp[0],
		$city_exp[1]
	);
	return 1;
}

/**
 * Just a wrapper for the lib_dal_market_userOffers() function that seperates the
 * display layer from the data access layer
 * @author siyb
 * @param <int> $uid the uid of the user
 * @param <string> $filter the filter, might be ALL, SELLER or BUYER, caution, this
 * parameter is case sensitive
 * @param <string> $order may be DESC or ASC, please note that this parameter is
 * case sensitive
 * @return <array> containing all results
 */
function lib_bl_market_userOffers($uid, $filter, $order)
{
	return dal\market\userOffers($uid, $filter, $order);
}

/**
 * Calculates the tax of a certain resource. Will take the last 25 closed offers
 * into account.
 * @author siyb
 * @param <string> $resource the resource
 * @param <int> $amount the amount of the resource
 * @return <float> the tax
 */
function lib_bl_market_calculateTax($resource, $amount) {
	$res = dal\market\sales();
	$total = 0;
	foreach ($res as $row)
	{
		$total += $row['amount'];
		if ($row['resource'] == $resource)
			$fragment = $row['amount']; // the amount for the wanted resource
	}

	/* calculate the percentage of the $amount in the total sales of this $resource
	 * in a specific timeframe. Take this percentage and use it on $amount to
	 * calulate the tax that has to be payed
	*/
	$tax = util\math\calcPercentage($total, $fragment);
	if ($tax > 49) $tax = 49;
	return util\math\calcFragment($amount, $tax);
}

/**
 * Just a wrapper for the lib_dal_market_search() function that seperates the
 * display layer from the data access layer
 * @author siyb
 * @author Neithan
 * @global array $lang
 * @global array $user
 * @param <type> $Sresource
 * @param <type> $SvalueRangeStart
 * @param <type> $SvalueRangeEnd
 * @param <type> $Eresource
 * @param <type> $EvalueRangeStart
 * @param <type> $EvalueRangeEnd
 * @param <type> $complete
 * @param <type> $seller
 * @return <type>
 */
function lib_bl_market_search(
	$Sresource, $SvalueRangeStart, $SvalueRangeEnd,
	$Eresource, $EvalueRangeStart, $EvalueRangeEnd,
	$complete, $seller)
{
	global $lang, $user;
	$offers = dal\market\search(
		$Sresource,
		$SvalueRangeStart,
		$SvalueRangeEnd,
		$Eresource,
		$EvalueRangeStart,
		$EvalueRangeEnd,
		$complete,
		$seller
	);

	$result = array();
	foreach ($offers as $offer)
	{
		$result[] = array(
			'mid' => $offer['mid'],
			'seller' => dal\user\uid2nick($offer['sid']),
			'soldResource' => $lang[$offer['s_resource']],
			'soldAmount' => util\math\numberFormat($offer['s_amount'], 0),
			'requestedResource' => $lang[$offer['e_resource']],
			'requestedAmount' => util\math\numberFormat($offer['e_amount'], 0),
			'tax' => util\math\numberFormat($offer['tax'], 0),
			'ownOffer' => ($offer['sid'] == $_SESSION['user']->getUID() ? 1 : 0),
		);
	}

	return $result;
}

/**
 * returns a mapped array with all active offers
 * @author Neithan
 * @global array $lang
 * @global object $user
 * @return array
 */
function lib_bl_market_returnAllOffers()
{
	global $lang, $user;
	$allOffers = dal\market\returnAllOffers();

	$result = array();
	if ($allOffers)
	{
		foreach ($allOffers as $offer)
		{
			$result[] = array(
				'mid' => $offer['mid'],
				'seller' => dal\user\uid2nick($offer['sid']),
				'soldResource' => $lang[$offer['s_resource']],
				'soldAmount' => util\math\numberFormat($offer['s_amount'], 0),
				'requestedResource' => $lang[$offer['e_resource']],
				'requestedAmount' => util\math\numberFormat($offer['e_amount'], 0),
				'tax' => util\math\numberFormat($offer['tax'], 0),
				'ownOffer' => ($offer['sid'] == $_SESSION['user']->getUID() ? 1 : 0),
			);
		}
	}

	return $result;
}

/**
 * Checks if an offer is open
 * @author Neithan
 * @param int $mid
 * @return bool
 */
function lib_bl_market_isOpen($mid)
{
	return dal\market\isOpen($mid) > 0;
}