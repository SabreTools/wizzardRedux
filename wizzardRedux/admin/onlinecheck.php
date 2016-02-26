<?php

/*
Check for new downloadable ROMs from all available sites

Requires:
	source		The sourcename to check against (in sites/<source>.php)

TODO: Retool existing onlinecheck.php files to follow the new format. 2) replace stuff with cURL 3) check code flow to try to optimize
TODO: Add a way to figure out if a site is dead based on the original list that WoD created
TODO: Most explode/implode can probably be changed to preg_match, just need to decipher them
TODO: Remember to replace GLOBALS GET and POST with the proper $_GET, $_POST
TODO: Document all required GET and POST vars for each page
TODO: Direct connect to EAB with FTP (ftp:any@ftp.grandis.nu)
TODO: Comment all of the code...
TODO: Merge MESS and MAME in sources
TODO: Some loadDir functions are useless because they are only used once. Put their code where it should be
TODO: Maybe look at NES-CartDatabase for No-Intro parsing
TODO: VideopacNL uses a cookie to be able to access the board. This means you need to log in to the site and then copy the cookie as a param
TODO: VimmsLair uses wget.exe currently. Can this be reamped to use cURL instead (since it's built into PHP)?
*/

// Site whose checkers have been once-overed (not all checked for dead)
$checked = array (
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
		"EAB",
		"Edicolac64",
		"EludeVisibility",
		"ep128hu",
		"Fandal",
		"Gamebase64",
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
		"manosoft",
		"Mapy",
		"Marcos64",
		"MCBremakes",
		"MSXbasic",
		"MSXcassettes",
		"MyVG5000",
		"NanoWasp",
		"NES-CartDatabase",
		"newgame",
		"NewRom",
		"NintendoPlayer",
		"Panprase",
		"PiratedGameCenter",
		"PokemonGBAroms",
		"PokemonMiniNet",
		"pokeysoft",
		"Retro64Games",
		"RetroPrograms",
		"RH",
		"rufnoiz",
		"russianroms",
		"SacNewsNet",
		"SatellaBlog",
		"smartlip",
		"SMS-Power",
		"soniccenter",
		"sonicretro",
		"SpecialProgramSipe",
		"spectrum4ever",
		"ssrg",
		"Stadium64",
		"StairwayToHell",
		"Symlink",
		"TapProject",
		"Tiddles",
		"tomcat",
		"TRS80CoCoArchive",
		"TZXvault",
		"UltimateC64TP",
		"UnofficialCD32Ports",
		"vgdb",
		"vic20it",
		"VideopacNL",
		"VimmsLair",
		"VirtualTR-DOS",
		"Vizzed",
		"Vjetnam",
		"webbedspace",
		"WhatIsThe2gs",
		"WinWorld",
		"WorldOfDragon",
		"WorldOfSpectrum",
		"z80ne",
		"zxAAA",
);

// Sites that have been given a second look over (not all sites checked for dead)
$fixed = array(
		"6502dude",
		"8BitChip",						// Probably dead
		"8BitCommodoreItalia",			// Probably dead
		"AcornPreservation",
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

if (in_array($source, $checked) || in_array($source, $fixed))
{
	echo "<h2>Loading pages and links...</h2>";
	
	$r_query = implode('', file("../sites/".$source.".txt"));
	$r_query = explode("\r\n", $r_query);
	$r_query = array_flip($r_query);
	
	$found = array();

	// Original code: The Wizard of DATz
	include_once("../sites/".$source.".php");
}

//https://davidwalsh.name/curl-download
/* gets the data from a URL */
function get_data($url)
{
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

?>