<?php

/* ------------------------------------------------------------------------------------
Internal test to see if the No-Intro pages can be traversed reasonably
Original code by Matt Nadareski (darksabre76)

Note: Because it needs to include sleep(5), this will always take
a couple of hours. It's unfortunate, but necessary because of limitations
that No-Intro puts on a given IP
Note: This still times out every once in a while. Only about half of the
pages are parsed correctly. If it reaches an error page, have it wait and
try again?
------------------------------------------------------------------------------------ */

ini_set('max_execution_time', -1); // Set the execution time higher because DATs can be big

// Connect to the database so it doesn't have to be done in every page
$link = mysqli_connect('localhost', 'root', '', 'scene');
if (!$link)
{
	die('Error: Could not connect: ' . mysqli_error($link));
}

$system = (isset($_GET["system"]) ? $_GET["system"] : "28");
$start = (isset($_GET["start"]) ? $_GET["start"] : 0);

// System ID to Name mapping
$systems = array(
	"28" => "Nintendo DS",
	"54" => "Nintendo DSi",
	"53" => "Nintendo DSi (DLC)",
);

// Copy these from generate.php
$version = date("YmdHis");
$datname = $systems[$system].' Scene Releases ('.$version.')';
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
			<clrmamepro/>
		</header>\n";
$footer = "</datafile>";

$vals = array();

// Populate vals
if ($system == "28")
{
	for ($i = 0; $i <= 6603; $i++)
	{
		$vals[] = str_pad($i, 4, "0", STR_PAD_LEFT);
	}
	for ($i = 1; $i <= 165; $i++)
	{
		$vals[] = "z".str_pad($i, 3, "0", STR_PAD_LEFT);
	}
	for ($i = 1; $i <= 3; $i++)
	{
		$vals[] = "xB".str_pad($i, 2, "0", STR_PAD_LEFT);
	}
	for ($i = 1; $i <= 197; $i++)
	{
		$vals[] = "x".str_pad($i, 3, "0", STR_PAD_LEFT);
	}
}
elseif ($system == "54")
{
	for ($i = 1; $i <= 9; $i++)
	{
		$vals[] = str_pad($i, 4, "0", STR_PAD_LEFT);
	}
	for ($i = 1; $i <= 1; $i++)
	{
		$vals[] = "z".str_pad($i, 3, "0", STR_PAD_LEFT);
	}
}
elseif ($system == "53")
{
	for ($i = 1; $i <= 393; $i++)
	{
		$vals[] = str_pad($i, 4, "0", STR_PAD_LEFT);
	}
}

for ($i = $start; $i < $start + 10 || $i < sizeof($vals); $i++)
{
	$id = $vals[$i];
	
	echo ("Retrieving file information for ".$id."<br/>\n");
	$filename = "http://datomatic.no-intro.org/index.php?page=show_record&s=".$system."&n=".$id;
	$query = implode("", file($filename));
	
	// If we're in an error state, wait a minute...
	while (strpos($query, "I am too busy for this") === FALSE)
	{
		echo "\tError page found, waiting 60 seconds<br/>\n";
		sleep(60);
		$query = implode("", file($filename));
	}
	
	// Get leading edge of the scene releases
	$query = explode("Scene releases", $query);
	if (!isset($query[1]))
	{
		echo "\tNo scene information found<br/>\n";
	}
	else
	{
		$query = $query[1];
		
		// Get trailing edge of the scene releases
		$query = explode("</article>", $query);
		$query = $query[0];
		
		// Now all that's left are the scene releases
		// Let's replace all of the obnoxious spaces with single ones
		$query = preg_replace("/\s+/", " ", $query);
		
		// Now all of the spaces should be fixed.
		// Let's separate it by "Directory"
		$query = explode("Directory:", $query);
		unset($query[0]);
		
		// Now there are little chunks of page that contain the directory
		$xmlr = new XMLReader;
		foreach ($query as $release)
		{
			$data = array();
			
			$enddiv = strpos($release, "</div>");
			$xmlr->XML(($enddiv !== false ? "<div>" : "")."<table><tr><td>".$release.($enddiv === false ? "</td></tr></table>" : ""));
			
			// Just in case, read to the first <td>
			while($xmlr->read() && $xmlr->name !== "td");
			
			// Skip the first; it's blank
			$xmlr->next("td");
			
			// Get the directory name
			$name = trim($xmlr->readString());
			$data[] = "Directory: ".$name;
			
			// Go the next row
			$xmlr->next("tr");
			
			// Get whatever else comes out
			while($xmlr->name === "tr")
			{
				if ($xmlr->readString() !== "")
				{
					$data[] = preg_replace("/\s+/", " ", trim($xmlr->readString()));
				}
				$xmlr->next("tr");
			}
			
			// Extract the useful bits
			proc_data($data, $system);
		}
	}
	
	ob_flush();
	flush();
	
	// Don't anger the No-Intro admins..
	sleep(10);
}

echo "<a href='?page=parsenointro&system=".$system."&start=".($start+10)."'>Next</a><p/>\n";

/*
// Make the page ready for output
ob_end_clean();
header('content-type: application/x-gzip');
header('Content-Disposition: attachment; filename="'.$datname.'.xml.gz"');
echo gzencode($header, 9);
// Now output the roms
foreach ($roms as &$rom)
{
	// Check for blank CRC and MD5
	$rom[2] = ($rom[2] == "0" ? "00000000" : strtolower($rom[2]));
	$rom[3] = ($rom[3] == "0" ? "d41d8cd98f00b204e9800998ecf8427e" : strtolower($rom[3]));
	
	$state = "\t<machine name=\"".$rom[0]."\">\n".
			"\t\t<description>".$rom[0]."</description>\n".
			"\t\t<rom name=\"".$rom[1].".nds\" crc=\"".$rom[2]."\" md5=\"".$rom[3]."\" />\n".
			"\t</machine>\n";
	
	echo gzencode($state, 9);
}
echo gzencode($footer, 9);
die();
*/

function proc_data($data, $system)
{
	GLOBAL $roms, $link;
	
	$directory = "";
	$name = "";
	$released = "";
	$crc = "";
	$md5 = "";
	
	foreach ($data as $datum)
	{
		// Directory name
		if (preg_match("/Directory: (.*)/", $datum, $point))
		{
			$directory = $point[1];
		}
		// NFO File
		elseif (preg_match("/NFO File: (.*)/", $datum, $point))
		{
			$name = $point[1];
		}
		// Released
		elseif (preg_match("/Released: (.*)/", $datum, $point))
		{
			$released = $point[1];
		}
		// Decrypted CRC32
		elseif (preg_match("/.*CRC32: (.*)/", $datum, $point))
		{
			$crc = $point[1];
		}
		// Decrypted MD5
		elseif (preg_match("/.*MD5: (.*)/", $datum, $point))
		{
			$md5 = $point[1];
		}
	}
	
	// Check if it's in the database. If it's not, add it
	$query = "SELECT id FROM releases
WHERE game='".$directory."'
	AND system=".$system."
	AND name='".$name."'
	AND released='".$released."'
	AND crc='".$crc."'
	AND md5='".$md5."'";
	$result = mysqli_query($link, $query);
	
	if (gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
	{
		$query = "INSERT INTO releases (game, system, name, released, crc, md5)
	VALUES ('".$directory."', ".$system.", '".$name."', '".$released."', '".$crc."', '".$md5."'";
		$result = mysqli_query($link, $query);
		if (gettype($result) == "boolean" && $result)
		{
			echo "\tRelease ".$directory." has been added<br/>\n";
		}
		else
		{
			echo "\tRelease ".$directory." could not be added<br/>\n";
		}
	}
	else
	{
		echo "\tRelease ".$directory." already exists<br/>\n";
	}
}

?>