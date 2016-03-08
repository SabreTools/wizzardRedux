<?php

/* ------------------------------------------------------------------------------------
Import an existing DAT into the system

Requires:
	filename	File name in the format of "Manufacturer - SystemName (Source .*)\.dat"
	size		Sort the list by size of the DAT file (handy for multiple imports)
	type		If defined, sets the mapping to use on DAT import

TODO: Auto-generate DATs affected by import (merged and custom)?
------------------------------------------------------------------------------------ */

// Special import types
$type = array(
		"mame",
		"nointro",
		"redump",
		"tosec",
		"trurip",
);

echo "<h2>Import From Datfile</h2>";

ini_set('max_execution_time', 0); // Set the execution time to infinite. This is a bad idea in production.

// Verify GET variables
$auto = isset($_GET["auto"]) && $_GET["auto"] == "1";
$size = isset($_GET["size"]) && $_GET["size"] == "1";
$type = isset($_GET["type"]) && in_array($_GET["type"], $type) ? $_GET["type"] : "";

// Set import paths
$importroot = "../temp/import/";
$importdone = "../temp/imported/";

// If there's a type defined, set the root accordingly
if ($type != "")
{
	$importroot .= $type."/";
}

if (!isset($_GET["filename"]))
{
	// List all files, auto-generate links to proper pages
	echo "<p><a href='?page=import&auto=1".($size ? "&size=1" : "").($type != "" ? "&type=".$type : "")."'>Automatically add all DATs</a><br/>\n".
			"<a href='?page=import".($type != "" ? "&type=".$type : "")."'>Sort list by name</a><br/>\n".
			"<a href='?page=import&size=1".($type != "" ? "&type=".$type : "")."'>Sort list by size</a></p>\n";
	
	$files = scandir($importroot);
	if (sizeof($files) != 0)
	{
		if ($size)
		{
			usort($files, function ($a, $b)
			{
				global $importroot;
				$size_a = filesize($importroot.$a);
				$size_b = filesize($importroot.$b);
				
				return $size_a - $size_b;
			});
		}
		
		foreach ($files as $file)
		{
			if (preg_match("/^.*\.dat$/", $file) || preg_match("/^.*\.xml$/", $file))
			{
				// If we want to import everything in the folder...
				if ($auto)
				{
					import_dat($file);
					sleep(1);
					echo "<script type='text/javascript'>window.location='?page=import&auto=1".
						($size ? "&size=1" : "").
						($type != "" ? "&type=".$type : "").
						"'</script>";
				}
				else
				{
					echo "<a href=\"?page=import&filename=".$file.
						($size ? "&size=1" : "").
						($type != "" ? "&type=".$type : "").
						"\">".htmlspecialchars($file)."</a> (".filesize($importroot.$file)." bytes)<br/>\n";
				}
			}
		}
	}
}
else
{
	import_dat($_GET["filename"]);
	sleep(1);
	echo "<script type='text/javascript'>window.location='?page=import".($size ? "&size=1" : "")."'</script>";
}

function import_dat ($filename)
{
	global $link, $normalize_chars, $search_pattern, $importroot, $importdone, $type;
	
	// First, get the pattern of the file name. This is required for organization.
	switch ($type)
	{
		case "mame":
			$datpattern = "/^(.*)\.xml$/";
			break;
		case "nointro":
			$datpattern = "/^(.*?) \((\d{8}-\d{6})_CM\)\.dat$/";
			break;
		case "redump":
			$datpattern = "/^(.*?) \((\d{8} \d{2}-\d{2}-\d{2})\)\.dat$/";
			break;
		case "tosec":
			$datpattern = "/^(.*?) - .* \(TOSEC-v(\d{4}-\d{2}-\d{2})_CM\)\.dat$/";
			break;
		case "trurip":
			$datpattern = "/^(.*?) - .* \(trurip_XML\)\.dat$/";
			break;
		default:
			$datpattern = "/^(.+?) - (.+?) \((.*) (.*)\)\.dat$/";
			break;
	}
	
	// Check the file is valid
	if (!file_exists($importroot.$filename))
	{
		echo "<b>The file you supply must be in ".$importroot."</b><br/>";
		echo "<a href='?page=import".($size ? "&size=1" : "")."'>Go back to import page</a>";
	
		return;
	}
	elseif (!preg_match($datpattern, $filename, $fileinfo))
	{
		echo "<b>DAT not in the proper pattern! (Manufacturer - SystemName (Source .*)\.dat)</b><br/>\n";
		echo "<a href='?page=import".($size ? "&size=1" : "")."'>Go back to import page</a>";
	
		return;
	}
	
	echo "<p>The file ".$filename." has a proper pattern!</p>\n";
	
	// Next, get information from the database on the current machine
	switch ($type)
	{
		case "mame":
			preg_match("/^(.*) - (.*)$/", $mapping_mame[$fileinfo[1]], $name);
			$manufacturer = $name[1];
			$system = $name[2];
			$source = "MAME";
			$datestring = filemtime($importroot.$filename);
			$date = date("Y-m-d G:i:s", $datestring);
			break;
		case "nointro":
			preg_match("/^(.*) - (.*)$/", $mapping_nointro[$fileinfo[1]], $name);
			$manufacturer = $name[1];
			$system = $name[2];
			$source = "no-Intro";
			$datestring = $fileinfo[2];
			preg_match("/(\d{4})(\d{2})(\d{2})-(\d{2})(\d{2})(\d{2})/", $datestring, $date);
			$date = $date[1]."-".$date[2]."-".$date[3]." ".$date[4].":".$date[5].":".$date[6];
			break;
		case "redump":
			preg_match("/^(.*) - (.*)$/", $mapping_redump[$fileinfo[1]], $name);
			$manufacturer = $name[1];
			$system = $name[2];
			$source = "Redump";
			$datestring = $fileinfo[2];
			preg_match("/(\d{4})(\d{2})(\d{2}) (\d{2})-(\d{2})-(\d{2})/", $datestring, $date);
			$date = $date[1]."-".$date[2]."-".$date[3]." ".$date[4].":".$date[5].":".$date[6];
			break;
		case "tosec":
			preg_match("/^(.*) - (.*)$/", $mapping_tosec[$fileinfo[1]], $name);
			$manufacturer = $name[1];
			$system = $name[2];
			$source = "TOSEC";
			$datestring = $fileinfo[2];
			preg_match("/(\d{4})-(\d{2})-(\d{2}))/", $datestring, $date);
			$date = $date[1]."-".$date[2]."-".$date[3]." 00:00:00";
			break;
		case "trurip":
			preg_match("/^(.*) - (.*)$/", $mapping_trurip[$fileinfo[1]], $name);
			$manufacturer = $name[1];
			$system = $name[2];
			$source = "trurip";
			$datestring = filemtime($importroot.$filename);
			$date = date("Y-m-d G:i:s", $datestring);
			break;
		default:
			$manufacturer = $fileinfo[1];
			$system = $fileinfo[2];
			$source = $fileinfo[3];
			$datestring = $fileinfo[4];
			preg_match("/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/", $datestring, $date);
			$date = $date[1]."-".$date[2]."-".$date[3]." ".$date[4].":".$date[5].":".$date[6];
			break;
	}
	
	$query = "SELECT id
		FROM systems
		WHERE manufacturer='$manufacturer'
			AND system='$system'";
	$result = mysqli_query($link, $query);
	
	if (!gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
	{
		echo('Error: No suitable system found! Please add the system and then try again<br/>');
		return;
	}
	
	$sysid = mysqli_fetch_assoc($result);
	$sysid = $sysid["id"];
	
	if ($sourceid == "")
	{
		$query = "SELECT id
			FROM sources
			WHERE name='".$source."'";
		$result = mysqli_query($link, $query);
		
		if (!gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
		{
			echo('Error: No suitable source found! Please add the source and then try again<br/>');
			return;
		}
		
		$sourceid = mysqli_fetch_assoc($result);
		$sourceid = $sourceid["id"];
	}
	
	// Then, parse the file and read in the information. Echo it out for safekeeping for now.
	$handle = fopen($importroot.$filename, "r");
	if ($handle)
	{
		$format = "";
		$machinefound = false;
		$machinename = "";
		$description = "";
		$gameid = 0;
		$comment = false;
		
		echo "<h3>Roms Added:</h3>
	<table border='1'>
		<tr><th>Machine</th><th>Rom</th><th>Size</th><th>CRC32</th><th>MD5</th><th>SHA1</th></tr>\n";
		while (($line = fgets($handle)) !== false)
		{
			// If a machine or game tag is found, check to see if it's in the database
			// If it's not, add it to the database and then save the gameID
			
			// Normalize the whole line, just in case
			$line = strtr($line, $normalize_chars);
			
			// If the input style hasn't been set, set it according to the header
			if ($format == "")
			{
				if (strpos($line, "<!DOCTYPE datafile") !== false)
				{
					$format = "logiqx";
				}
				elseif (strpos($line, "<!DOCTYPE softwarelist") !== false)
				{
					$format = "softwarelist";
				}
				elseif (strpos($line, "clrmamepro (") !== false || strpos($line, "romvault (") !== false)
				{
					$format = "romvault";
				}
				else
				{
					$format = "unknown";
				}
			}
			
			// If there's an XML-style comment, stop the presses and skip until it's over
			elseif (strpos($line, "-->") !== false)
			{
				$comment = false;
			}
			elseif (strpos($line, "<!--") !== false)
			{
				$comment = true;
			}
			
			// Process Logiqx XML-derived DATs
			elseif ($format == "logiqx" && !$comment)
			{
				if ((strpos($line, "<machine") !== false || strpos($line, "<game") !== false))
				{
					$machinefound = true;
					$xml = simplexml_load_string($line.(strpos($line, "<machine")?"</machine>":"</game>"));
					$machinename = $xml->attributes()["name"];
					$gameid = add_game($sysid, $machinename, $sourceid);
				}
				elseif (strpos($line, "<rom") !== false && $machinefound)
				{
					add_rom($line, $machinename, "rom", $gameid, $date);
				}
				elseif (strpos($line, "<disk") !== false && $machinefound)
				{
					add_rom($line, $machinename, "disk", $gameid, $date);
				}
				elseif ((strpos($line, "</machine>") !== false || strpos($line, "</game>") !== false))
				{
					$machinefound = false;
					$machinename = "";
					$description = "";
					$gameid = 0;
				}
			}
			
			// Process SoftwareList XML-derived DATs
			elseif ($format == "softwarelist" && !$comment)
			{
				if (strpos($line, "<software") !== false)
				{
					$machinefound = true;
					$xml = simplexml_load_string($line."</software>");
					$machinename = $xml->attributes()["name"];
					$gameid = add_game($sysid, $machinename, $sourceid);
				}
				elseif (strpos($line, "<rom") !== false)
				{
					add_rom($line, $machinename, "rom", $gameid, $date);
				}
				elseif (strpos($line, "<disk") !== false)
				{
					add_rom($line, $machinename, "disk", $gameid, $date);
				}
				elseif (strpos($line, "</software>") !== false)
				{
					$machinefound = false;
					$machinename = "";
					$description = "";
					$gameid = 0;
				}
			}
			
			// Process original style RomVault DATs
			elseif ($format == "romvault")
			{
				if (strpos($line, "game") !== false && !$machinefound)
				{
					$machinefound = true;
				}
				elseif (strpos($line, "rom (") !== false && $machinefound)
				{
					add_rom_old($line, $machinename, "rom", $gameid, $date);
				}
				elseif (strpos($line, "disk (") !== false && $machinefound)
				{
					add_rom_old($line, $machinename, "disk", $gameid, $date);
				}
				elseif (strpos($line, "name \"") !== false && $machinefound)
				{
					preg_match("/^\s*name \"(.*)\"/", $line, $machinename);
					$machinename = $machinename[1];
					$gameid = add_game($sysid, $machinename, $sourceid);
				}
				elseif (strpos($line, ")") !== false)
				{
					$machinefound = false;
					$machinename = "";
					$description = "";
					$gameid = 0;
				}
			}
		}
		echo "</table><br/>\n";
		
		fclose($handle);
		rename($importroot.$filename, $importdone.$filename);
		
		return;
	}
	else
	{
		echo("Could not open file ".$filename."<br/>");
		return;
	}
}

function add_game ($sysid, $machinename, $sourceid)
{
	global $link, $normalize_chars, $search_pattern;
	
	// WoD gets rid of anything past the first "(" or "[" as the name, we will do the same
	preg_match("/(([[(].*[\)\]] )?([^([]+))/", $machinename, $machinename);
	$machinename = $machinename[1];
	
	// Run the name through the filters to make sure that it's correct
	$machinename = strtr($machinename, $normalize_chars);
	$machinename = ru2lat($machinename);
	$machinename = preg_replace($search_pattern["EXT"], $search_pattern["REP"], $machinename);
	$machinename = trim($machinename);
	
	// This is an issue, apparently
	if ($machinename == "")
	{
		echo "</table><br/>\n";
		die("Machinename is blank!");
	}
	
	$query = "SELECT id
			FROM games
			WHERE system=".$sysid."
			AND name='".htmlspecialchars($machinename)."'
			AND source=".$sourceid;
	
	$result = mysqli_query($link, $query);
	if (gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
	{
		$query = "INSERT INTO games (system, name, source)
					VALUES (".$sysid.", '".htmlspecialchars($machinename)."', ".$sourceid.")";		
		$result = mysqli_query($link, $query);
		$gameid = mysqli_insert_id($link);
	}
	else
	{
		$gameid = mysqli_fetch_assoc($result);
		$gameid = $gameid["id"];
	}
	
	return $gameid;
}

function add_rom ($line, $machinename, $romtype, $gameid, $date)
{
	$xml = simplexml_load_string($line);
	add_rom_helper($machinename, $romtype, $gameid, $xml->attributes()["name"], $date, 
			$xml->attributes()["size"], $xml->attributes()["crc"], $xml->attributes()["md5"],
			$xml->attributes()["sha1"]);
}
	
function add_rom_old ($line, $machinename, $romtype, $gameid, $date)
{
	preg_match("/name \"(.*)\"/", $line, $name);
	
	$name = $name[1];
	$rominfo = explode(" ", $line);
	$size = ""; $crc = ""; $md5 = ""; $sha1 = ""; 
	
	$next = "";
	foreach ($rominfo as $info)
	{
		if ($info == "size" || $info == "crc" || $info == "md5" || $info == "sha1")
		{
			$next = $info;
		}
		elseif ($next != "")
		{
			switch ($next)
			{
				case "size": $size = $info; break;
				case "crc": $crc = $info; break;
				case "md5": $md5 = $info; break;
				case "sha1": $sha1 = $info; break;
				default: break;
			}
			$next = "";
		}
	}
	
	add_rom_helper($machinename, $romtype, $gameid, $name, $date, $size, $crc, $md5, $sha1);
}
	
function add_rom_helper ($machinename, $romtype, $gameid, $name, $date, $size, $crc, $md5, $sha1)
{
	global $link, $normalize_chars, $search_pattern;
	
	// Run the name through the filters to make sure that it's correct
	$name = strtr($name, $normalize_chars);
	$name = ru2lat($name);
	$name = str_replace($search_pattern["EXT"], $search_pattern["REP"], $name);
	
	// WOD origninally stripped out any subdirs from the imported files, we do the same
	$name = explode("\\", $name);
	$name = $name[sizeof($name) - 1];
	
	if ($romtype != "rom" && $romtype != "disk")
	{
		$romtype = "rom";
	}
	
	// Check for the existance of the rom in the given system and game
	// If it doesn't exist, create the rom with the information provided	
	$query = "SELECT files.id
	FROM files
	JOIN checksums
	ON files.id=checksums.file
	WHERE files.name='".addslashes($name)."' ".
		"AND files.type='".$romtype."' ".
		"AND files.setid=".$gameid." ".
		($size != "" ? " AND checksums.size=".$size : "").
		($crc != "" ? " AND checksums.crc='".$crc."'" : "").
		($md5 != "" ? " AND checksums.md5='".$md5."'" : "").
		($sha1 != "" ? " AND checksums.sha1='".$sha1."'" : "");
	
	$result = mysqli_query($link, $query);
	if (gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
	{
		$query = "INSERT INTO files (setid, name, type, lastupdated)
		VALUES (".$gameid.",
		'".addslashes($name)."',
		'".$romtype."',
		'".$date."')";
		
		$result = mysqli_query($link, $query);

		if (gettype($result) == "boolean" && $result)
		{
			$romid = mysqli_insert_id($link);

			$query = "INSERT INTO checksums (file".
						($size != "" ? ", size" : "").
						($crc != "" ? ", crc" : "").
						($md5!= "" ? ", md5" : "").
						($sha1 != "" ? ", sha1" : "").
					")
					VALUES (".$romid.
						($size != "" ? ", ".$size : "").
						($crc != "" ? ", '".$crc."'" : "").
						($md5 != "" ? ", '".$md5."'" : "").
						($sha1 != "" ? ", '".$sha1."'" : "").
					")";
			
			$result = mysqli_query($link, $query);

			if (gettype($result)=="boolean" && $result)
			{
				echo "<tr><td>".$machinename."</td><td>".$name."</td><td>".$size."</td><td>".$crc."</td><td>".$md5."</td><td>".$sha1."</td></tr>\n";
			}
			else
			{
				echo("MYSQL Error! ".mysqli_error($link)."<br/>");
				return;
			}
		}
		else
		{
			echo("MYSQL Error! ".mysqli_error($link)."<br/>");
			return;
		}
	}
}
	
?>