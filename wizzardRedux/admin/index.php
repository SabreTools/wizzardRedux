<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<title>Wizard! Of! The! DATz! Admin</title>
	<link type="text/css" href="../css/_master.css" />
</head>

<?php

include_once("../css/style.php");
include_once("../includes/remapping.php");

// Connect to the database so it doesn't have to be done in every page
$link = mysqli_connect('localhost', 'root', '', 'wod');
if (!$link)
{
	die('Error: Could not connect: ' . mysqli_error($link));
}

//echo "Connection established!<br/>\n";

// Ensure the temp folder exists with the right subfolders
if (!file_exists("../temp/imported/"))
{
	mkdir("../temp/imported", "0777", true);
}
if (!file_exists("../temp/output/"))
{
	mkdir("../temp/output", "0777", true);
}

if ($_GET["page"] && file_exists(str_replace("../", "", htmlspecialchars($_GET["page"])).".php"))
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
	<li><a href='?page=onlinecheck'>Check for new files online</a></li>
	<li><a href='?page=getmamenames'>Generate an array of names for all MAME softlists</a></li>
	<li><a href='?page=parsenointro'>Generate an array of names for all No-Intro sources</a></li>
	<li><a href='?page=clean'>Clean the database of dupes and orphans</a></li>
</ol>";
}

echo "<a href='../index.php'>Return to home</a>";

mysqli_close($link);

?>

</html>