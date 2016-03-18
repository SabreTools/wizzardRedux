<?php

/* ------------------------------------------------------------
Automatically download all No-Intro DATs to the server machine

NOTE: THIS CODE DOES NOT WORK AND PROBABLY WILL NEVER WORK BECAUSE
	OF HOW NO-INTRO HAS THEIR WEBSITE SET UP. THE VARIABLE CURRENTLY
	LABELED "Download12" IS VARIABLY NAMED AND IS A FAILSAFE SO
	THIS EXACT THING CAN'T BE DONE. THIS PAGE IS SAVED FOR FUTURE
	RESARCH INTO THE SUBJECT
-------------------------------------------------------------*/

ini_set('max_execution_time', 0); // Set the execution time to infinite. This is a bad idea in production.

// All possible DAT creation POST options		
$options = array(
	"crypted" => "0",						// Use decrypted ROM values
	"Download12" => "1860",					// Required POST value (both key and value change)
	"format" => "clrmamepro",				// Output a clrmamepro XML datfile
	"inc_bios" => "1",						// Include BIOS
	"inc_physical" => "0",					// Include non-physical roms
	"inc_pirate" => "1",					// Include pirate roms
	"inc_private" => "1",					// Include private roms
	"inc_unl" => "1",						// Include unlicensed roms
	"inc_xroms" => "1",						// Include xxxx roms
	"language_filter" => "all_languages",	// Don't filter out any languages
	"numbered" => "0",						// Don't include numbered entries
	"region_filter" => "all_regions",		// Don't filter out any regions
	"special_filter" => "all_specials",		// Don't filter out any specials
);
	
// Mapping of each system to used options	
$systems = array(
	"Atari - 5200" => array("inc_bios", "inc_unl", "format"),
	"Atari - 7800" => array("inc_bios", "format", "region_filter"),
	"Atari - Jaguar" => array("inc_bios", "format", "region_filter"),
	"Atari - Lynx" => array("inc_bios", "format", "region_filter"),
	"Atari - ST" => array("numbered", "format", "special_filter", "language_filter", "region_filter"),
	"Bandai - WonderSwan" => array("format", "language_filter", "region_filter"),
	"Bandai - WonderSwan Color" => array("format", "language_filter", "region_filter"),
	"Casio - Loopy" => array("inc_bios", "format"),
	"Casio - PV-1000" => array("format"),
	"Coleco - ColecoVision" => array("inc_bios", "inc_unl", "format", "region_filter"),
	"Commodore - 64" => array("inc_bios", "inc_unl", "format", "special_filter", "language_filter", "region_filter"),
	"Commodore - 64 (PP)" => array("format", "special_filter", "language_filter", "region_filter"),
	"Commodore - 64 (Tapes)" => array("format", "special_filter", "language_filter", "region_filter"),
	"Commodore - Amiga" => array("numbered", "inc_bios", "inc_unl", "inc_physical", "format", "inc_private", "special_filter", "language_filter", "region_filter"),
	"Commodore - Plus-4" => array("inc_bios", "format", "language_filter", "region_filter"),
	"Commodore - VIC-20" => array("inc_bios", "format", "language_filter", "region_filter"),
	"Emerson - Arcadia 2001" => array("format", "region_filter"),
	"Entex - Adventure Vision" => array("inc_bios", "format"),
	"Epoch - Super Cassette Vision" => array("format", "language_filter", "region_filter"),
	"Fairchild - Channel F" => array("inc_bios", "format", "language_filter", "region_filter"),
	"Funtech - Super Acan" => array("format"),
	"GamePark - GP32" => array("inc_bios", "format", "language_filter", "region_filter"),
	"GCE - Vectrex" => array("inc_bios", "format", "region_filter"),
	"Hartung - Game Master" => array("inc_bios", "format"),
	"LeapFrog - Leapster Learning Game System" => array("format", "language_filter", "region_filter"),
	"Magnavox - Odyssey2" => array("inc_bios", "format", "language_filter", "region_filter"),
	"Microsoft - MSX" => array("inc_bios", "inc_unl", "format", "language_filter", "region_filter"),
	"Microsoft - MSX 2" => array("inc_bios", "format", "language_filter", "region_filter"),
	"Microsoft - XBOX 360 (DLC)" => array(),
	"Microsoft - XBOX 360 (Games on Demand)" => array(),
	"Microsoft - XBOX 360 (Title Updates)" => array("format", "language_filter", "region_filter"),
	"NEC - PC Engine - TurboGrafx 16" => array("inc_bios", "format", "language_filter", "region_filter"),
	"NEC - Super Grafx" => array("format"),
	"Nintendo - Famicom Disk System" => array("inc_bios", "inc_unl", "format"),
	"Nintendo - Game Boy" => array("inc_bios", "inc_unl", "format", "special_filter", "language_filter", "region_filter"),
	"Nintendo - Game Boy Advance" => array("numbered", "inc_bios", "inc_unl", "inc_xroms", "inc_physical", "format", "language_filter", "region_filter"),
	"Nintendo - Game Boy Advance (e-Cards)" => array("format", "inc_private", "special_filter", "language_filter", "region_filter"),
	"Nintendo - Game Boy Color" => array("inc_bios", "inc_unl", "format", "special_filter", "language_filter", "region_filter"),
	"Nintendo - New Nintendo 3DS" => array("numbered", "inc_xroms", "format", "language_filter", "region_filter"),
	"Nintendo - New Nintendo 3DS (DLC)" => array("numbered", "inc_xroms", "format"),
	"Nintendo - Nintendo 3DS" => array("numbered", "inc_xroms", "format", "special_filter", "language_filter", "region_filter"),
	"Nintendo - Nintendo 3DS (DLC)" => array("numbered", "format", "special_filter", "language_filter", "region_filter"),
	"Nintendo - Nintendo 64" => array("inc_unl", "inc_physical", "format", "language_filter", "region_filter"),
	"Nintendo - Nintendo 64DD" => array("format"),
	"Nintendo - Nintendo DS" => array("numbered", "crypted", "inc_bios", "inc_unl", "inc_xroms", "format", "special_filter", "language_filter", "region_filter"),
	"Nintendo - Nintendo DS (Download PLay) (BETA)" => array("format", "special_filter", "language_filter", "region_filter"),
	"Nintendo - Nintendo DSi" => array("numbered", "crypted", "inc_xroms", "format", "language_filter", "region_filter"),
	"Nintendo - Nintendo DSi (DLC)" => array("numbered", "format", "language_filter", "region_filter"),
	"Nintendo - Nintendo Entertainment System" => array("inc_unl", "inc_physical", "format", "special_filter", "language_filter", "region_filter"),
	"Nintendo - Nintendo Wii (DLC)" => array("inc_xroms", "format", "special_filter", "language_filter", "region_filter"),
	"Nintendo - Pokemon Mini" => array("inc_bios", "format", "language_filter", "region_filter"),
	"Nintendo - Satellaview" => array("inc_physical", "format", "special_filter", "language_filter"),
	"Nintendo - Sufami Turbo" => array("format"),
	"Nintendo - Super Nintendo Entertainment System" => array("inc_bios", "inc_unl", "inc_pirate", "inc_physical", "format", "special_filter", "language_filter", "region_filter"),
	"Nintendo - Virtual Boy" => array("format", "language_filter", "region_filter"),
	"Philips - Videopac+" => array("inc_bios", "format", "language_filter", "region_filter"),
	"RCA - Studio II" => array("inc_bios", "format"),
	"Sega - 32X" => array("inc_bios", "format", "language_filter", "region_filter"),
	"Sega - Game Gear" => array("inc_unl", "format", "language_filter", "region_filter"),
	"Sega - Master System - Mark III" => array("inc_bios", "inc_unl", "inc_pirate", "format", "language_filter", "region_filter"),
	"Sega - Mega Drive - Genesis" => array("inc_bios", "inc_unl", "inc_pirate", "inc_physical", "format", "special_filter", "language_filter", "region_filter"),
	"Sega - PICO" => array("format", "language_filter", "region_filter"),
	"Sega - SG-1000" => array("inc_bios", "format", "special_filter", "language_filter", "region_filter"),
	"Sinclair - ZX Spectrum +3" => array("numbered", "format", "inc_private"),
	"SNK - Neo Geo Pocket" => array("inc_bios", "format", "language_filter", "region_filter"),
	"SNK - Neo Geo Pocket Color" => array("inc_bios", "format", "language_filter", "region_filter"),
	"Sony - PlayStation 3 (DLC)" => array("format"),
	"Sony - PlayStation 3 (Downloadable)" => array("format"),
	"Sony - PlayStation 3 (PSN)" => array("format", "language_filter", "region_filter"),
	"Sony - PlayStation Portable" => array("numbered", "inc_xroms", "inc_physical", "format", "special_filter", "language_filter", "region_filter"),
	"Sony - PlayStation Portable (DLC)" => array(),
	"Sony - PlayStation Portable (PSN)" => array("numbered", "format", "language_filter", "region_filter"),
	"Sony - PlayStation Portable (PSX2PSP)" => array("inc_unl", "format", "language_filter", "region_filter"),
	"Sony - PlayStation Portable (UMD Music)" => array("format", "language_filter", "region_filter"),
	"Sony - PlayStation Portable (UMD Video)" => array("format", "language_filter", "region_filter"),
	"Tiger - Game.com" => array("inc_bios", "format"),
	"VTech - CreatiVision" => array("inc_bios", "format"),
	"VTech - V.Smile" => array("format", "language_filter", "region_filter"),
	"Watara - Supervision" => array("format"),
);

// POST URL
$url = "http://datomatic.no-intro.org/?page=download&fun=dat";

/*
// Download the newest version of each file
// http://stackoverflow.com/questions/5647461/how-do-i-send-a-post-request-with-php
foreach ($systems as $key => $value)
{
	$data = array();
	$data["sel_s"] = $key;
	$data["Download15"] = $options["Download15"];
	foreach ($value as $option)
	{
		$data[$option] = $options[$option];
	}
	
	$options = array(
		'http' => array(
			'header' => "Content-type: application/x-www-form-urlencoded\r\n",
			'method' => 'POST',
			'content' => http_build_query($data),
		),
	);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE)
	{
		echo "Error case!<br/>\n";
		die();
	}
	
	var_dump($result);
	
	if (!file_exists("../temp/no-intro/"))
	{
		mkdir("../temp/no-intro/", 0777,  true);
	}
	
	file_put_contents("../temp/no-intro/".$key.".zip", $result);
}
*/

// Alternate using forms
foreach ($systems as $key => $value)
{
	echo "<form action='".$url."' method='post'>\n".
	"<input type='hidden' name='sel_s' value='".$key."'/>\n".
	"<input type='hidden' name='Download15' value='".$options["Download15"]."'/>\n";
	foreach ($value as $option)
	{
		echo "<input type='hidden' name='".$option."' value='".$options[$option]."'/>\n";
	}
	echo "<input type='submit' value='".$key."'/>\n".
	"</form><br/>\n\n";
}

?>