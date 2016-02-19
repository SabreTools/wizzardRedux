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

// Ensure the temp folder exists
if (!file_exists("temp/"))
{
	mkdir("temp");
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
	<li><a href=\"?page=admin\">Adding and removing files from the DATs</a></li>
	<li><a href=\"?page=admin\">Adding and removing systems</a></li>
	<li><a href=\"?page=admin\">Adding and removing sources</a></li>
	<li><a href=\"?page=generate\">Creating and downloading DATs</a><ul>
		<li>Merged dats based on multiple sources</li>
		<li>Custom dats based on a single source</li>
	</li>
</ul>
</p>";
}

echo "<a href=\"?page=\">Return to home</a>";

?>

</html>