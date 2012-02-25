<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */

namespace bl\clan;

/**
 * @author siyb
 * @param int $uid
 * @param string $resource
 * @param int $amount
 * @return int
 */
function addToBank($uid, $resource, $amount) {
    // check if the user owes enough of the resource
    if (dal\resource\returnResourceAmount($uid, $resource) < $amount)
        return 0;
    //@todo: check if the user is in a clan or not

    dal\clan\addToBank($uid, $resource, $amount);
    return 1;
}

/**
 * List all bank transactions of the clan known by $cid. If $uid != -1, this
 * function will be user specific
 * @author siyb
 * @param int $cid the clanid
 * @param int $uid the userid of the user to be listed
 * @return array containing the transaction data
 */
function listBankTransactions($cid, $uid=-1) {
    if($uid == -1)
        return dal\clan\listBankTransactions($cid);
    else
        return dal\clan\listBankTransactionsPerUser($cid, $uid);
}