<?php
namespace util\mysql;

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
function query($sql, $noTransform = false)
{
	global $con, $debug, $firePHP_debug, $smarty_debug;

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
			if (mysql_num_rows($res) > 1 || ($noTransform && mysql_num_rows($res) > 0))
			{
				while($line = mysql_fetch_array($res,MYSQL_ASSOC))
					$out[] = $line;
			}
			elseif (mysql_num_rows($res) == 1 && !$noTransform)
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
function transactionBegin()
{
    query("BEGIN");
}

/**
 * Save Changes on Database
 * @author BlackIce
 */
function transactionCommit()
{
    query("COMMIT");
}

/**
 * Rollback Changes
 * @author BlackIce
 */
function transactionRollback()
{
    query("ROLLBACK");
}

/**
 * Escapes and wraps the given value. If it's an array, all elements will be
 * escaped separately
 * @param mixed $value
 * @return String
 */
function sqlval($value, $wrap = true)
{
	if (is_array($value))
	{
		foreach ($value as &$row)
			$row = sqlval($row, $wrap);
		unset($row);

		return $value;
	}
	else
	{
		$escapedString = '';

		if ($wrap)
			$escapedString .= '"';

		$escapedString .= mysql_real_escape_string($value);

		if ($wrap)
			$escapedString .= '"';

		return $escapedString;
	}
}