<?php

/* ------------------------------------------------------------------------------------
Check for new downloadable ROMs from all available sites
Original code by Matt Nadareski (darksabre76), based on code written by The Wizard of DATz

Requires:
	source		The sourcename to check against (in sites/<source>.php)
	
Notes:
	Below are a list of sites with things of note about them:
	- AtariOnline				No idea what "compare" block does
	- c64gamescom				Empty checker page
	- C64Heaven					No idea how to get to the archive, only few files on home page
	- C64Warez					Needs registration; possible cookie usage
	- CPC-Power					"full" is no longer active
	- Import64					Empty checker page
	- NES-CartDatabase			Needs more testing
	- PiratedGameCenter			Not technically dead, just all posts gone
	- SpecialProgramSipe		Goes through MEGA now, not via the site itself
	- ssrg						Needs special attention because of possible login
	- Tiddles					Output needs verification
	- vic20it					Needs special attention because of possible login
	- VideopacNL				Need to test cookie usage
	- VimmsLair					See download link todo
	- Vizzed					Possible cookie usage

TODO: Retool existing onlinecheck.php files to follow the new format. 3) check code flow to try to optimize
TODO: VideopacNL uses a cookie to be able to access the board. This means you need to log in to the site and then copy the cookie as a param
TODO: Can we run all online checks in a coherent way (in series, that is)?
TODO: Once all checkers are certainly using the new table and found format, standardize $found and move to this file
TODO: Last updated - CPC-Crackers
------------------------------------------------------------------------------------ */

ini_set('max_execution_time', 0); // Set the execution time to infinite. This is a bad idea in production.

// Populate list if sources
$query = "SELECT name FROM sources";
$result = mysqli_query($link, $query);
$result = mysqli_fetch_all($result);
$sites = array();
foreach ($result as $item)
{
	$sites[] = array_shift($item);
}

// Sites that are dead
$dead = array(
		"8BitChip",
		"8BitCommodoreItalia",
		"AcornPreservation",
		"Atarimania",
		"c64gamescom",
		"Cas2Rom",
		"Import64",
		"Konamito",
		"m3Zz",
		"PiratedGameCenter",
		"PokemonGBAroms",
		"smartlip",
);

// If we don't have a source set, show the generic page
if (!isset($_GET["source"]))
{
	echo "<h2>Please Choose a Site</h2>\n";
	
	// List all files, auto-generate links to proper pages
	$files = scandir("../sites/", SCANDIR_SORT_NONE);
	foreach ($files as $file)
	{
		if (preg_match("/^.*\.php$/", $file))
		{
			$file = substr($file, 0, sizeof($file) - 5);
			echo "<a href=\"?page=onlinecheck&source=".$file."\">".htmlspecialchars($file).
				"</a>".(in_array($file, $dead) ? " (Dead)" : "")."<br/>\n";
		}
	}
	
	// List all sources that don't have checkers
	echo "<h2>Sites With No Checker</h2>\n";
	
	// Normalize the arrays because some names don't match 1:1
	$newsites = array();
	foreach ($sites as $site)
	{
		$newsites[] = strtolower($site);
	}
	$newfiles = array();
	foreach ($files as $file)
	{
		$newfiles[] = strtolower($file);
	}
	$newfiles = str_replace(".php", "", $newfiles);
	
	// Print out the source names with proper capitalization
	foreach (array_diff($newsites, $newfiles) as $key => $site)
	{
		echo $sites[$key]."<br/>\n";
	}

	echo "<br/><a href='".$path_to_root."/index.php'>Return to home</a>";

	die();
}
/// If the checker requested doesn't exist, tell the user
elseif (!file_exists("../sites/".$_GET["source"].".php"))
{
	echo "<b>The file you supply must be in /sites/</b><br/>";
	echo "<a href='index.php'>Return to home</a>";

	die();
}

// Otherwise, we assume the soruce is good, and we attempt to load it
$source = $_GET["source"];

echo "<h2>Loading pages and links...</h2>";

$r_query = implode('', file("../sites/".$source.".txt"));
$r_query = explode("\r\n", $r_query);
$r_query = array_flip($r_query);

$found = array();

// Original code: The Wizard of DATz
include_once("../sites/".$source.".php");

?>