<?php

/* ------------------------------------------------------------------------------------
 Reset the database in case of data error
 Original code by Matt Nadareski (darksabre76)
 ------------------------------------------------------------------------------------ */

$allreset = true;

$query = "DELETE FROM checksums;
ALTER TABLE checksums AUTO_INCREMENT = 1";

$allreset = $allreset && mysqli_query($link, $query);

$query = "DELETE FROM files;
ALTER TABLE files AUTO_INCREMENT = 1";

$allreset = $allreset && mysqli_query($link, $query);

$query = "DELETE FROM games;
ALTER TABLE games AUTO_INCREMENT = 1";

$allreset = $allreset && mysqli_query($link, $query);

$query = "DELETE FROM parent;
ALTER TABLE parent AUTO_INCREMENT = 1";

$allreset = $allreset && mysqli_query($link, $query);

if ($allreset)
{
	echo "Resetting database succeeded!<br/>\n";
}
else
{
	echo "Resetting database failed!<br/>\n".mysqli_error($link);
}

?>