<?php 

/* ------------------------------------------------------------------------------------
 Wrap all administrative functions in a single password-locked page
 Original code by Matt Nadareski (darksabre76)

 Requires:
 page		The page name minus the extension from the admin folder

 TODO: Figure out some way to lock certain functions.
 			e.g. Make sure that only one person at a time can do edit/import.
 			Otherwise, issues can appear where adding a row can get dereferenced
 ------------------------------------------------------------------------------------ */

?>

<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<title>Wizard! Of! The! DATz! Admin</title>
	<link type="text/css" href="../css/_master.css" />
</head>

<?php

include_once("../css/style.php");
include_once("../includes/remapping.php");
include_once("../includes/functions.php");

// Connect to the database so it doesn't have to be done in every page
$link = mysqli_connect('localhost', 'root', '', 'wod');
if (!$link)
{
	die('Error: Could not connect: ' . mysqli_error($link));
}

//echo "Connection established!<br/>\n";

if (isset($_GET["page"]) && file_exists(str_replace("../", "", htmlspecialchars($_GET["page"])).".php"))
{
	include_once $_GET["page"].".php";
}
else
{
	echo "<p>
Administrative Functions Homepage
<ol>
	<li><a href='?page=edit'>Add/Edit/Remove a system/source/game/file</a></li>
	<li><a href='parenting/index.php'>Add/Edit/Remove a parent</a></li>
	<li><a href='?page=import'>Bulk add from DAT</a></li>
	<li><a href='../?page=generate&auto=1'>Create all available DATs</a></li>
	<li><a href='?page=onlinecheck'>Check for new files online</a></li>
 	<li><a href='?page=deheader'>Deheader files in a local (server) folder</a></li>
	<li><a href='?page=parsenointro'>Generate an array of names for all No-Intro sources</a></li>
	<li><a href='?page=clean'>Clean the database of dupes and orphans (CAUTION! CANNOT BE UNDONE!)</a></li>
</ol>";
}

echo "<a href='../index.php'>Return to home</a>";

mysqli_close($link);

?>

</html>