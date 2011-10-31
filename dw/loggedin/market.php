<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */
include('lib/bl/market.inc.php');
include('loggedin/header.php');

lib_bl_general_loadLanguageFile('market');
$smarty->assign('lang', $lang);

if (!$_GET['sub'] || $_GET['sub'] == 'offers')
{
	// place on market
	if ($_POST['type'] == 'offer_create' && isset($_POST['s_amount']) && isset($_POST['e_amount']))
	{
		if (is_numeric($_POST['s_amount']) and is_numeric($_POST['e_amount']))
		{
			if ($_POST['s_amount'] < 0)
				$_POST['s_amount'] = $_POST['s_amount']*-1;

			if ($_POST['e_amount'] < 0)
				$_POST['e_amount'] = $_POST['e_amount']*-1;

			// important: check for negative numbers!!!
			$res = lib_bl_market_placeResourceOnMarket(
				$_SESSION['user']->getUID(),
				$_POST['s_resource'],
				$_POST['s_amount'],
				$_POST['e_resource'],
				$_POST['e_amount'],
				lib_bl_market_calculateTax($_POST['s_resource'], $_POST['s_amount']),
				$city
			);
			if ($res)
				$placeorder = 1;
			else
				$placeorder = 2;
		}
	}
	// anul
	if (isset($_GET['action']) && $_GET['action'] == 'anul')
	{
		$res = lib_bl_market_anull($_SESSION['user']->getUID(), $_GET['mid'], $city);
		if ($res == 0)
			$anulled = 1;
		elseif ($res == -1)
			$anulled = 2;
		else
			$anulled = 3;
		lib_bl_general_redirect('index.php?chose=market&sub=offers&anulled='.$anulled);
	}

	if ($_GET['anulled'])
		$anulled = $_GET['anulled'];

	// buy
	if (isset($_GET['action']) && $_GET['action'] == 'buy')
	{
		$res = lib_bl_market_buy($_SESSION['user']->getUID(), $_GET['mid'], $city);
		switch ($res)
		{
			case 0:
				$buyed = 1;
				break;
			case -1:
				$buyed = 2;
				break;
			case -2:
				$buyed = 3;
				break;
			default:
				$buyed = 4;
		}
	}

	$smarty->assign('resourceList', array(
		'food' => $lang['food'],
		'wood' => $lang['wood'],
		'rock' => $lang['rock'],
		'iron' => $lang['iron'],
		'paper' => $lang['paper'],
		'koku' => $lang['koku'],
	));
	$smarty->assign('resourceListSearch', array(
		'-' => '-',
		'food' => $lang['food'],
		'wood' => $lang['wood'],
		'rock' => $lang['rock'],
		'iron' => $lang['iron'],
		'paper' => $lang['paper'],
		'koku' => $lang['koku'],
	));

	if ($placeorder == 1)
		$message = $lang['done'];
	elseif ($placeorder == 2)
		$message = $lang['no_res'];

	if ($anulled xor $buyed)
	{
		if ($anulled == 1)
			$message = $lang['not_yours'];
		elseif ($anulled == 2 xor $buyed == 2)
			$message = $lang['not_open'];
		elseif ($anulled == 3)
			$message = $lang['anulled'];
		elseif ($buyed == 1)
			$message = $lang['no_buy'];
		elseif ($buyed == 3)
			$message = $lang['no_requested'];
		elseif ($buyed == 4)
			$message = $lang['buyed'];
	}

	$smarty->assign('resultMessage', $message);
	$offerArray = lib_bl_market_returnAllOffers();

	if ($_GET['type'] == 'search')
	{
		$s_res = $_GET['search_s_resource'];
		$s_rs = $_GET['search_s_rangestart'];
		$s_re = $_GET['search_s_rangeend'];
		$e_res = $_GET['search_e_resource'];
		$e_rs = $_GET['search_e_rangestart'];
		$e_re = $_GET['search_e_rangeend'];
		$seller = $_GET['search_seller'];
		$complete = $_GET['search_complete'];
// setting defaults if user choose not to fill out a field
		if ($s_res == '-') $s_res = '%';
		if ($s_rs == '') $s_rs = '0';
		if ($s_re == '') $s_re = '200000000';
		if ($e_res == '-') $e_res = '%';
		if ($e_rs == '') $e_rs = '0';
		if ($e_re == '') $e_re = '200000000';
		if ($seller == '') $seller = '%';
		if (!$complete) $complete = 0;
// start searching, something is wrong here !!! (empty result)
		$offerArray = lib_bl_market_search($s_res, $s_rs, $s_re, $e_res, $e_rs, $e_re, $complete, $seller);
	}

	$smarty->assign('offersArray', $offerArray);
	$smarty->assign('buy', $lang['buy']);
	$smarty->assign('anull', $lang['anull']);

}
elseif ($_GET['sub'] == 'log')
{
	if (!isset($_POST['filter']))
		$_POST['filter'] = 'ALL';
	if (!isset($_POST['order']))
		$_POST['filter'] = 'DESC';

	$smarty->assign('filterArray', array(
		'ALL' => $lang['all'],
		'SELLER' => $lang['seller'],
		'BUYER' => $lang['buyer'],
	));
	$smarty->assign('sortArray', array(
		'DESC' => $lang['desc'],
		'ASC' => $lang['asc'],
	));

	$offers = lib_bl_market_userOffers($_SESSION['user']->getUID(), $_POST['filter'], $_POST['order']);
	$offersArray = array();

	if ($offers)
	{
		foreach ($offers as $offer)
		{
			$class = 'table_tc';
			if ($offer['sid'] == $_SESSION['user']->getUID() and $offer['bid'] == $_SESSION['user']->getUID())
				$class = 'anulled';
			else
			{
				if ($offer['sid'] == $_SESSION['user']->getUID() and !lib_dal_market_isOpen($offer['mid']))
					$class = 'sold';
				elseif ($offer['bid'] == 1)
					$class = 'buyed';
				else
					$class = 'not_sold';
			}

			$offersArray[] = array(
				'class' => $class,
				'title' => $lang['item_'.$class],
				'seller' => lib_dal_user_uid2nick($offer['sid']),
				'buyer' => ($offer['bid'] ? lib_dal_user_uid2nick($offer['bid']) : 'N/A'),
				'offer' => $lang[$offer['s_resource']],
				'offer_amount' => lib_util_math_numberFormat($offer['s_amount'], 0),
				'request' => $lang[$offer['e_resource']],
				'request_amount' => lib_util_math_numberFormat($offer['e_amount'], 0),
				'tax' => lib_util_math_numberFormat($offer['tax'], 0),
				'date' => ($offer['timestamp'] == 1 ? 'N/A' : date($lang['timeformat'], $offer['timestampt'])),
			);
		}
	}

	$smarty->assign('offersArray', $offersArray);
}

include('loggedin/footer.php');

$smarty->display('market_list.tpl');