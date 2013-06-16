<form method="post" action="transformToMigrations.php">
	<textarea name="sql" rows="20" cols="80"></textarea>
	<input type="submit" />
</form>
<?php
if ($_POST['sql'])
{
	$sql_snippets = explode(';', $_POST['sql']);

	echo '<textarea rows="20" cols="80">';
	foreach ($sql_snippets as $sql)
	{
		if (trim($sql))
		{
			$sql_expl = explode("\n", trim(str_replace('\'', '"', $sql)));
			$sqlString = '';
			foreach ($sql_expl as $part)
			{
				$part = trim($part);
				if ($part && substr($part, 0, 2) !== '--' && substr($part, 0, 2) !== '/*')
				{
					if (substr($part, 0, 4) !== 'DROP' && substr($part, 0 ,6) !== 'CREATE'
						&& substr($part, 0, 5) !== 'ALTER' && substr($part, 0, 1) !== ')')
						$sqlString .= "\t";
					$sqlString .= "\t\t\t".$part."\n";
				}
			}

			if (!$sqlString)
				continue;

			if (substr($sqlString, 0, 4) === 'DROP')
				echo "\t\t".'\util\mysql\query_raw(\''."\n".$sqlString."\t\t".'\');'."\n";
			else
				echo "\t\t".'$results[] = \util\mysql\query_raw(\''."\n".$sqlString."\t\t".'\');'."\n\n";
		}
	}
	echo '</textarea>';
}