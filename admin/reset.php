<?php

/* ------------------------------------------------------------------------------------
 Reset the database in case of data error
 Original code by Matt Nadareski (darksabre76)
 ------------------------------------------------------------------------------------ */

$allreset = true;

// Reset checksums table
$query = "DELETE FROM checksums";
$allreset = $allreset && mysqli_query($link, $query);
$query = "ALTER TABLE checksums AUTO_INCREMENT = 1";
$allreset = $allreset && mysqli_query($link, $query);

// Reset files table
$query = "DELETE FROM files";
$allreset = $allreset && mysqli_query($link, $query);
$query = "ALTER TABLE files AUTO_INCREMENT = 1";
$allreset = $allreset && mysqli_query($link, $query);

// Reset games table
$query = "DELETE FROM games";
$allreset = $allreset && mysqli_query($link, $query);
$query = "ALTER TABLE games AUTO_INCREMENT = 1";
$allreset = $allreset && mysqli_query($link, $query);

// Reset parent table
$query = "DELETE FROM parent";
$allreset = $allreset && mysqli_query($link, $query);
$query = "ALTER TABLE parent AUTO_INCREMENT = 1";
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