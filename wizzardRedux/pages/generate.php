<?php

/* ------------------------------------------------------------------------------------
Create a DAT from the database
Original code by Matt Nadareski (darksabre76), emuLOAD

TODO: emuload - For CMP, a virtual parent can be created as an empty set and then
	each set that has it as a parent sets it as cloneof
TODO: Look at http://www.logiqx.com/Dats/datafile.dtd for XML DAT info
TODO: Limit DAT creation for some sources, e.g. No-Intro, Good, TOSEC, Redump, etc.
 ------------------------------------------------------------------------------------ */

// All possible $_GET variables that we can use (propogate this to other files?)
$getvars = array(
		"system",			// systems.id
		"source",			// sources.id
		"old",				// set this to 1 for the old style output
		"dats",				// systems.id-sources.id
		"mega",				// override to create complete merged DAT
		"auto",				// auto-create all available DATs
);

// Map systems to headers for datfile creation
$headers = array(
		"25" => "a7800.xml",
		"228" => "fds.xml",
		"31" => "lynx.xml",
		"0" => "mega.xml",						// Merged version of all other headers
		"238" => "nes.xml",
		"241" => "snes.xml",					// Self-created to deal with various headers
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
	ini_set("memory_limit", "1024M");
}

// Use dropdown value to override others, if applicable
if ($dats != "" && $dats != "0")
{
	$dats = explode("-", $_GET["dats"]);
	$system = ($dats[0] == "0" ? "" : $dats[0]);
	$source = ($dats[1] == "0" ? "" : $dats[1]);
	$loc = "?page=generate&system=".$system."&source=".$source.(isset($_GET["old"]) && $_GET["old"] == 1 ? "&old=1" : "");
	header("Location: ".$loc);
	exit;
}


// If nothing is set, show the list of all available DATs (or generate all DATs)
if ($system == "" && $source == "" && $mega != "1")
{
	echo "<h2>Export to Datfile</h2>";
	
	// If we are creating all DATs, don't show the form
	if ($auto != "1")
	{
		echo "<h3>Available Systems</h3>
<form action='?page=generate' method='get'>
<input type='hidden' name='page' value='generate' />
<select name='dats' id='dats'>
<option value=' ' selected='selected'>Choose a DAT</option>\n";
	}
	// Though we should create the MEGAMERGED DAT...
	else
	{
		/*
		ini_set("memory_limit", "1024M");
		echo "Beginning generate ALL (merged)<br/>\n";
		generate_dat("", "");
		sleep(5);
		*/
	}
	
	$query = "SELECT DISTINCT systems.id, systems.manufacturer, systems.system
		FROM systems
		JOIN games
			ON systems.id=games.system
		ORDER BY systems.manufacturer, systems.system";
	$result = mysqli_query($link, $query);
	
	// Either generate options for custom and system-merged DATs OR generate them in auto mode
	while($system = mysqli_fetch_assoc($result))
	{
		if ($auto != "1")
		{
			echo "<option value='".$system["id"]."-0'>".$system["manufacturer"]." - ".$system["system"]." (merged)</option>\n";
		}
		else
		{
			echo "Beginning generate ".$system["manufacturer"]." - ".$system["system"]." (merged)<br/>\n";
			generate_dat($system["id"], "");
			sleep(2);
		}
		
		$query = "SELECT DISTINCT sources.id, sources.name
			FROM systems
			JOIN games
				ON systems.id=games.system
			JOIN sources
				ON games.source=sources.id
			WHERE systems.id=".$system["id"];
		$sresult = mysqli_query($link, $query);
		
		while($source = mysqli_fetch_assoc($sresult))
		{
			if ($auto != "1")
			{
				echo "<option value='".$system["id"]."-".$source["id"]."'>".$system["manufacturer"]." - ".$system["system"]." (".$source["name"].")</option>\n";
			}
			else
			{
				echo "Beginning generate ".$system["manufacturer"]." - ".$system["system"]." (".$source["name"].")<br/>\n";
				generate_dat($system["id"], $source["id"]);
			}
		}
		
		// Free up the memory 
		unset($sresult);
	}
	
	// Free up the memory
	unset($result);
	
	// Either generate options for source-merged DATs OR generate them in auto mode
	$query = "SELECT DISTINCT sources.id, sources.name
		FROM sources
		JOIN games
			ON sources.id=games.source";
	$result = mysqli_query($link, $query);
	
	while($source = mysqli_fetch_assoc($result))
	{
		if ($auto != "1")
		{
			echo "<option value='0-".$source["id"]."'>ALL (".$source["name"].")</option>\n";
		}
		else
		{
			echo "Beginning generate ALL (".$source["name"].")<br/>\n";
			generate_dat("", $source["id"]);
		}
	}
	
	// Free up the memory
	unset($result);
	
	// If we're not in auto mode, then end the form and show the remaining links
	if ($auto != "1")
	{
		echo "</select><br/>
<input type='checkbox' name='old' value='1'>Use old DAT format<br/><br/>
<input type='submit'>\n</form><br/>
<a href='?page=generate&mega=1'>Create DAT of all available files</a><br/>";
	}
	// If we're in auto mode, zip up the files and clean up
	else
	{
		//echo "Creating new zipfile...<br/>\n";
		$zip = new ZipArchive();
		$zip->open("temp/dats-".date("Ymd").".zip", ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
		
		// Seems unnecessary, but helps make sure the zipfile is populated correctly
		$zip->addEmptyDir("merged-system");
		$zip->addEmptyDir("merged-source");
		$zip->addEmptyDir("custom");
		
		//echo "Zipfile created successfully!<br/>\n";
		foreach (scandir("temp/output") as $item)
		{
			if (strpos($item, ".xml") !== FALSE || strpos($item, ".dat") !== FALSE)
			{
				//echo "Adding ".$item."<br/>\n";
				$zip->addFile("temp/output/".$item,
						(strpos($item, "ALL (merged") !== FALSE ? $item :
						(strpos($item, "merged") !== FALSE ? "merged-system/".$item :
						(strpos($item, "ALL") !== FALSE ? "merged-source/".$item : "custom/".$item))));
			}
		}
		
		$zip->close();
		
		// http://php.net/manual/en/function.unlink.php#109971
		array_map("unlink", glob("temp/output/*.*"));
	}
}
// If we have system, source, or mega set, generate the appropriate DAT
else
{
	generate_dat($system, $source, true);
	exit();
}

/*
 If just the source is set, create a DAT that has each game suffixed by system and merged
 If just the system is set, create a DAT that has each game suffixed by source and merged (merged)
 If both system and source are set, create a DAT that has each rom unsuffixed and unmerged (custom)
 */
function generate_dat ($system, $source, $lone = false)
{
	global $link, $headers;
	
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
		
		// Free up the memory
		unset($result);
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
		
		// Free up the memory
		unset($result);
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
					($system != "" ? " systems.id=".$system : "")."
			ORDER BY games.name ASC, files.name ASC";
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
	
	// Free up the memory
	unset($result);

	// Process the roms
	$roms = process_roms($roms, $system, $source);

	$version = date("YmdHis");
	$datname = ($system != "" ? $roms[0]["manufacturer"]." - ".$roms[0]["system"] : "ALL").
	" (".($source != "" ? $roms[0]["source"] : "merged")." ".$version.")";
	
	// Create and open an output file for writing (currently uses current time, change to "last updated time"
	if ($lone)
	{
		ob_end_clean();
		
		//First thing first, push the http headers
		header('content-type: application/x-gzip');
		header('Content-Disposition: attachment; filename="'.$datname.($old == "1" ? ".dat" : ".xml").'.gz"');
	}
	else
	{
		echo "Opening file for writing: temp/output/".$datname.($old == "1" ? ".dat" : ".xml")."<br/>\n";
		$handle = fopen("temp/output/".$datname.($old == "1" ? ".dat" : ".xml"), "w");
	}
	
	// Temporarilly set $system if we're in MEGAMERGED mode
	if ($system == "" && $source == "")
	{
		$system = "0";
	}

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
	
	// Unset $system again if we're in MEGAMERGED mode
	if ($system == "0" && $source == "")
	{
		$system = "";
	}

	if (!$lone)
	{
		echo "Writing data to file<br/>\n";
	}
	$lastgame = "";
	if ($old == "1")
	{
		// Write the header out
		if ($lone)
		{
			echo gzencode($header_old, 9);
		}
		else
		{
			fwrite($handle, $header_old);
		}
		
		// Write out each of the machines and roms
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
			
			if ($lone)
			{
				echo gzencode($state, 9);
			}
			else
			{
				fwrite($handle, $state);
			}
		}
		
		if ($lone)
		{
			echo gzencode(")", 9);
		}
		else
		{
			fwrite($handle, ")");
		}
	}
	else
	{
		// Write the header out
		if ($lone)
		{
			echo gzencode($header, 9);
		}
		else
		{
			fwrite($handle, $header);
		}
		
		// Write out each of the machines and roms
		foreach ($roms as $rom)
		{
			// Preprocess each game and rom name for safety
			$rom["game"] = htmlspecialchars($rom["game"]);
			$rom["name"] = htmlspecialchars($rom["name"]);
			
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
				
			if ($lone)
			{
				echo gzencode($state, 9);
			}
			else
			{
				fwrite($handle, $state);
			}
		}
		
		if ($lone)
		{
			echo gzencode("\t</machine>", 9);
			echo gzencode($footer, 9);
		}
		else
		{
			fwrite($handle, "\t</machine>");
			fwrite($handle, $footer);
		}	
	}
	
	// Free up the memory
	unset($roms);

	if (!$lone)
	{
		fclose($handle);
		echo "File written!<br/>\n";
	}
}

// Change duplicate names and remove duplicates (merged only)
function process_roms ($roms, $system, $source)
{
	// First, go through and rename any necessary
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
	if ($system == "" || $source == "")
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
				($system == "" ? " [".$rom["manufacturer"]." - ".$rom["system"]."]" : "").
				($source == "" ? " [".$rom["source"]."]" : "");
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