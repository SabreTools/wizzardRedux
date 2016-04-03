<?php

// Original code: Matt Nadareski (darksabre76)

$pages = array(
	"http://dl.openhandhelds.org/cgi-bin/pandora.cgi", // Pandora
	"http://dl.openhandhelds.org/cgi-bin/caanoo.cgi", // Caanoo
	"http://dl.openhandhelds.org/cgi-bin/dingoo.cgi", // Dingoo
	"http://dl.openhandhelds.org/cgi-bin/wiz.cgi", // WIZ
	"http://dl.openhandhelds.org/cgi-bin/gp2x.cgi", // GP2X
	"http://dl.openhandhelds.org/cgi-bin/zodiac.cgi", // Zodiac
	"http://dl.openhandhelds.org/cgi-bin/gp32.cgi", // GP32
);

$categories = array(
	"4", // Applications
	"38", // Demos
	"13", // Development
	"5", // Emulators
	"42", // Firmwares
	"3", // Games
	
	// These categories aren't relevant to the DAT
	//"2", // Magazines
	//"50", // Skins
	//"17", // Linux
	//"16", // MacOS
	//"15", // Windows
);

// Loop through each of the main pages
echo "<table>\n";
foreach ($pages as $page)
{
	// Process the current page
	parse($page, "?0,0,0,0,1", true);
	
	// Process pages in filtered subcategories
	foreach ($categories as $cat)
	{
		parse($page, "?0,0,0,0,".$cat);
	}
}
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

function parse($page, $cat, $nocats = false)
{
	GLOBAL $found;
	
	$query = get_data($page.$cat);
	
	// Everything before this is header and last updated files
	$query = explode("<TD>&nbsp;<FONT CLASS=\"mid\"><B>Browse</B></FONT></TD>", $query);
	$query = $query[1];
	
	// Everything after this is the most downloaded and footer
	$query = explode("<TD ALIGN=\"RIGHT\"><FONT CLASS=\"small\">Downloads from this category", $query);
	$query = $query[0];
	
	// Now separate into category and download pages
	$query = explode("<TD WIDTH=\"100\"><FONT CLASS=\"mid\"><B>&nbsp;Screenshot</B></FONT></TD>", $query);
	$categories = $query[0];
	$downloads = (isset($query[1]) ? $query[1] : "");
	
	// Set found variables
	$new = 0;
	$old = 0;
	
	// Get and parse all downloads on the page
	preg_match_all("/HREF='\?0,0,0,0,(\d+),(\d+)'/", $downloads, $downloads);
	$newdowns = array();
	for ($index = 0; $index < sizeof($downloads[0]); $index++)
	{
		$newdowns[] = array($downloads[1][$index], $downloads[2][$index]);
	}
	$downloads = $newdowns;
	unset($newdowns);
	
	foreach ($downloads as $down)
	{
		$dquery = get_data($page.$cat.",".$down[1]);
		
		// Get the name of the program
		preg_match("/<TD VALIGN=\"TOP\" WIDTH=\"40%\"><FONT CLASS=\"big\">&nbsp;<B>(.*?)<\/B>/", $dquery, $name);
		$name = $name[1];
		
		// Download ID is always static
		$dl_id = "?0,1,0.0,".$down[0].",".$down[1];
		
		if (!isset($r_query[$dl_id]))
		{
			preg_match("/http:\/\/dl\.openhandhelds\.org\/cgi-bin\/(.*?)\.cgi/", $page, $system);
			$system = $system[1];
			
			$found[] = array("(".$system.") ".$name, $page.$dl_id);
			$r_query[$dl_id] = true;
			$new++;
		}
		else
		{
			$old++;
		}
	}
	
	// Get and parse all new categories if we are told to
	if (!$nocats)
	{
		preg_match_all("/onclick=\"window\.location\.href='\?0,0,0,0,(.*?)'/", $categories, $categories);
		$categories = $categories[1];
		foreach ($categories as $catb)
		{
			parse($page, "?0,0,0,0,".$catb);
		}
	}
	
	// Output the newly found information
	echo "<tr><td>".$page.$cat."</td><td>Found new: ".$new.", old: ".$old."</tr>\n";
}

?>