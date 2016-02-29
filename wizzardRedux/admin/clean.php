<?php

/*
 Clean duplicates and orphaned checksums from the database
 */

//http://stackoverflow.com/questions/18932/how-can-i-remove-duplicate-rows

// Delete dupes from games
$query = "DELETE games 
			FROM games LEFT OUTER JOIN (
				SELECT MIN(id) as id, system, name, parent, source
				FROM games
				GROUP BY system, name, parent, source
				) as KeepRows ON
			games.id = KeepRows.id
			WHERE KeepRows.id IS NULL";
$result = mysqli_query($link, $query);

if (gettype($result) == "boolean" && $result)
{
	echo "Deleting game dupes succeeded!<br/>\n";
}
else
{
	echo "Deleting game dupes failed!<br/>\n".mysqli_error($link);
}
			
// Delete dupes from files
$query = "DELETE files
			FROM files LEFT OUTER JOIN (
				SELECT MIN(id) as id, setid, name, type, lastupdated
				FROM files
				GROUP BY setid, name, type, lastupdated
				) as KeepRows ON
			files.id = KeepRows.id
			WHERE KeepRows.id IS NULL";
if (gettype($result) == "boolean" && $result)
{
	echo "Deleting file dupes succeeded!<br/>\n";
}
else
{
	echo "Deleting file dupes failed!<br/>\n".mysqli_error($link);
}

// Delete orphaned checksums
$query = "DELETE FROM checksums
			WHERE NOT EXISTS (
				SELECT *
				FROM files
				WHERE checksums.file = files.id)";

$query = "SELECT * FROM sources";
$result = mysqli_query($link, $query);
if (gettype($result) == "boolean" && $result)
{
	echo "Deleting checksum orphans succeeded!<br/>\n";
}
else
{
	echo "Deleting checksum orphans failed!<br/>\n".mysqli_error($link);
}

?>