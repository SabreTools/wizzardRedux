<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<title>Wizard! Of! The! DATz! Admin</title>
	<link type="text/css" href="../css/_master.css" />
</head>

<?php

include_once("../css/style.php");

// Check for debug mode and pass it along
$debug = false;
if (isset($_GET["debug"]) && $_GET["debug"]=="1")
{
	$debug = true;
}

// Connect to the database so it doesn't have to be done in every page
$link = mysqli_connect('localhost', 'root', '', 'wod');
if (!$link)
{
	die('Error: Could not connect: ' . mysqli_error($link));
}

echo "Connection established!<br/>\n";

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
So, this is where all admin functions will be linked from. Currently, that page is /pages/admin.php for testability.<br/>
This will include:
<ol>
	<li>Add/Edit/Remove a system</li>
	<li>Add/Edit/Remove a source</li>
	<li>Add/Edit/Remove a parent</li>
	<li>Add/Edit/Remove a game</li>
	<li>Add/Edit/Remove a file</li>
	<li><a href='?page=import'>Bulk add from DAT</a></li>
	<li><a href='?page=onlinecheck'>Check for new files online</a></li>
	<li><a href='?page=getmamenames'>Generate an array of names for all MAME softlists</a></li>
	<li><a href='?page=parsenointro'>Generate an array of names for all No-Intro sources</a></li>
</ol>";
}

echo "<a href='../index.php'>Return to home</a>";

mysqli_close($link);

?>

</html>