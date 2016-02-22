<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<title>Wizard! Of! The! DATz!</title>
	<link type="text/css" href="css/_master.css" />
</head>

<?php

include_once("css/style.php");

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
if (!file_exists("temp/imported/"))
{
	mkdir("temp/imported", "0777", true);
}
if (!file_exists("temp/output/"))
{
	mkdir("temp/output", "0777", true);
}

if ($_GET["page"] && file_exists("pages/".str_replace("../", "", htmlspecialchars($_GET["page"])).".php"))
{
	include_once "pages/".$_GET["page"].".php";
}
else
{
	echo "<p>
Welcome to the WoD Revival homepage!
		
Here, the following functions will be implemented:
<ul>
	<li>Viewing system, source, and game data</li>
	<li><a href='?page=generate'>Creating and downloading DATs</a><ul>
		<li>Merged dats based on multiple sources</li>
		<li>Custom dats based on a single source</li>
	</ul></li>
</ul>
</p>
<p><a href='admin/'>Access administrative functions (admin/admin)</a></p>";
}

echo "<a href='?page='>Return to home</a>";

mysqli_close($link);

?>

</html>