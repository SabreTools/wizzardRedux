<?php

/*
Check for new downloadable ROMs from all available sites

Requires:
	source		The sourcename to check against

Note: This is the most tedious one of all. All of the checks should be named as "sites/<sitename>.php".

If the page is sent with no param, generate a list of all possible checks. Each check has a
<sitename>.txt file next to it that designates what files have already been found. Use
the existing <sitename>/onlinecheck.php files for either reference or wholesale repurposing.

TODO: Retool existing onlinecheck.php files to follow the new format
TODO: Add a way to figure out if a site is dead based on the original list that WoD created
TODO: Last site actually processed was 8BitChip. All others are direct imports.
*/

if (!isset($_GET["source"]))
{
	// List all files, auto-generate links to proper pages
	$files = scandir("sites/");
	foreach ($files as $file)
	{
		if (preg_match("/^.*\.php$/", $file))
		{
			$file = substr($file, 0, sizeof($file) - 5);
			echo "<a href=\"?page=onlinecheck&source=".$file."&debug=1\">".htmlspecialchars($file)."</a><br/>";
		}
	}

	echo "<br/><a href='".$path_to_root."/index.php'>Return to home</a>";

	die();
}
elseif (!file_exists("sites/".$_GET["source"].".php"))
{
	echo "<b>The file you supply must be in /wod/sites/</b><br/>";
	echo "<a href='index.php'>Return to home</a>";

	die();
}

$source = $_GET["source"];

$checked = array (
		"6502dude" => 1,
		"8BitChip" => 2,
		"8BitCommodoreItalia" => 3,
		"AcornPreservation" => 4,
);

// Do all onlinecheck pages use this?
if (array_key_exists($source, $checked))
{
	echo "<h2>Loading pages and links...</h2>";
	
	$r_query = file("sites/".$source.".txt");
	$r_query = array_flip($r_query);
	
	// There is the case that all keys will contain a whitespace character at the end
	$s_query = Array();
	while (list($k, $v) = each($r_query))
	{
		$s_query[trim($k)] = $r_query[$k];
	}
	$r_query = $s_query;
	unset($s_query);
	
	$found = Array();
	$base_dl_url = "";
}

// If we get to this point, we assume that it's good
include_once("sites/".$source.".php");

if (array_key_exists($source, $checked))
{
	echo "<h2>New files:</h2>";}
	
	foreach ($found as $row)
	{
		echo htmlspecialchars($row);
		echo "<a href='".$base_dl_url.$row[0]."'>".$row[0]."</a><br/>";
	}
}

?>