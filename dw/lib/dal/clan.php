<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */

/**
 * Adds a positive transaction to the user's banklog
 * @author siyb
 * @param <int> $uid the userid of the user
 * @param <String> $resource the resource, must be food, wood, rock, koku, paper,
 * iron. No check, so make sure to do the checking before sending the query
 * @param <float> $amount the amount of the resource to be added to the bank
 */
function lib_dal_clan_addToBank($uid, $resource, $amount) {
    $escapedUid = mysql_real_escape_string($uid);
    util\mysql\query(
        sprintf(
            "
            INSERT INTO dw_clan_bank
            ( cid, uid, forUid, resource, amount)
            VALUES
            (
                (SELECT cid FROM dw_user WHERE uid='%d'),
                '%d',
                '%d',
                '%s',
                '%f'
            );
            ",
            $escapedUid,
            $escapedUid,
            $escapedUid,
            mysql_real_escape_string($resource),
            mysql_real_escape_string($amount)

        )
    );
}

/**
 * Adds a negative transaction to $forUid's banklog
 * @author siyb
 * @param <int> $cid the clanid
 * @param <int> $uid the userid of the user that makes the transaction
 * @param <int> $forUid the user that the transaction is made for
 * @param <type> $resource the resource of the transaction
 * @param <type> $amount the amount of the resource
 */
function lib_dal_clan_removeFromBank($uid, $forUid, $resource, $amount) {
    $escapedUid = mysql_real_escape_string($uid);
    util\mysql\query(
        sprintf(
            "
            INSERT INTO dw_clan_bank
            ( cid, uid, forUid, resource, amount)
            VALUES
            (
                (SELECT cid FROM dw_user WHERE uid='%d'),
                '%d',
                '%d',
                '%s',
                '%f'
            );
            ",
            $escapedUid,
            $escapedUid,
            mysql_real_escape_string($forUid),
            mysql_real_escape_string($resource),
            mysql_real_escape_string($amount*-1)
        )
    );
}

/**
 * List all bank transactions of the clan known by $cid
 * @param <int> $cid the clanid
 * @return <mysqlresultset> containing the transaction data
 */
function lib_dal_clan_listBankTransactions($cid) {
    return
    util\mysql\query(
        sprintf(
            "
            SELECT * FROM dw_clan_bank
            WHERE cid='%d'
            ",
            mysql_real_escape_string($cid)
        )
    );
}

/**
 * List all bank transactions of the clan known by $cid made by user $uid
 * @author siyb
 * @param <int> $cid the clanid
 * @param <int> $uid the userid of the user to be listed
 * @return <mysqlresultset> containing the transaction data
 */
function lib_dal_clan_listBankTransactionsPerUser($cid, $uid) {
    return
    util\mysql\query(
        sprintf(
            "
            SELECT * FROM dw_clan_bank
            WHERE cid='%d'
            AND uid='%d'
            ",
            mysql_real_escape_string($cid),
            mysql_real_escape_string($uid)
        )
    );
}

/**
 * Returns a mysql resultset that contains the savingsdata for the user with $uid
 * @author siyb
 * @param <int> $uid the userid of the user to be listed
 * @return <mysqlresultset> containing the savings data
 */
function lib_dal_clan_returnSavings($uid) {
    return
    util\mysql\query(
        sprintf(
            "
            SELECT resource, sum(amount) FROM dw_clan_bank
            JOIN dw_clan ON dw_clan_bank.cid = dw_clan.cid
            JOIN dw_user ON dw_clan.cid = dw_user.cid
            WHERE dw_user.uid='%d'
            GROUP BY resource
            ",
            mysql_real_escape_string($uid)
        )
    );
}

/**
 * Returns a mysql resultset containing the following data of all cities of clan
 * $cid: map_x, map_y, uid, cid
 * @author siyb
 * @param <int> $cid the clanid of the clan to be listed
 * @return <mysqlresultset> containing the city data
 */
function lib_dal_clan_returnAllCities($cid) {
    return
    util\mysql\query(
        sprintf(
            "
            SELECT map_x, map_y, dw_map.uid as uid, dw_clan.cid as cid FROM dw_map
            JOIN dw_user ON dw_map.uid = dw_user.uid
            JOIN dw_clan ON dw_user.cid = dw_clan.cid
            WHERE dw_clan.cid = '%d'",
            mysql_real_escape_string($cid)
        )
    );
}

function lib_dal_clan_getAllUser($cid)
{
	$sql = 'SELECT * FROM dw_user WHERE cid = '.mysql_real_escape_string($cid).'';
	return util\mysql\query($sql, true);
}
?>