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
		if (trim($sql))
			echo '$results[] = \util\mysql\query_raw(\''."\n\t".trim(str_replace('\'', '"', $sql))."\n".'\');'."\n";
	echo '</textarea>';
}