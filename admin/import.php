<?php

/* ------------------------------------------------------------------------------------
Import an existing DAT into the system
Original code by Matt Nadareski (darksabre76)

Requires:
	filename	File name in one of a few formats, see $datpattern
	size		Sort the list by size of the DAT file (handy for multiple imports)

TODO: Auto-generate DATs affected by import (merged and custom)?
TODO: Figure out if some systems need to have their data removed before importing again
		e.g. TOSEC, Redump, TruRip
TODO: RomCenter format? http://www.logiqx.com/DatFAQs/RomCenter.php
	Seems based on INI format; see PHP reference http://php.net/manual/en/function.parse-ini-file.php
TODO: Check import and parsing of No-Intro DATs; don't seem to be working
------------------------------------------------------------------------------------ */

echo "<h2>Import From Datfile</h2>";

ini_set('max_execution_time', 0); // Set the execution time to infinite. This is a bad idea in production.

// Verify GET variables
$auto = isset($_GET["auto"]) && $_GET["auto"] == "1";
$size = isset($_GET["size"]) && $_GET["size"] == "1";

// Set import path
$importroot = "../temp/import/";

if (!isset($_GET["filename"]))
{
	// List all files, auto-generate links to proper pages
	echo "<p><a href='?page=import&auto=1".($size ? "&size=1" : "")."'>Automatically add all DATs</a><br/>\n".
			"<a href='?page=import'>Sort list by name</a><br/>\n".
			"<a href='?page=import&size=1'>Sort list by size</a></p>\n";
	
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
						"'</script>";
				}
				else
				{
					echo "<a href=\"?page=import&filename=".$file.
						($size ? "&size=1" : "").
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
	global $link, $normalize_chars, $search_pattern, $importroot,
		$mapping_mame, $mapping_nointro, $mapping_redump, $mapping_tosec, $mapping_trurip;
	
	// Check the file is valid
	if (!file_exists($importroot.$filename))
	{
		echo "<b>The file you supply must be in ".$importroot."</b><br/>";
		echo "<a href='?page=import".($size ? "&size=1" : "")."'>Go back to import page</a>";
	
		return;
	}
	
	// Then determine the type of the DAT
	$type = "";
	if (preg_match("/^(.*)\.xml$/", $filename, $fileinfo))
	{
		$type = "mame";
	}
	elseif (preg_match("/^(.*?) \((\d{8}-\d{6})_CM\)\.dat$/", $filename, $fileinfo))
	{
		$type = "nointro";
	}
	elseif (preg_match("/^(.*?) \((\d{8} \d{2}-\d{2}-\d{2})\)\.dat$/", $filename, $fileinfo))
	{
		$type = "redump";
	}
	elseif (preg_match("/^(.*?) - .* \(TOSEC-v(\d{4}-\d{2}-\d{2})_CM\)\.dat$/", $filename, $fileinfo))
	{
		$type = "tosec";
	}
	elseif (preg_match("/^(.*?) - .* \(trurip_XML\)\.dat$/", $filename, $fileinfo))
	{
		$type = "trurip";
	}
	elseif (preg_match("/^(.+?) - (.+?) \((.*) (.*)\)\.dat$/", $filename, $fileinfo))
	{
		$type = "custom";
	}
	else
	{
		echo "<b>DAT type could not be determined from file name!</b><br/>\n";
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
			// If it's a special case, try to see if it's one of the odd TOSEC's
			if (!isset($mapping_tosec[$fileinfo[1]]))
			{
				preg_match("/^(.*? - .*? - .*?) - .* \(TOSEC-v(\d{4}-\d{2}-\d{2})_CM\)\.dat$/", $filename, $fileinfo);
			}
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
		case "custom":
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
				if (strpos($line, "<?xml version=\"1.0\" encoding=\"utf-8\"?>") !== false)
				{
					$format = "logiqx";
				}
				elseif (strpos($line, "clrmamepro (") !== false || strpos($line, "romvault (") !== false)
				{
					$format = "romvault";
				}
			}
			elseif (strpos($line, "<!DOCTYPE softwarelist") !== false)
			{
				$format = "softwarelist";
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
				if (strpos($line, "<software ") !== false)
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
				if (strpos($line, "game (") !== false && !$machinefound)
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
				elseif (strpos($line, "description \"") !== false && $machinefound)
				{
					// Placeholder unused
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
		die();
		fclose($handle);
		
		// Add the imported file to the zip and delete
		$extfilename = $importroot.$filename;
		$zip = new ZipArchive();
		$zip->open("../temp/imported".($type != "" ? "-".$type : "").".zip", ZIPARCHIVE::CREATE);
		$zip->addFile($extfilename, $filename);
		$zip->close();
		unlink($extfilename);
		
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
			AND name='".addslashes($machinename)."'
			AND source=".$sourceid;
	
	$result = mysqli_query($link, $query);
	if (gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
	{
		$query = "INSERT INTO games (system, name, source)
					VALUES (".$sysid.", '".addslashes($machinename)."', ".$sourceid.")";		
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
						($crc != "" ? ", '".strtolower($crc)."'" : "").
						($md5 != "" ? ", '".strtolower($md5)."'" : "").
						($sha1 != "" ? ", '".strtolower($sha1)."'" : "").
					")";
			
			$result = mysqli_query($link, $query);

			if (gettype($result)=="boolean" && $result)
			{
				echo "<tr><td>".$machinename."</td><td>".$name."</td><td>".$size."</td><td>".$crc."</td><td>".$md5."</td><td>".$sha1."</td></tr>\n";
				ob_flush(); flush();
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