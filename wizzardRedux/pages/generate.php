<?php

/* ------------------------------------------------------------------------------------
Create a DAT from the database
Original code by Matt Nadareski (darksabre76), emuLOAD

TODO: emuload - For CMP, a virtual parent can be created as an empty set and then
	each set that has it as a parent sets it as cloneof
TODO: Look at http://www.logiqx.com/Dats/datafile.dtd for XML DAT info
 ------------------------------------------------------------------------------------ */

// All possible $_GET variables that we can use (propogate this to other files?)
$getvars = array(
		"mega",				// override to create complete merged DAT
		"auto",				// auto-create all available DATs
);

$postvars = array(
		"generate",			// If a DAT should be generated (only on submit)
		"system",			// systems.id
		"source",			// sources.id
		"all",				// All sources selected
		"old",				// Set this to 1 for the old style output
);

// Map systems to headers for datfile creation
$headers = array(
		"25" => "a7800.xml",
		"228" => "fds.xml",
		"31" => "lynx.xml",
		"0" => "mega.xml",						// Merged version of all other headers
		"234" => "n64.xml",
		"238" => "nes.xml",
		"241" => "snes.xml",					// Self-created to deal with various headers
);

ini_set('max_execution_time', 0); // Set the execution time to infinite. This is a bad idea in production.

//Get the values for all parameters
foreach ($getvars as $var)
{
	$$var = (isset($_GET[$var]) ? trim($_GET[$var]) : "");
}

//Get the values for all parameters
foreach ($postvars as $var)
{
	$$var = (isset($_POST[$var]) ? trim($_POST[$var]) : "");
}

// Specifically deal with MEGA being set
if ($mega == "1")
{
	$system = "";
	$source = "";
	ini_set("memory_limit", "-1"); // 1024M didn't cut it for the biggest database
}

// If not generating or creating MEGAMERGED, show the list of all available DATs
if ($generate != "1" && $mega != "1" && $auto != "1")
{
	echo <<<EOL
<h2>Export to Datfile</h2>
<table>
<tr><th>System</th><th>Source</th></tr>
<tr><td>
<form name="systemselect" action="?page=generate" method="post">
<select name="system" onChange="window.document.forms['systemselect'].submit();">\n
EOL;

	$query = "SELECT DISTINCT systems.id, systems.manufacturer, systems.system
		FROM systems
		JOIN games
			ON systems.id=games.system
		ORDER BY systems.manufacturer, systems.system";
	$result = mysqli_query($link, $query);

	// Either generate options for custom and system-merged DATs OR generate them in auto mode
	while($sys = mysqli_fetch_assoc($result))
	{
		echo "<option value='".$sys["id"]."'".($system == $sys["id"] ? " selected='selected'" : "").">".$sys["manufacturer"]." - ".$sys["system"]."</option>\n";
	}

	echo <<<EOL
</select></form></td>
<td>
<form name="sourceselect" action="?page=generate" method="post">
<select name="source" onChange="window.document.forms['sourceselect'].submit();">\n
EOL;
		
	// Generate options for source-merged DATs
	$query = "SELECT DISTINCT sources.id, sources.name
		FROM sources
		JOIN games
			ON sources.id=games.source
		ORDER BY sources.name";
	$result = mysqli_query($link, $query);
	
	while($src = mysqli_fetch_assoc($result))
	{
		// If the source is not one of the import-only ones
		if ((int) $src["id"] > 14)
		{
			echo "<option value='".$src["id"]."'".($source == $src["id"] ? " selected='selected'" : "").">".$src["name"]."</option>\n";
		}
	}
	
	echo "</select><br/>
</form></tr></table>";
	
	// If the system is set, it takes precidence in DAT creation
	if ($system != "")
	{
		echo <<<EOL
<form name="dat" action="?page=generate" method="post">
<input type="hidden" name="generate" value="1">
<input type="hidden" name="system" value="$system">
<h3>Sources:</h3>
<input type="radio" name="all" value="1" checked>All sources <b> OR </b> <input type="radio" name="all" value="0">Select a source below:<p/>\n

EOL;
			
		$query = "SELECT DISTINCT sources.id, sources.name
		FROM systems
		JOIN games
			ON systems.id=games.system
		JOIN sources
			ON games.source=sources.id
		WHERE systems.id=".$system.
		" ORDER BY sources.name";
		$result = mysqli_query($link, $query);
		
		while($src = mysqli_fetch_assoc($result))
		{
			echo "<input type=\"checkbox\" name=\"".$src["id"]."\" value=\"1\">".$src["name"]."<br/>";
		}
	}
	
	// If the source is set, show all values for the source
	else if ($source != "")
	{
		echo <<<EOL
<form name="dat" action="?page=generate" method="post">
<input type="hidden" name="generate" value="1">
<input type="hidden" name="source" value="$source">
<h3>Systems:</h3>
<input type="radio" name="all" value="1" checked>All systems <b> OR </b> <input type="radio" name="all" value="0">Select a system below:<p/>\n

EOL;
		
		$query = "SELECT DISTINCT systems.id, systems.manufacturer, systems.system
		FROM sources
		JOIN games
			ON sources.id=games.source
		JOIN systems
			ON games.system=systems.id
		WHERE sources.id=".$source.
		" ORDER BY systems.manufacturer, systems.system";
		$result = mysqli_query($link, $query);
		
		while($sys = mysqli_fetch_assoc($result))
		{
			echo "<input type=\"checkbox\" name=\"".$sys["id"]."\" value=\"1\">".$sys["manufacturer"]." - ".$sys["system"]."<br/>";
		}
	}
	
	echo "<br/><h3>Datfile format:</h3>
<input type='radio' name='old' value='0' checked>XML Format<br/>
<input type='radio' name='old' value='1'>Old Format<p/>
<input type='submit' value='Submit'>
</form><p/>
<a href='?page=generate&mega=1'>Create DAT of all available files</a><br/>";
}

// If not generating or creating MEGAMERGED, generate of all available DATs
elseif ($generate != "1" && $mega != "1" && $auto == "1")
{
	echo "<h2>Generate All DATs</h2>";
	
	$query = "SELECT DISTINCT systems.id, systems.manufacturer, systems.system
		FROM systems
		JOIN games
			ON systems.id=games.system
		ORDER BY systems.manufacturer, systems.system";
	$result = mysqli_query($link, $query);
	
	// Either generate options for custom and system-merged DATs OR generate them in auto mode
	while($sys = mysqli_fetch_assoc($result))
	{
		echo "Beginning generate ".$system["manufacturer"]." - ".$system["system"]." (merged)<br/>\n";
		generate_dat($system["id"], "");
		sleep(2);
	}
	
	// Generate options for source-merged DATs
	$query = "SELECT DISTINCT sources.id, sources.name
		FROM sources
		JOIN games
			ON sources.id=games.source
		ORDER BY sources.name";
	$result = mysqli_query($link, $query);
	
	while($src = mysqli_fetch_assoc($result))
	{
		// If the source is not one of the import-only ones
		if ((int) $src["id"] > 14)
		{
			echo "Beginning generate ALL (".$source["name"].")<br/>\n";
			generate_dat("", $source["id"]);
			
			$query = "SELECT DISTINCT sources.id, sources.name
				FROM systems
				JOIN games
					ON systems.id=games.system
				JOIN sources
					ON games.source=sources.id
				WHERE systems.id=".$system.
				" ORDER BY sources.name";
			$sresult = mysqli_query($link, $query);
			
			while($src = mysqli_fetch_assoc($sresult))
			{
				echo "Beginning generate ".$sys["manufacturer"]." - ".$sys["system"]." (".$src["name"].")<br/>\n";
				generate_dat($sys["id"], $src["id"]);
			}
		}
	}
	
	// Create the MEGAMERGED as part of the generation process
	ini_set("memory_limit", "-1"); // Set the maximum memory to infinite. This is a bad idea in production.
	echo "Beginning generate ALL (merged)<br/>\n";
	generate_dat("", "");

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

// If we are generating an individual DAT
elseif ($generate == "1" || $mega == "1")
{
	$systems = array();
	$sources = array();
	
	// If we have a source, treat all POST vars as systems
	if ($system == "" && $source != "")
	{
		$sources = $source;
		
		if ($all == "1")
		{
			$systems = "";
		}
		else
		{
			foreach ($_POST as $key => $value)
			{
				if (!in_array($key, $postvars) && $value == "1")
				{
					$systems[] = $key;
				}
			}
			
			$systems = implode(", ", $systems);
		}
	}
	// If we have a system, treat all POST vars as sources
	elseif ($system != "" && $source == "")
	{
		$systems = $system;
		
		if ($all == "1")
		{
			$sources = "";
		}
		else
		{
			foreach ($_POST as $key => $value)
			{
				if (!in_array($key, $postvars) && $value == "1")
				{
					$sources[] = $key;
				}
			}
			
			$sources = implode(", ", $sources);
		}
	}
	// If we have neither, then it's MEGA
	else
	{
		$systems = "";
		$sources = "";
	}
	
	// If the source is not one of the import-only ones
	if (!($sources != "" && sizeof(explode(", ", $sources)) == 1 && (int) $sources <= 14))
	{
		generate_dat($systems, $sources, true);
	}
	exit();
}

/*
 If just the source is set, create a DAT that has each game suffixed by system and merged
 If just the system is set, create a DAT that has each game suffixed by source and merged (merged)
 If both system and source are set, create a DAT that has each rom unsuffixed and unmerged (custom)
 */
function generate_dat ($systems, $sources, $lone = false)
{
	global $link, $headers;

	// Check the validity of the source id
	if ($sources != "")
	{
		$query = "SELECT * FROM sources WHERE id IN (".$sources.")";
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
	if ($systems != "")
	{
		$query = "SELECT * FROM systems WHERE id IN (".$systems.")";
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
				($systems != "" || $sources != "" ? " WHERE" : "").
				($sources != "" ? " sources.id IN (".$sources.")" : "").
				($sources != "" && $systems != "" ? " AND" : "").
				($systems != "" ? " systems.id IN (".$systems.")" : "")."
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

	// Process the roms
	$roms = process_roms($roms, $systems, $sources);

	$version = date("YmdHis");
	
	// Get the systems and sources names for the given inputs
	$querysy = "SELECT manufacturer, system FROM systems WHERE systems.id IN (".$systems.")";
	$resultsy = mysqli_query($link, $querysy);
	
	$syslist;
	if (gettype($resultsy) != "boolean" && mysqli_num_rows($resultsy) > 0)
	{
		$syslist = array();
		
		while ($sys = mysqli_fetch_assoc($resultsy))
		{
			$syslist[] = $sys["manufacturer"]." - ".$sys["system"];
		}
		
		$syslist = implode(", ", $syslist);
	}
	else
	{
		$syslist = "";
	}
	
	$queryso = "SELECT name FROM sources WHERE sources.id IN (".$sources.")";
	$resultso = mysqli_query($link, $queryso);
	
	$srclist;
	if (gettype($resultso) != "boolean" && mysqli_num_rows($resultso) > 0)
	{
		$srclist = array();
	
		while ($src = mysqli_fetch_assoc($resultso))
		{
			$srclist[] = $src["name"];
		}
		
		$srclist = implode(", ", $srclist);
	}
	else
	{
		$srclist = "";
	}
	
	$datname = ($syslist != "" ? $syslist : "ALL")." (".($srclist != "" ? $srclist : "merged")." ".$version.")";

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
	if ($systems == "" && $sources == "")
	{
		$systems = "0";
	}

	$header_old = "clrmamepro (
	name \"".htmlspecialchars($datname)."\"
	description \"".htmlspecialchars($datname)."\"
	version \"".$version."\"
	".($system != "" && array_key_exists($systems, $headers) ? " header \"".$headers[$systems]."\"" : "")."
	comment \"\"
	author \"The Wizard of DATz\"
)\n";

	$header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	<!DOCTYPE datafile PUBLIC \"-//Logiqx//DTD ROM Management Datafile//EN\" \"http://www.logiqx.com/Dats/datafile.dtd\">

	<datafile>
		<header>
			<name>".htmlspecialchars($datname)."</name>
			<description>".htmlspecialchars($datname)."</description>
			<category>The Wizard of DATz</category>
			<version>".$version."</version>
			<date>".$version."</date>
			<author>The Wizard of DATz</author>
			<clrmamepro".($systems != "" && array_key_exists($systems, $headers) ? " header=\"".$headers[$systems]."\"" : "")."/>
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
			$rom["game"] = htmlspecialchars(utf8_encode($rom["game"]));
			$rom["name"] = htmlspecialchars(utf8_encode($rom["name"]));
				
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
	
	if (!$lone)
	{
		fclose($handle);
		echo "File written!<br/>\n";
	}
}

// Change duplicate names and remove duplicates (merged only)
function process_roms ($roms, $systems, $sources)
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
	if ($systems == "" || sizeof(explode(", ", $systems)) > 1 || $sources == "" || sizeof(explode(", ", $sources)) > 1)
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
			($systems == "" || sizeof(explode(", ", $systems)) > 1 ? " [".$rom["manufacturer"]." - ".$rom["system"]."]" : "").
			($sources == "" || sizeof(explode(", ", $sources)) > 1 ? " [".$rom["source"]."]" : "");
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