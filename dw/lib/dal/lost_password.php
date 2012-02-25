<?php
namespace dal\lostPassword;

/**
 * check for older requests
 * @author Neithan
 * @param int $uid
 * @return int
 */
function checkRecoveries($uid)
{
	$sql = 'SELECT lpid FROM dw_lostpw WHERE uid = '.$uid.'';
	return \util\mysql\query($sql);
}

/**
 * insert a new recovery request
 * @author Neithan
 * @param string $id
 * @param int $time
 * @param int $uid
 * @return int
 */
function insertRecovery($id, $time, $uid)
{
	$sql = '
		INSERT INTO dw_lostpw (
			mailid,
			sent_time,
			uid
		)
		VALUES
		(
			"'.mysql_real_escape_string($id).'",
			NOW(),
			'.$uid.'
		)
	';
	return \util\mysql\query($sql);
}

/**
 * update an existing recovery request
 * @author Neithan
 * @param string $id
 * @param int $time
 * @return int
 */
function updateRecovery($id, $time)
{
	$sql = '
		UPDATE dw_lostpw
		SET mailid = "'.mysql_real_escape_string($id).'",
			sent_time = NOW()
	';
	return \util\mysql\query($sql);
}

/**
 * get all infos of the recovery request
 * @author Neithan
 * @param int $uid
 * @return array
 */
function getRecoveryRequest($uid)
{
	$sql = '
		SELECT * FROM dw_lostpw
		WHERE uid = '.mysql_real_escape_string($uid).'
	';
	return \util\mysql\query($sql);
}

/**
 * delete the recovery request
 * @author Neithan
 * @param int $uid
 * @return int
 */
function removeRecoveryRequest($uid)
{
	$sql = 'DELETE FROM dw_lostpw WHERE uid = '.mysql_real_escape_string($uid).'';
	return \util\mysql\query($sql);
}