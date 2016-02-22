<?php

/*
Check for new downloadable ROMs from all available sites

Requires:
	source		The sourcename to check against

Note: This is the most tedious one of all. All of the checks should be named as "sites/<sitename>.php".

TODO: Retool existing onlinecheck.php files to follow the new format
TODO: Add a way to figure out if a site is dead based on the original list that WoD created
*/

// Site whose checkers have been once-overed
$checked = array (
		"6502dude" => 1,
		"8BitChip" => 2,
		"8BitCommodoreItalia" => 3,
		"AcornPreservation" => 4,
		"ADVAnsCEne" => 5,
		"alexvampire" => 6,
);

// Probably dead sites
$dead = array (
		"8BitChip" => 1,
		"8BitCommodoreItalia" => 2,
);

// Sites that are purely external
$external = array (
		"ADVAnsCEne" => 1,
);

if (!isset($_GET["source"]))
{
	// List all files, auto-generate links to proper pages
	$files = scandir("../sites/");
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
elseif (!file_exists("../sites/".$_GET["source"].".php"))
{
	echo "<b>The file you supply must be in /wod/sites/</b><br/>";
	echo "<a href='index.php'>Return to home</a>";

	die();
}

$source = $_GET["source"];

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
include_once("../sites/".$source.".php");

if (array_key_exists($source, $checked))
{
	echo "<h2>New files:</h2>";
	
	foreach ($found as $row)
	{
		echo htmlspecialchars($row)."<br/>";
		echo "<a href='".$base_dl_url.$row[0]."'>".$row[0]."</a><br/>";
	}
}

?>