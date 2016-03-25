<?php

/* ------------------------------------------------------------------------------------
 Reset the database in case of data error
 Original code by Matt Nadareski (darksabre76)
 ------------------------------------------------------------------------------------ */

$query = "DELETE FROM checksums;
ALTER TABLE checksums AUTO_INCREMENT = 1;

DELETE FROM files;
ALTER TABLE files AUTO_INCREMENT = 1;

DELETE FROM games;
ALTER TABLE games AUTO_INCREMENT = 1;

DELETE FROM parent;
ALTER TABLE parent AUTO_INCREMENT = 1;";

$result = mysqli_query($link, $query);

if (gettype($result) == "boolean" && $result)
{
	echo "Resetting database succeeded!<br/>\n";
}
else
{
	echo "Resetting database failed!<br/>\n".mysqli_error($link);
}

?>