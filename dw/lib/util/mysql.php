<?php
namespace util\mysql;

/**
 * handles the MySQL queries
 * if the query is a select, it returns an array if there is only one value, otherwise it returns the value
 * if the query is an update, replace or delete from, it returns the number of affected rows
 * if the query is an insert, it returns the last insert id
 * @author Neithan
 * @param string $sql
 * @param bool $noTransform (default = false) if set to "true" the query function always returns a multidimension array
 * @return array|string|int|float
 */
function query($sql, $noTransform = false, $raw = false)
{
	global $debug, $firePHP_debug, $smarty_debug;

	$mysql = \util\mysql\connect();

	$sql = ltrim($sql);
	if ($debug == true)
	{
		$res = $mysql->query($sql);
	}
	else
	{
		$res = @$mysql->query($sql);
	}

	if (!$res && $debug)
	{
		if ($firePHP_debug && $GLOBALS['firePHP']->getEnabled())
		{
			$GLOBALS['firePHP']->log($mysql->error, 'database error');
			$GLOBALS['firePHP']->trace('database backtrace');
		}
		else
		{
			$backtrace = debug_backtrace();
			$html = '<br />Datenbank Fehler '.$mysql->error.'<br /><br />';
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
				$html = \substr($html, 0, -2);
				$html .= '</td></tr>';
			}
			$html .= '</table>';
			die($html);
		}
	}

	if ($res || is_object($res))
	{
		if (\substr($sql,0,6) == "SELECT" || \substr($sql, 0, 4) == 'SHOW')
		{
			$out = array();

			if ($res->num_rows > 1 || ($noTransform && $res->num_rows > 0))
			{
				if (method_exists('mysqli_result', 'fetch_all'))
					$out = $res->fetch_all(MYSQLI_ASSOC);
				else
					while ($row = $res->fetch_assoc())
						$out[] = $row;
			}
			elseif ($res->num_rows == 1 && !$noTransform)
			{
				$out = $res->fetch_assoc();

				if (count($out) == 1)
					$out = current($out);
			}
			else
				$out = false;

			return $out;
		}

		if (\substr($sql,0,6) == "INSERT" && $noTransform == false)
		    return $mysql->insert_id;
		elseif (\substr($sql,0,6) == "INSERT" && $noTransform == true)
			return $mysql->affected_rows;

		if (\substr($sql,0,6) == "UPDATE")
			return $mysql->affected_rows;

		if (\substr($sql,0,7) == "REPLACE")
			return $mysql->affected_rows;

		if (\substr($sql,0,11) == "DELETE FROM")
			return $mysql->affected_rows;

		if ($raw)
			return $mysql->affected_rows;
	}
	else
		return false;
}

function query_raw($sql)
{
	return \util\mysql\query($sql, false, true);
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
	$mysql = \util\mysql\connect();

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

		$escapedString .= $mysql->real_escape_string($value);

		if ($wrap)
			$escapedString .= '"';

		return $escapedString;
	}
}

/**
 * Handles the MySQL connection.
 * Should only be used in sqlval() and query()
 * @author Neithan
 * @global array $lang
 * @staticvar \mysqli $mysql
 * @return \mysqli
 */
function connect()
{
	global $lang;
	static $mysql;

	if (!is_object($mysql))
	{
		$mysql = new \mysqli($GLOBALS['db']['server'], $GLOBALS['db']['user'], $GLOBALS['db']['password']);

		if ($mysql->connect_error)
		{
			bl\general\loadLanguageFile('errors', 'rest');
			require_once(__DIR__.'/../loggedout/header.php');
			echo $lang['nodb'].'<br /><a href=\"mailto:admin@dynasty-wars.de\">admin@dynasty-wars.de</a>';
			require_once(__DIR__.'/../loggedout/footer.php');
			exit;
		}
		else
		{
			$mysql->set_charset($GLOBALS['db']['charset']);
			$mysql->select_db($GLOBALS['db']['db']);
		}
	}

	return $mysql;
}

/**
 * manager for the migrations
 * @author Neithan
 * @param array $post
 * @return string
 */
function migration_manager($post)
{
	$migration_files_dir = $GLOBALS['config']['migrations_dir'];
	$web_path = $GLOBALS['config']['dir_ws_migrations'];

	$migration_files = array();
	$dh = \opendir($migration_files_dir);
	if (!$dh)
		die('Migration files directory not found.');

	while (($filename = readdir($dh)) !== false)
		if (\substr($filename, -4) == '.php')
			$migration_files[] = $filename;

	\closedir($dh);
	\natsort($migration_files);

	if ($post['initialize'])
	{
		\util\mysql\initialize_migration();
		\bl\general\redirect($web_path.'/../db_migrations/migrations.php');
	}

	if ($post['create_new'])
	{
		$last_migration_number = \substr(end($migration_files), 0, -4);
		file_put_contents($migration_files_dir.'/'.($last_migration_number+1).'.php', "<?php\n\n\$DB_MIGRATION = array(\n\n\t'description' => function () {\n\t\treturn '';\n\t},\n\n\t'up' => function (\$migration_metadata) {\n\n\t\t\$results = array();\n\n\t\t\$results[] = \util\mysql\query_raw('\n\t\t\t\n\t\t');\n\n\t\treturn !in_array(false, \$results);\n\n\t},\n\n\t'down' => function (\$migration_metadata) {\n\n\t\t\$result = \util\mysql\query_raw('\n\t\t\tALTER TABLE tbl CHANGE col col_to_delete TEXT\n\t\t');\n\n\t\treturn !!\$result;\n\n\t}\n\n);");
		\bl\general\redirect($web_path.'/../db_migrations/migrations.php');
	}

	if ($post['apply'] || $post['unapply'])
	{
		foreach ($migration_files as $filename)
		{
			$eligible = false;

			if ($filename == $post['filename'])
				$eligible = true;

			if ($post['next'])
				if (!\util\mysql\is_migration_applied($filename))
					$eligible = true;

			if ($eligible)
			{
				require_once($migration_files_dir.'/'.$filename);

				\set_time_limit(0);

				if ($post['unapply'])
					$result = $DB_MIGRATION['down'](array());
				else
					$result = $DB_MIGRATION['up'](array());

				if ($result === true)
				{
					if ($post['unapply'])
					{
						\util\mysql\mark_migration_unapplied($filename);
						\bl\general\redirect($web_path.'/../db_migrations/migrations.php');
					}
					else
					{
						\util\mysql\mark_migration_applied($filename);
						\bl\general\redirect($web_path.'/../db_migrations/migrations.php');
					}
				}
//				elseif (\is_string($result))
//				{
//					$message = '<div style="font-family: sans-serif; font-size: 13px;">Message from migration file '.\htmlentities($filename).': <b>'.\htmlentities($result).'</b><br><br></div>';
//					$message .= '<a href="'.$web_path.'/../migrations.php">Back</a>';
//
//					if ($post['unapply'])
//					{
//						$message .= '<a href="'.$web_path.'/../migrations.php?'
//					}
//				}
				else
				{
					$message = '<div style="font-family: sans-serif; font-size: 13px;">There has been a problem '.($post['unapply'] ? 'unapplying' : 'applying').' '.\htmlentities($filename).'<br></div>';
					$message .= $result;
					$message .= '<a href="'.$web_path.'/../db_migrations/migrations.php">Back</a>';
					return $message;
				}
			}
		}
	}

	$all_applied = true;
	if (\util\mysql\is_migrations_initialized())
	{
		$message = '
			<style type="text/css">
				table.df_migration_manager { font-family: sans-serif; font-size: 13px; border-spacing: 0px 3px; }
				table.df_migration_manager td { background-color: #EEEEEE; padding: 0px 4px; }
				table.df_migration_manager th { padding: 2px 4px; }
				table.df_migration_manager th { text-align: left; }
				table.df_migration_manager td.df_migration_manager_applied_col { text-align: center; }
				table.df_migration_manager td[colspan] { background-color: inherit; }
			</style>
		';
		$message .= '<table class="df_migration_manager">';
		$message .= '<tr><th>Filename</th><th>Description</th><th>Applied?</th><th></th><th></th></tr>';

		foreach ($migration_files as $filename)
		{
			require_once($migration_files_dir . '/' . $filename);

			$description = $DB_MIGRATION['description']();

			$applied = \util\mysql\is_migration_applied($filename);
			if (!$applied)
				$all_applied = false;

			$message .= '
				<tr class="'.($applied ? 'df_migration_manager_applied' : '').'">
					<td class="df_migration_manager_filename_col">'.\htmlentities($filename).'</td>
					<td class="df_migration_manager_description_col">'.\htmlentities($description).'</td>
					<td class="df_migration_manager_applied_col">'.($applied ? '&#x2714;' : ' ').'</td>
					<td class="df_migration_manager_apply_col">'.(!$applied ? '<a href="'.$web_path.'/../db_migrations/migrations.php?apply=1&amp;filename='.\urlencode($filename).'">' : '').'Apply'.(!$applied ? '</a>' : '').'</td>
					<td class="df_migration_manager_unapply_col">'.($applied ? '<a href="'.$web_path.'/../db_migrations/migrations.php?unapply=1&amp;filename='.\urlencode($filename).'">' : '').'Unapply'.($applied ? '</a>' : '').'</td>
				</tr>
			';
		}

		$message .= '
			<tr><td colspan="5">
				'.(!$all_applied ? '<a href="'.$web_path.'/../db_migrations/migrations.php?next=1&amp;apply=1">' : '').'Apply next'.(!$all_applied ? '</a>' : '').'
				'.(is_writable($migration_files_dir) ? '<a href="'.$web_path.'/../db_migrations/migrations.php?create_new=1">' : '').'Create new'.(is_writable($migration_files_dir) ? '</a>' : '').'
			</td></tr>
		';
		$message .= '</table>';

		return $message;
	}
	else
		\util\mysql\initialize_migration();
}

/**
 * check if the migrations have been initialized
 * @author Neithan
 * @return boolean
 */
function is_migrations_initialized()
{
	$sql = 'SHOW TABLES LIKE "db_migrations"';
	return !!\util\mysql\query($sql);
}

/**
 * initialize the migrations
 * @author Neithan
 */
function initialize_migration()
{
	$sql = '
		CREATE TABLE `db_migrations` (
			`filename` VARCHAR(255) NOT NULL COLLATE "utf8_general_ci",
			`status` ENUM("unapplied","applied") NOT NULL COLLATE "utf8_general_ci",
			PRIMARY KEY (`filename`)
		)
		COLLATE="utf8_general_ci"
	';
	\util\mysql\query($sql);
}

/**
 * check if the migration has been applied
 * @author Neithan
 * @param string $filename
 * @return boolean
 */
function is_migration_applied($filename)
{
	$sql = '
		SELECT IF (`status` = "applied", 1, 0)
		FROM `db_migrations`
		WHERE `filename` = '.\util\mysql\sqlval($filename).'
	';
	return !!\util\mysql\query($sql);
}

/**
 * mark the migration as applied
 * @author Neithan
 * @param string $filename
 */
function mark_migration_applied($filename)
{
	$sql = '
		INSERT INTO `db_migrations`
		SET `filename` = '.\util\mysql\sqlval($filename).',
			`status` = "applied"
		ON DUPLICATE KEY UPDATE `status` = VALUES(status)
	';
	\util\mysql\query($sql);
}

/**
 * mark the migration as unapplied
 * @author Neithan
 * @param string $filename
 */
function mark_migration_unapplied($filename)
{
	$sql = '
		UPDATE `db_migrations`
		SET `status` = "unapplied"
		WHERE `filename` = '.\util\mysql\sqlval($filename).'
	';
	\util\mysql\query($sql);
}