<?php

/*
Check for new downloadable ROMs from all available sites

Requires:
	source		The sourcename to check against (in sites/<source>.php)

TODO: Retool existing onlinecheck.php files to follow the new format
		2 passes: 1) reformat file and categorize, 2) check code flow to try to optimize
TODO: Add a way to figure out if a site is dead based on the original list that WoD created
TODO: Most explode/implode can probably be changed to preg_match, just need to decipher them
TODO: For page read, can we use the function in parsenointro (the cURL one)?
TODO: Addendum, page reads done by "getHTML" can probably be replaced by this too
TODO: Remember to replace GLOBALS GET and POST with the proper $_GET, $_POST
TODO: Document all required GET and POST vars for each page
TODO: Direct connect to EAB with FTP (ftp:any@ftp.grandis.nu)
TODO: Comment all of the code...
TODO: Remove external and import only
TODO: Merge MESS and MAME in sources
TODO: Some loadDir functions are useless because they are only used once. Put their code where it should be
TODO: Maybe look at NES-CartDatabase for No-Intro parsing
*/

// Site whose checkers have been once-overed (not all checked for dead)
// TODO: make the mapping to the base url?
$checked = array (
		"6502dude",
		"8BitChip",						// Probably dead
		"8BitCommodoreItalia",			// Probably dead
		"AcornPreservation",
		"ADVAnsCEne",					// Import only
		"alexvampire",
		"AmstradESP",
		"ANN",
		"Apple2Online",
		"AppleIIgsInfo",
		"Arise64",
		"AtariAge",
		"Atarimania",
		"AtariOnline",
		"BananaRepublic",
		"bjars",
		"BrutalDeluxeSoftware",
		"c16de",
		"C64ch",
		"c64com",
		"c64gamescom",					// Empty checker page?
		"c64gamesde",
		"C64Heaven",
		"C64intros",
		"c64rulez",
		"C64Tapes",
		"C64Warez",
		"CaH4e3",
		"Cas2Rom",
		"CasArchive",
		"computeremuzone",
		"CPC-Crackers",
		"CPC-GameReviews",
		"CPC-Power",					// "full" is no longer active
		"CPC-Rulez",
		"CrackersVelus",
		"csdb",
		"DC",
		"Demotopia",
		"DigitalDream",
		"DigitalDungeon",
		"DuncanTwain",					// Import only
		"EAB",
		"Edicolac64",
		"EludeVisibility",
		"ep128hu",
		"Fandal",
		"Gamebase64",
		"good",							// Import only
		"GratisSaugen",
		"hackedroms",
		"heranbago",
		"HHUG",
		"i-mockery",
		"Import64",						// Empty checker page?
		"Kamming",
		"karpez",
		"Konamito",
		"m3Zz",
		"magicrip",
		"MagyarC64HQ",
		"MAME",							// Import only (softlists)
		"manosoft",
		"Mapy",
		"Marcos64",
		"Maybe-Intro",					// Import only
		"MCBremakes",
		"MSXbasic",
		"MSXcassettes",
		"MyVG5000",
		"NanoWasp",
		"NES-CartDatabase",
		"newgame",
		"NewRom",
		"NintendoPlayer",
		"no-intro",						// Import only
		"nonbetagood",					// Import only
		"nongood",						// Import only
		"Panprase",
		"PiratedGameCenter",
		"PokemonGBAroms",
		"PokemonMiniNet",
		"pokeysoft",
		"redump",						// Import only
		"Retro64Games",
		"RetroPrograms",
		"RH",
		"rufnoiz",
		"russianroms",
		"SacNewsNet",
		"SatellaBlog",
		"smartlip",
		"SMS-Power",
);

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
			echo "<a href=\"?page=onlinecheck&source=".$file."\">".htmlspecialchars($file)."</a><br/>";
		}
	}

	echo "<br/><a href='".$path_to_root."/index.php'>Return to home</a>";

	die();
}
elseif (!file_exists("../sites/".$_GET["source"].".php"))
{
	echo "<b>The file you supply must be in /sites/</b><br/>";
	echo "<a href='index.php'>Return to home</a>";

	die();
}

$source = $_GET["source"];

if (in_array($source, $checked))
{
	echo "<h2>Loading pages and links...</h2>";
	
	$r_query = implode('', file("../sites/".$source.".txt"));
	$r_query = explode("\r\n", $r_query);
	$r_query = array_flip($r_query);
	
	$found = array();
	$base_dl_url = "";

	// Original code: The Wizard of DATz
	include_once("../sites/".$source.".php");

	// Not currently properly used by all
	echo "<h2>New files:</h2>";
	
	foreach ($found as $row)
	{
		echo htmlspecialchars($row)."<br/>";
		echo "<a href='".$base_dl_url.$row[0]."'>".$row[0]."</a><br/>";
	}
}

?>