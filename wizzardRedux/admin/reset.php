<?php

/* ------------------------------------------------------------------------------------
 Reset the database in case of data error
 ------------------------------------------------------------------------------------ */

$query = "DELETE FROM checksums;
ALTER TABLE checksums AUTO_INCREMENT = 1;

DELETE FROM files;
ALTER TABLE files AUTO_INCREMENT = 1;

DELETE FROM games;
ALTER TABLE games AUTO_INCREMENT = 1;

DELETE FROM parent;
ALTER TABLE parent AUTO_INCREMENT = 1;";

echo "<a href=\"?page=reset&confirm=1\">Are you sure you want to delete the data?/a>";

if (isset($_GET["confirm"]) && $_GET["confirm"] == 1)
{
	$result = mysqli_query($link, $query);
	
	if (gettype($result) == "boolean" && $result)
	{
		echo "Resetting database succeeded!<br/>\n";
	}
	else
	{
		echo "Resetting database failed!<br/>\n".mysqli_error($link);
	}
}

?>