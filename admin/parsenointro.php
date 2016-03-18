<?php

/* ------------------------------------------------------------------------------------
Internal test to see if the No-Intro pages can be traversed reasonably
Original code by Matt Nadareski (darksabre76)

Requires
	system		System ID according to the No-Intro standard
	
TODO: Match redumps as well as good and scene
TODO: Should this be for scene creation only? If so, it's as simple as commenting out one line
------------------------------------------------------------------------------------ */

ini_set('max_execution_time', 6000); // Set the execution time higher because DATs can be big

$system = (isset($_GET["system"]) ? $_GET["system"] : "");

// If the system isn't set, show the dropdown to pick one
if ($system == "")
{
	$query = get_data("http://datomatic.no-intro.org/?page=download");
	
	preg_match_all("/<b><a href=\"index\.php\?page=search&s=([0-9]+).*>(.*)<\/a><\/b>/", $query, $systems);
	
	$systemsnew = array();
	for ($index = 0; $index < sizeof($systems[0]); $index++)
	{
		$systemsnew[] = array($systems[1][$index], $systems[2][$index]);
	}
	$systems = $systemsnew;
	unset($systemsnew);
	
	echo "<h2>Select a No-Intro System</h2>
	<form action='' method='get'>
	<input type='hidden' name='page' value='parsenointro' />
	<select name='system' id='system'>";

	foreach ($systems as $system)
	{
		echo "<option value='".$system[0]."'>".$system["1"]."</option>\n";
	}
	
	echo "</select><br/><br/>
	<input type='submit'>\n</form><br/>";
}
// Try and get rom information direct from the no-intro pages. (Rollback?)
else
{
	$gameid = 1; $maxid = 10;
	$errorpage = false;
	$roms = array(); // name, size, crc, md5, sha1
	while (!$errorpage)
	{
		// Retrieve the page information
		$query = get_data("http://datomatic.no-intro.org/index.php?page=show_record&s=".$system."&n=".str_pad($gameid, 4, "0", STR_PAD_LEFT));
		
		var_dump($query);
		die();
		
		// The error page case, it means time to stop the cycle
		// This could result in too many page request too... not sure though
		if ($query == "" || strpos($query, "I am too busy for this!") || $gameid > $maxid)
		{
			$errorpage = true;
			break;
		}
		
		// Create all necessary regex patterns
		$regex_romname = "<tr class=\"romname_section\">\s+<td>\s+&nbsp;(.+?)<br \/>&nbsp;\s+<\/td>";
		$regex_romsize = "<tr>\s+<td class=\"TableTitle\" colspan=\"3\">ROM data<\/td>\s+<\/tr>\s+<tr>\s+<td.*?>Size:<\/td>\s+<td class=\"TableData\">(.*?)<\/td>\s+<\/tr>";
		$regex_crc = "<tr>\s+<td class=\"TableData\".*?>\s*CRC32:\s*<\/td>\s+<td class=\"TableData\">(.*?)<\/td>\s+<\/tr>";
		$regex_md5 = "<tr>\s+<td class=\"TableData\".*?>\s*MD5:\s*<\/td>\s+<td class=\"TableData\">(.*?)<\/td>\s+<\/tr>";
		$regex_sha1 = "<tr>\s+<td class=\"TableData\".*?>\s*SHA-1:\s*<\/td>\s+<td class=\"TableData\">(.*?)<\/td>\s+<\/tr>";
		$regex_deccrc = "<tr>\s+<td class=\"TableData\".*?>\s*Decrypted CRC32:\s*<\/td>\s+<td class=\"TableData\">(.*?)<\/td>\s+<\/tr>";
		$regex_decmd5 = "<tr>\s+<td class=\"TableData\".*?>\s*Decrypted MD5:\s*<\/td>\s+<td class=\"TableData\">(.*?)<\/td>\s+<\/tr>";
		$regex_decsha1 = "<tr>\s+<td class=\"TableData\".*?>\s*Decrypted SHA-1:\s*<\/td>\s+<td class=\"TableData\">(.*?)<\/td>\s+<\/tr>";
		$regex_enccrc = "<tr>\s+<td class=\"TableData\".*?>\s*Encrypted CRC32:\s*<\/td>\s+<td class=\"TableData\">(.*?)<\/td>\s+<\/tr>";
		$regex_encmd5 = "<tr>\s+<td class=\"TableData\".*?>\s*Encrypted MD5:\s*<\/td>\s+<td class=\"TableData\">(.*?)<\/td>\s+<\/tr>";
		$regex_encsha1 = "<tr>\s+<td class=\"TableData\".*?>\s*Encrypted SHA-1:\s*<\/td>\s+<td class=\"TableData\">(.*?)<\/td>\s+<\/tr>";
		
		// Get initial rom information
		$regex_rominfo = "/".
			"(?:".$regex_romname.")?.*?".
			"(?:".$regex_romsize.")?.*?".
			"(?:".$regex_crc.")?.*?".
			"(?:".$regex_md5.")?.*?".
			"(?:".$regex_sha1.")?.*?".
			"(?:".$regex_deccrc.")?.*?".
			"(?:".$regex_decmd5.")?.*?".
			"(?:".$regex_decsha1.")?.*?".
			//"(?:".$regex_enccrc.")?.*?".
			//"(?:".$regex_encmd5.")?.*?".
			//"(?:".$regex_encsha1.")?.*?".
		"/s";
		
		preg_match($regex_rominfo, $query, $rominfo);
		unset($rominfo[0]);
		
		var_dump($rominfo);
		die();
		
		// Strip out the extension and number
		preg_match("/\d{4} - (.*)\.(.*)/", $rominfo[1], $rominfo[1]);
		
		// Add the currently accepted rom to the array
		$roms[] = array(
				"game" => $rominfo[1][1],
				"name" => $rominfo[1][1].".".$rominfo[1][2],
				"size" => $rominfo[2],
				"crc" => ($rominfo[6] != NULL ? $rominfo[6] : $rominfo[3]),
				"md5" => ($rominfo[7] != NULL ? $rominfo[7] : $rominfo[4]),
				"sha1" => ($rominfo[8] != NULL ? $rominfo[8] : $rominfo[5])
		);
		
		// To make sure we don't match initial rom information, remove everything before Scene releases
		$query = explode("Scene releases", $query);
		
		// If there are no scene releases, go to the next page
		if ($query[1] === NULL)
		{
			continue;
		}
		$query = $query[1];
		
		// Create all necessary regex patterns
		$regex_dir = "<tr.*?>\s+<td width=\"104px\">\s+Directory:\s+<\/td>\s+<td>\s+(.*?)\s+<\/td>\s+<\/tr>";
		$regex_nfo = "<tr>\s+<td class=\"TableData\" width=\"104px\">\s+NFO File:\s+<\/td>\s+<td class=\"TableData\">\s+(.*?)\s+<\/td>\s+<\/tr>";
		$regex_group = "<tr>\s+<td class=\"TableData\" width=\"104px\">\s+Group:\s+<\/td>\s+<td class=\"TableData\">\s+(.*?)\s+<\/td>\s+<\/tr>";
		$regex_released = "<tr>\s+<td class=\"TableData\" width=\"104px\">\s+Released:\s+<\/td>\s+<td class=\"TableData\">\s+(.*?)\s+<\/td>\s+<\/tr>";
		
		$regex_sceneinfo = "/".
			$regex_dir.".*?".
			$regex_nfo.".*?".
			$regex_group.".*?".
			$regex_released.".*?".
			$regex_deccrc.".*?".
			$regex_decmd5.".*?".
		"/s";
		
		preg_match_all($regex_sceneinfo, $query, $sceneinfo);
		
		$scenenew = array();
		for ($index = 0; $index < sizeof($sceneinfo[0]); $index++)
		{
			$scenenew[] = array(
					trim($sceneinfo[1][$index]),
					trim($sceneinfo[2][$index]),
					trim($sceneinfo[3][$index]),
					trim($sceneinfo[4][$index]),
					trim($sceneinfo[5][$index]),
					trim($sceneinfo[6][$index]),
			);
		}
		$sceneinfo = $scenenew;
		unset($scenenew);
		
		// Add all of the scene roms to the array
		foreach ($sceneinfo as $scene)
		{
			$roms[] = array(
					"game" => $rominfo[1][1],
					"name" => $scene[3]."_".$scene[0].".".$rominfo[1][2],
					"size" => $rominfo[2],
					"crc" => $scene[4],
					"md5" => $scene[5],
					"sha1" => "");
		}
		
		// Increment the game pointer
		$gameid++;
		
		// Wait 5 seconds to avoid flooding the server
		sleep(5);
	}
	
	//echo "Error page hit or ran out of numbers.<br/>";
	//var_dump($roms);
	
	// Use hacked version of generate code here
	ob_end_clean();
	
	//First thing first, push the http headers
	header('content-type: application/x-gzip');
	header('Content-Disposition: attachment; filename="No-Intro.xml.gz"');
	
	$header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	<!DOCTYPE datafile PUBLIC \"-//Logiqx//DTD ROM Management Datafile//EN\" \"http://www.logiqx.com/Dats/datafile.dtd\">
	
	<datafile>
		<header>
			<name>No-Intro</name>
			<description>No-Intro</description>
			<category>The Wizard of DATz</category>
			<version></version>
			<date></date>
			<author>The Wizard of DATz</author>
			<clrmamepro/>
		</header>\n";
	
	$footer = "\n</datafile>";
	
	// Write the header out
	echo gzencode($header, 9);
	
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
		$state = $state."\t\t<rom name=\"".$rom["name"]."\"".
				($rom["size"] != "" ? " size=\"".$rom["size"]."\"" : "").
				($rom["crc"] != "" ? " crc=\"".$rom["crc"]."\"" : "").
				($rom["md5"] != "" ? " md5=\"".$rom["md5"]."\"" : "").
				($rom["sha1"] != "" ? " sha1=\"".$rom["sha1"]."\"" : "").
				" />\n";
	
		$lastgame = $rom["game"];

		echo gzencode($state, 9);
	}
	echo gzencode("\t</machine>", 9);
	echo gzencode($footer, 9);
	exit();
}

?>