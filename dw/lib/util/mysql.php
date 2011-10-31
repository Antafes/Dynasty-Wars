<?php
/*
 *
 * Licensed under GPL2
 * Copyleft by siyb (siyb@geekosphere.org)
 *
 */

/**
 * Creates a 2 dimensional array containing multiple rows of a resultset
 * @author siyb
 * @param resource $res
 * @return array
 */
function lib_util_mysql_data2array($res)
{
    $i = 0;
    while ($row = mysql_fetch_array($res))
    {
        $retArray[$i] = $row;
        $i++;
    }
    return $retArray;
}


/**
 * handles the mysql_query
 * if the query is a select, it returns an array if there is only one value, otherwise it returns the value
 * if the query is an update, replace or delete from, it returns the number of affected rows
 * if the query is an insert, it returns the last insert id
 * @author Neithan
 * @param string $sql
 * @param bool $noTransform (default = false) if set to "true" the query function always returns a multidimension array
 * @return array|string|int|float
 */
function lib_util_mysqlQuery($sql, $noTransform = false)
{
	global $con, $debug;
	$sql = ltrim($sql);
	if ($debug == true)
		$res = mysql_query($sql, $con);
	else
		$res = @mysql_query($sql, $con);
	if (!$res && $debug)
	{
		if ($firePHP_debug && $GLOBALS['firePHP']->getEnabled())
		{
			$GLOBALS['firePHP']->log(mysql_error(), 'database error');
			$GLOBALS['firePHP']->trace('database backtrace');
		}
		else
		{
			$backtrace = debug_backtrace();
			$html = '<br />Datenbank Fehler '.mysql_error().'<br /><br />';
			$html .= $sql.'<br />';
			$html .= '<table>';
			foreach ($backtrace as $part)
			{
				$html .= '<tr><td width="100">';
				$html .= 'File: </td><td>'.$part['file'];
				$html .= ' in line '.$part['line'];
				$html .= '</td></tr><tr><td>';
				$html .= 'Function: </td><td>'.$part['function'];
				$html .= '</td></tr><tr><td>';
				$html .= 'Arguments: </td><td>';
				foreach ($part['args'] as $args)
					$html .= $args.', ';
				$html = substr($html, 0, -2);
				$html .= '</td></tr>';
			}
			$html .= '</table>';
			die($html);
		}
	}

	if ($res)
	{

		if (substr($sql,0,6) == "SELECT")
		{
			$out = array();
			if (mysql_num_rows($res) > 1 or ($noTransform && mysql_num_rows($res) > 0))
			{
				while($line = mysql_fetch_array($res,MYSQL_ASSOC))
					$out[] = $line;
			}
			elseif (mysql_num_rows($res) == 1 and !$noTransform)
			{
				$out = mysql_fetch_array($res,MYSQL_ASSOC);
				if (count($out) == 1)
					$out = current($out);
			}
			else
				$out = false;
			return $out;
		}

		if (substr($sql,0,6) == "INSERT" and $noTransform == false)
		    return mysql_insert_id($con);
		elseif (substr($sql,0,6) == "INSERT" and $noTransform == true)
			return mysql_affected_rows($con);

		if (substr($sql,0,6) == "UPDATE")
			return mysql_affected_rows($con);

		if (substr($sql,0,7) == "REPLACE")
			return mysql_affected_rows($con);

		if (substr($sql,0,11) == "DELETE FROM")
			return mysql_affected_rows($con);
	}
	else
		return false;
}
/**
 * Let the Transaction begin
 * @author BlackIce
 */
function lib_util_mysql_transactionBegin()
{
    lib_util_mysqlQuery("BEGIN");
}
/**
 * Save Changes on Database
 * @author BlackIce
 */
function lib_util_mysql_transactionCommit()
{
    lib_util_mysqlQuery("COMMIT");
}
/**
 * Rollback Changes
 * @author BlackIce
 */
function lib_util_mysql_transactionRollback()
{
    lib_util_mysqlQuery("ROLLBACK");
}
?>