<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */

/**
 * @author siyb
 * @param int $uid
 * @param string $resource
 * @param int $amount
 * @return <int>
 */
function lib_bl_clan_addToBank($uid, $resource, $amount) {
    // check if the user owes enough of the resource
    if (lib_dal_resource_returnResourceAmount($uid, $resource) < $amount)
        return 0;
    //@todo: check if the user is in a clan or not

    lib_dal_clan_addToBank($uid, $resource, $amount);
    return 1;
}

/**
 * List all bank transactions of the clan known by $cid. If $uid != -1, this
 * function will be user specific
 * @author siyb
 * @param <int> $cid the clanid
 * @param <int> $uid the userid of the user to be listed
 * @return <mysqlresultset> containing the transaction data
 */
function lib_bl_clan_listBankTransactions($cid, $uid=-1) {
    if($uid == -1)
        return lib_dal_clan_listBankTransactions($cid);
    else
        return lib_dal_clan_listBankTransactionsPerUser($cid, $uid);
}
?>
