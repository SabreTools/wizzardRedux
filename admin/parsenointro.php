<?php

/* ------------------------------------------------------------------------------------
Internal test to see if the No-Intro pages can be traversed reasonably
Original code by Matt Nadareski (darksabre76)
------------------------------------------------------------------------------------ */

ini_set('max_execution_time', 6000); // Set the execution time higher because DATs can be big

// Game, Name, CRC, MD5
$roms = array();
$vals = array();

// Populate vals
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

foreach ($vals as $id)
{
	$filename = "http://datomatic.no-intro.org/index.php?page=show_record&s=28&n=".$id;
	$query = implode("", file($filename));
	
	// Get leading edge of the scene releases
	$query = explode("Scene releases", $query);
	if (isset($query[1]))
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
			proc_data($data);
		}
	}
	
	sleep(5);
	
	if ($id == "0005")
	{
		break;
	}
}

var_dump($roms);

function proc_data($data)
{
	GLOBAL $roms;
	
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
	
	$roms[] = array($released."_".$directory, $name, $crc, $md5);
}

?>