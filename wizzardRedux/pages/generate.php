<?php

/* ------------------------------------------------------------------------------------
Create a DAT from the database
	
TODO: Create an "auto-generate all available". The following is required:
	- Create method from "else" block, call it "generate_dat"
	- Add new GET var called "auto"
	- Add new link to the default page with auto=1
	- Copy and retool the create list of options code to make a nested loop to generate the file
TODO: emuload - For CMP, a virtual parent can be created as an empty set and then
	each set that has it as a parent sets it as cloneof
TODO: Look at http://www.logiqx.com/Dats/datafile.dtd for XML DAT info
TODO: Substitute in the system id for the keys in $headers
 ------------------------------------------------------------------------------------ */

echo "<h2>Export to Datfile</h2>";

// All possible $_GET variables that we can use (propogate this to other files?)
$getvars = array(
		"system",			// systems.id
		"source",			// sources.id
		"old",				// set this to 1 for the old style output
		"dats",				// systems.id-sources.id
		"mega",				// override to create complete merged DAT
);

// Map systems to headers for datfile creation
$headers = array(
		"a7800" => "a7800.xml",
		"fds" => "fds.xml",
		"lynx" => "lynx.xml",
		//"n64-BADC" => "n64-BADC.xml",				// Appears unused by No-Intro or NonGood
		//"n64-DCBA" => "n64-DCBA.xml",				// Appears unused by No-Intro or NonGood
		//"n64" => "n64.xml",							// Appears unused by No-Intro or NonGood
		"nes" => "nes.xml",
		//"No-Intro_A7800" => "No-Intro_A7800.xml",	// Functional subset of a7800.xml
		//"No-Intro_FDS" => "No-Intro_FDS.xml",		// Functional subset of fds.xml
		//"No-Intro_LNX" => "No-Intro_LNX.xml",		// Functional subset of lnx.xml
		//"no-intro_NES" => "No-Intro_NES.xml",		// Functional subset of nes.xml
		//"nongoodnes" => "nongoodnes.xml",			// Functional subset of nes.xml
		"psid" => "psid.xml",
		"spc" => "spc.xml",
);

ini_set('max_execution_time', 0); // Set the execution time to infinite. This is a bad idea in production.

//Get the values for all parameters
foreach ($getvars as $var)
{
	$$var = (isset($_GET[$var]) ? trim($_GET[$var]) : "");
}

// Specifically deal with MEGA being set
if ($mega == "1")
{
	$system = "";
	$source = "";
	ini_set("memory_limit", "512M");
}

// Use dropdown value to override others, if applicable
if ($dats != "" && $dats != "0")
{
	$dats = explode("-", $_GET["dats"]);
	$system = $dats[0];
	$source = ($dats[1] == "0" ? "" : $dats[1]);
	$loc = "?page=generate&system=".$system."&source=".$source.(isset($_GET["old"]) && $_GET["old"] == 1 ? "&old=1" : "");
	header("Location: ".$loc);
	exit;
}


// If nothing is set, show the list of all available DATs
if ($system == "" && $source == "" && $mega != "1")
{
	$query = "SELECT DISTINCT systems.id, systems.manufacturer, systems.system
		FROM systems
		JOIN games
			ON systems.id=games.system
		ORDER BY systems.manufacturer, systems.system";
	$result = mysqli_query($link, $query);
	
	echo "<h3>Available Systems</h3>\n";
	
	$systems = array();
	while($system = mysqli_fetch_assoc($result))
	{
		array_push($systems, $system);
	}
	
	// Note: Source-only DATs are not provided as an option yet
	echo "<form action='?page=generate' method='get'>
<input type='hidden' name='page' value='generate' />
<select name='dats' id='dats'>
<option value=' ' selected='selected'>Choose a DAT</option>\n";
	foreach ($systems as $system)
	{
		echo "<option value='".$system["id"]."-0'>".$system["manufacturer"]." - ".$system["system"]." (merged)</option>\n";
	
		$query = "SELECT DISTINCT sources.id, sources.name
			FROM systems
			JOIN games
				ON systems.id=games.system
			JOIN sources
				ON games.source=sources.id
			WHERE systems.id=".$system["id"];
		$result = mysqli_query($link, $query);
	
		while($source = mysqli_fetch_assoc($result))
		{
			echo "<option value='".$system["id"]."-".$source["id"]."'>".$system["manufacturer"]." - ".$system["system"]." (".$source["name"].")</option>\n";
		}
	}
	echo "</select><br/>
<input type='checkbox' name='old' value='1'>Use old DAT format<br/><br/>
<input type='submit'>\n</form><br/><br/>
<a href='?page=generate&mega=1'>Create DAT of all available files</a><br/>";
}
/*
If just the source is set, create a DAT that has each game suffixed by system and merged
If just the system is set, create a DAT that has each game suffixed by source and merged (merged)
If both system and source are set, create a DAT that has each rom unsuffixed and unmerged (custom)
*/
else
{
	// Check the validity of the source id
	if ($source != "")
	{
		$query = "SELECT * FROM sources WHERE id=".$source;
		$result = mysqli_query($link, $query);
		
		// If the source doesn't exist, tell the user and don't proceed
		if (gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
		{
			echo "No source could be found by that ID. Please check and try again.<br/>";
			echo "<a href='?page=generate'>Go Back</a>";
			exit;
		}
	}
	
	// Check the validity of the system id
	if ($system != "")
	{
		$query = "SELECT * FROM systems WHERE id=".$system;
		$result = mysqli_query($link, $query);
		
		// If the system doesn't exist, tell the user and don't proceed
		if (gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
		{
			echo "No system could be found by that ID. Please check and try again.<br/>";
			echo "<a href='?page=generate'>Go Back</a>";
			exit;
		}
	}
	
	// Since the source and/or system are valid, retrive all files
	$query = "SELECT systems.manufacturer AS manufacturer, systems.system AS system, sources.name AS source, sources.url AS url,
				games.name AS game, files.name AS name, files.type AS type, checksums.size AS size, checksums.crc AS crc,
				checksums.md5 AS md5, checksums.sha1 AS sha1
			FROM systems
			JOIN games
				ON systems.id=games.system
			JOIN sources
				ON games.source=sources.id
			JOIN files
				ON games.id=files.setid
			JOIN checksums
				ON files.id=checksums.file".
			($system != "" || $source != "" ? " WHERE" : "").
			($source != "" ? " sources.id=".$source : "").
			($source != "" && $system != "" ? " AND" : "").
			($system != "" ? " systems.id=".$system : "");
	$result = mysqli_query($link, $query);
	
	// If there are no games for this set of parameters, tell the user
	if (gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
	{
		echo "No games could be found with those inputs. Please check and try again.<br/>";
		echo "<a href='?page=generate'>Go Back</a>";
		exit;
	}
	
	// Get all roms from the result for processing
	$roms = array();
	while($rom = mysqli_fetch_assoc($result))
	{
		array_push($roms, $rom);
	}
	
	// Process the roms
	$roms = process_roms($roms);
	
	// If we're in debug mode, print out the entire DAT to screen
	if ($debug)
	{
		echo "<table border='1'>
			<tr><th>Source</th><th>Set</th><th>Name</th><th>Size</th><th>CRC32</th><th>MD5</th><th>SHA1</th></tr>";
		
		foreach ($roms as $rom)
		{
			echo "<tr><td>".$rom["source"]."</td><td>".$rom["game"]."</td><td>".$rom["name"]."</td><td>".$rom["size"]."</td><td>".$rom["crc"]."</td><td>".$rom["md5"]."</td><td>".$rom["sha1"]."</td></tr>";
		}
		
		echo "</table>";
	}
	
	$version = date("YmdHis");
	$datname = ($system != "" ? $roms[0]["manufacturer"]." - ".$roms[0]["system"] : "ALL").
		" (".($source != "" ? $roms[0]["source"] : "merged")." ".$version.")";
	
	// Create and open an output file for writing (currently uses current time, change to "last updated time"
	echo "Opening file for writing: temp/output/".$datname.($old == "1" ? ".dat" : ".xml")."<br/>\n";
	$handle = fopen("temp/output/".$datname.($old == "1" ? ".dat" : ".xml"), "w");
	
	$header_old = "clrmamepro (
	name \"".$datname."\"
	description \"".$datname."\"
	version \"".$version."\"
	".($system != "" && array_key_exists($system, $headers) ? " header \"".$headers[$system]."\"" : "")."
	comment \"\"
	author \"The Wizard of DATz\"
)\n";
	
	$header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	<!DOCTYPE datafile PUBLIC \"-//Logiqx//DTD ROM Management Datafile//EN\" \"http://www.logiqx.com/Dats/datafile.dtd\">
	
	<datafile>
		<header>
			<name>".$datname."</name>
			<description>".$datname."</description>
			<category>The Wizard of DATz</category>
			<version>".$version."</version>
			<date>".$version."</date>
			<author>The Wizard of DATz</author>
			<clrmamepro".($system != "" && array_key_exists($system, $headers) ? " header=\"".$headers[$system]."\"" : "")."/>
		</header>\n";
	
	$footer = "\n</datafile>";
	
	echo "Writing data to file<br/>\n";
	$lastgame = "";
	if ($old == "1")
	{
		fwrite($handle, $header_old);
		foreach ($roms as $rom)
		{
			$state = "";		
			if ($lastgame != "" && $lastgame != $rom["game"])
			{
				$state = $state.")\n";
			}
			if ($lastgame != $rom["game"])
			{
				$state = $state."game (\n".
							"\tname \"".$rom["game"]."\"\n";
			}
			$state = $state."\t".$rom["type"]." ( name \"".$rom["name"]."\"".
					($rom["size"] != "0" ? " size ".$rom["size"] : "").
					($rom["crc"] != "" ? " crc ".$rom["crc"] : "").
					($rom["md5"] != "" ? " md5 ".$rom["md5"] : "").
					($rom["sha1"] != "" ? " sha1 ".$rom["sha1"] : "").
					" )\n";
	
			$lastgame = $rom["game"];
			
			fwrite($handle, $state);
		}
		fwrite($handle, ")");
	}
	else
	{
		fwrite($handle, $header);
		foreach ($roms as $rom)
		{
			$state = "";
			
			if ($lastgame != "" && $lastgame != $rom["game"])
			{
				$state = $state."\t</machine>\n";
			}
			if ($lastgame != $rom["game"])
			{
				$state = $state."\t<machine name=\"".$rom["game"]."\">\n".
					"\t\t<description>".$rom["game"]."</description>\n";
			}
			$state = $state."\t\t<".$rom["type"]." name=\"".$rom["name"]."\"".
				($rom["size"] != "" ? " size=\"".$rom["size"]."\"" : "").
				($rom["crc"] != "" ? " crc=\"".$rom["crc"]."\"" : "").
				($rom["md5"] != "" ? " md5=\"".$rom["md5"]."\"" : "").
				($rom["sha1"] != "" ? " sha1=\"".$rom["sha1"]."\"" : "").
				" />\n";
				
			$lastgame = $rom["game"];
			
			fwrite($handle, $state);
		}
		fwrite($handle, "\t</machine>");
		fwrite($handle, $footer);
	}
	fclose($handle);
	
	echo "File written!<br/>\n";
}

// Change duplicate names and remove duplicates (merged only)
function process_roms($roms)
{	
	GLOBAL $system, $source, $mega;
	
	// First sort all roms by name and game
	usort($roms, function ($a, $b)
	{
		$game_a = strtolower($a["game"]);
		$name_a = strtolower($a["name"]);
		$game_b = strtolower($b["game"]);
		$name_b = strtolower($b["name"]);
	
		if (strcmp($game_a, $game_b) == 0)
		{
			return strcmp($name_a, $name_b);
		}
		return strcmp($name_a, $name_b);
	});
	
	// Next, go through and rename any necessary
	$lastname = ""; $lastgame = "";
	$newroms = array();
	foreach ($roms as $rom)
	{
		if ($lastname == "")
		{
			array_push($newroms, $rom);
		}
		else
		{
			// Determine which matching criteria is available and match on them
			$samename = false; $samegame = false;
			if ($rom["name"] != "")
			{
				$samename = ($lastname == $rom["name"]);
			}
			if ($rom["game"] != "")
			{
				$samegame = ($lastgame == $rom["game"]);
			}
				
			// If the name and set are the same, rename it 
			if ($samename && $samegame)
			{
				$rom["name"] = preg_replace("/^(.*)(\..*)/", "\1 (".
						($rom["crc"] != "" ? $rom["crc"] :
							($rom["md5"] != "" ? $rom["md5"] :
									($rom["sha1"] != "" ? $rom["sha1"] : "Alt"))).
					")\2", $rom["name"]);
				array_push($newroms, $rom);
			}
			// Otherwise, just add it the way it is
			else
			{
				array_push($newroms, $rom);
			}
	
			$lastname = $rom["name"];
			$lastgame = $rom["game"];
		}
	}
	
	// If we're in a merged mode, go through and remove any duplicates (size, CRC/MD5/SHA1 match)
	if ($system == "" || $source != "")
	{		
		$roms = $newroms;
		unset($newroms);
		
		// First resort all roms by source and crc (or md5 or sha1)
		usort($roms, function ($a, $b)
		{
			$crc_a = strtolower($a["crc"]);
			$md5_a = strtolower($a["md5"]);
			$sha1_a = strtolower($a["sha1"]);
			$source_a = $a["source"];
			$crc_b = strtolower($b["crc"]);
			$md5_b = strtolower($b["md5"]);
			$sha1_b = strtolower($b["sha1"]);
			$source_b = $b["source"];
		
			if ($crc_a == "" || $crc_b == "")
			{
				if ($md5_a == "" || $md5_b == "")
				{
					if ($sha1_a == "" || $sha1_b == "")
					{
						return $source_a - $source_b;
					}
					return strcmp($sha1_a, $sha1_b);
				}
				return strcmp($md5_a, $md5_b);
			}
			return strcmp($crc_a, $crc_b);
		});
		
		$lastsize = ""; $lastcrc = ""; $lastmd5 = ""; $lastsha1 = ""; $lasttype = "";
		$newroms = array();
		foreach ($roms as $rom)
		{
			if ($lastsize == "")
			{
				$lastsize = $rom["size"];
				$lastcrc = $rom["crc"];
				$lastmd5 = $rom["md5"];
				$lastsha1 = $rom["sha1"];
				$lasttype = $rom["type"];
				array_push($newroms, $rom);
			}
			else
			{
				// Determine which matching criteria is available and match on them
				$samesize = false; $samecrc = false; $samemd5 = false; $samesha1 = false;
				if ($rom["size"] != "")
				{
					$samesize = ($lastsize == $rom["size"]);
				}
				if ($rom["crc"] != "")
				{
					$samecrc = ($lastcrc == $rom["crc"]);
				}
				if ($rom["md5"] != "")
				{
					$samemd5 = ($lastmd5 == $rom["md5"]);
				}
				if ($rom["sha1"] != "")
				{
					$samesha1 = ($lastsha1 == $rom["sha1"]);
				}
				
				// If we have a rom, we need at least the size and one criteria to match
				if ($rom["type"] == "rom")
				{
					if (!($samesize && ($samecrc || $samemd5 || $samesha1)))
					{
						array_push($newroms, $rom);
					}
				}
				// If we have a disk, it generally only has an md5 or sha1
				else
				{
					if (!($samemd5 || $samesha1))
					{
						array_push($newroms, $rom);
					}
				}
					
				$lastsize = $rom["size"];
				$lastcrc = $rom["crc"];
				$lastmd5 = $rom["md5"];
				$lastsha1 = $rom["sha1"];
				$lasttype = $rom["type"];
			}
		}		
		
		// Then rename the sets to include the proper source
		foreach ($newroms as &$rom)
		{			
			$rom["game"] = $rom["game"].
				($system != "" && $source == "" ? " [".$rom["source"]."]" : "").
				($system == "" && $source != "" ? " [".$rom["manufacturer"]." - ".$rom["system"]."]" : "").
				($system == "" && $source == "" ? " [".$rom["manufacturer"]." - ".$rom["system"]." (".$rom["source"].")]" : "");	
		}
	}
	
	// Once it's pruned, revert the order of the files by sorting by game
	usort($newroms, function ($a, $b)
	{		
		$game_a = strtolower($a["game"]);
		$game_b = strtolower($b["game"]);
		
		return strcmp($game_a, $game_b);
	});
	
	// Finally, change the pointer of $roms to the new array
	return $newroms;
}

?>