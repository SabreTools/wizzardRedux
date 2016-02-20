<?php

/* ------------------------------------------------------------------------------------
Import an existing DAT into the system

Requires:
	filename	File name in the format of "Manufacturer - SystemName (Source .*)\.dat"

TODO: Auto-generate DATs affected by import (merged and custom)?
TODO: Add lastupdated when import is finished? 
TODO: Change lastupdated from sources.lastupdated to games.lastupdated?
------------------------------------------------------------------------------------ */

echo "<h2>Import From Datfile</h2>";

ini_set('max_execution_time', 3000); // Set the execution time higher because DATs can be big

// First, get the pattern of the file name. This is required for organization.
$datpattern = "/^(.+?) - (.+?) \((\S+) .*\)\.dat$/";

if (!isset($_GET["filename"]))
{
	// List all files, auto-generate links to proper pages
	$files = scandir("temp/");
	foreach ($files as $file)
	{
		if (preg_match("/^.*\.dat$/", $file))
		{
			echo "<a href=\"?page=import&filename=".$file."&debug=1\">".htmlspecialchars($file)."</a><br/>";
		}
	}
	
	echo "<br/><a href=\"?page=\">Return to home</a>";
	
	die();
}
elseif (!file_exists("temp/".$_GET["filename"]))
{
	echo "<b>The file you supply must be in /wod/temp/</b><br/>";
	echo "<a href='index.php'>Return to home</a>";
	
	die();
}
elseif (!preg_match($datpattern, $_GET["filename"]))
{
	echo "<b>DAT not in the proper pattern! (Manufacturer - SystemName (Source .*)\.dat)</b><br/>";
	echo "<a href='index.php'>Return to home</a>";
	
	die();
}

echo "<p>The file ".$_GET["filename"]." has a proper pattern!</p>";

// Next, get information from the database on the current machine
$fileinfo = explode(" - ", $_GET["filename"]);
$manufacturer = $fileinfo[0];
$fileinfo = explode(" (", $fileinfo[1]);
$system = $fileinfo[0];
$source = explode(" ", $fileinfo[1])[0];

$link = mysqli_connect('localhost', 'root', '', 'wod');
if (!$link)
{
	die('Error: Could not connect: ' . mysqli_error($link));
}

if ($debug)
{
	echo "Connection established!<br/>";
}

$query = "SELECT id
	FROM systems
	WHERE manufacturer='$manufacturer'
		AND system='$system'";
$result = mysqli_query($link, $query);

if (!gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
{
	die('Error: No suitable system found! Please add the system and then try again');
}

$sysid = mysqli_fetch_assoc($result);
$sysid = $sysid["id"];

$query = "SELECT id
	FROM sources
	WHERE name='".$source."'";
$result = mysqli_query($link, $query);

if (!gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
{
	die('Error: No suitable source found! Please add the source and then try again');
}

$sourceid = mysqli_fetch_assoc($result);
$sourceid = $sourceid["id"];

// Then, parse the file and read in the information. Echo it out for safekeeping for now.
$handle = fopen("temp/".$_GET["filename"], "r");
if ($handle)
{
	$old = false;
	$machinefound = false;
	$machinename = "";
	$description = "";
	$gameid = 0;
	$query = "";
	
	if ($debug)
	{
		echo "<h3>File Printout:</h3>";
	}
	while (($line = fgets($handle)) !== false)
	{
		// If a machine or game tag is found, check to see if it's in the database
		// If it's not, add it to the database and then save the gameID
		
		// Normalize the whole line, just in case
		$line = strtr($line, $normalize_chars);
		
		// This first block is for XML-derived DATs
		if ((strpos($line, "<machine") !== false || strpos($line, "<game") !== false) && !$old)
		{
			$machinefound = true;
			$xml = simplexml_load_string($line.(strpos($line, "<machine")?"</machine>":"</game>"));
			$machinename = $xml->attributes()["name"];
			$machinename = preg_replace($search_pattern['EXT'], $search_pattern['REP'], $machinename);
			$gameid = add_game($sysid, $machinename, $sourceid, $link);
		}
		elseif (strpos($line, "<rom") !== false && $machinefound && !$old)
		{
			add_rom($line, $link, "rom", $gameid);
		}
		elseif (strpos($line, "<disk") !== false && $machinefound && !$old)
		{
			add_rom($line, $link, "disk", $gameid);
		}
		elseif ((strpos($line, "</machine>") !== false || strpos($line, "</game>") !== false) && !$old)
		{			
			echo "End of machine<br/><br/>";
			
			$machinefound = false;
			$machinename = "";
			$description = "";
			$gameid = 0;
		}
		
		// This block is for the old style DATs
		if (strpos($line, "game (") !== false)
		{
			$old = true;
		}
		elseif (strpos($line, "rom (") !== false && $machinefound && $old)
		{
			add_rom_old($line, $link, "rom", $gameid);
		}
		elseif (strpos($line, "disk (") !== false && $machinefound && $old)
		{
			add_rom_old($line, $link, "disk", $gameid);
		}
		elseif (strpos($line, "name") !== false && $old)
		{
			$machinefound = true;
			preg_match("/^\s*name \"(.*)\"$/", $line, $machinename);
			$machinename = $machinename[1];
			$machinename = preg_replace($search_pattern['EXT'], $search_pattern['REP'], $machinename);
			$gameid = add_game($sysid, $machinename, $sourceid, $link);
		}
		elseif (strpos($line, ")") !== false && $old)
		{
			echo "End of machine<br/><br/>";
				
			$machinefound = false;
			$machinename = "";
			$description = "";
			$gameid = 0;
		}
		
		// Print out all lines only in debug
		elseif ($debug)
		{
			echo htmlspecialchars($line)."<br/>";
		}
	}
	echo "<br/>";
	
	fclose($handle);
}
else
{
	die("Could not open file");
}

mysqli_close($link);

function add_game ($sysid, $machinename, $sourceid, $link)
{
	// WoD gets rid of anything past the first "(" as the name, we will do the same
	$machinename = preg_replace("/^(.*?) (\(|\[).*$/", "\1", $machinename);
	
	echo "Machine: ".$machinename."<br/>";
	
	$query = "SELECT id
	FROM games
	WHERE system=".$sysid."
	AND name='".$machinename."'
	AND source=".$sourceid;
	
	$result = mysqli_query($link, $query);
	if (gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
	{
		echo "No games found by that name. Creating new game.<br/>";
	
		$query = "INSERT INTO games (system, name, source)
		VALUES (".$sysid.", '".htmlspecialchars($machinename)."', ".$sourceid.")";
		$result = mysqli_query($link, $query);
		$gameid = mysqli_insert_id($link);
	}
	else
	{
		echo "Game found!<br/>";
	
		$gameid = mysqli_fetch_assoc($result);
		$gameid = $gameid["id"];
	}
	
	return $gameid;
}

function add_rom ($line, $link, $romtype, $gameid)
{
	$xml = simplexml_load_string($line);
	add_rom_helper($link, $romtype, $gameid, $xml->attributes()["name"], $xml->attributes()["size"],
			$xml->attributes()["crc"], $xml->attributes()["md5"], $xml->attributes()["sha1"]);
}
	
function add_rom_old($line, $link, $romtype, $gameid)
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
	
	add_rom_helper($link, $romtype, $gameid, $name, $size, $crc, $md5, $sha1);
}
	
function add_rom_helper($link, $romtype, $gameid, $name, $size, $crc, $md5, $sha1)
{
	if ($romtype != "rom" && $romtype != "disk")
	{
		$romtype = "rom";
	}
	
	// Check for the existance of the rom in the given system and game
	// If it doesn't exist, create the rom with the information provided
	
	echo $tab.($romtype=="disk" ? "DiskName:" : "RomName: ").$name."<br/>".
			$tab."Size (bytes): ".$size."<br/>".
			$tab."CRC32: ".$crc."<br/>".
			$tab."MD5 Hash: ".$md5."<br/>".
			$tab."SHA1 Hash: ".$sha1."<br/><br/>";
	
	$query = "SELECT files.id
	FROM files
	JOIN checksums
	ON files.id=checksums.file
	WHERE files.name='".addslashes($name)."'
		AND files.type='".$romtype."'
		AND files.setid=".$gameid."
		AND checksums.size=".$size."
		AND checksums.crc='".$crc."'
		AND checksums.md5='".$md5."'
		AND checksums.sha1='".$sha1."'";
	$result = mysqli_query($link, $query);
	if (gettype($result)=="boolean" || mysqli_num_rows($result) == 0)
	{
		echo "ROM not found. Creating new ROM.<br/>";
		
		$query = "SELECT files.id FROM files WHERE files.name='".addslashes($name)."'";
		$result = mysqli_query($link, $query);
		
		// See if there's any ROMs with the same name. If so, add a delimiter on the end of the name.
		/// THIS HAS TO BE DONE IN GENERATE, FSCK
		//if (gettype($result) != "boolean" && mysqli_num_rows($result) > 0)
		//{
		//	$name = preg_replace("/^(.*)(\..*)/", "\1 (".
		//			($crc != "" ? $crc :
		//					($md5 != "" ? $md5 :
		//							($sha1 != "" ? $sha1 : "Alt"))).
		//			")\2", $name);
		//}

		$query = "INSERT INTO files (setid, name, type)
		VALUES (".$gameid.",
		'".addslashes($name)."',
		'".$romtype."')";
		$result = mysqli_query($link, $query);

		if (gettype($result)=="boolean" && $result)
		{
			echo "ROM created. Adding checksums<br/>";
			$romid = mysqli_insert_id($link);

			$query = "INSERT INTO checksums (file, size, crc, md5, sha1)
		VALUES (".$romid.",
				".$size.",
				'".$crc."',
				'".$md5."',
				'".$sha1."')";
			$result = mysqli_query($link, $query);

			if (gettype($result)=="boolean" && $result)
			{
				echo "Checksums added!<br/>";
			}
			else
			{
				echo "MYSQL Error! ".mysqli_error($link)."<br/>";
			}
		}
		else
		{
			echo "MYSQL Error! ".mysqli_error($link)."<br/>";
		}
	}
}
	
?>