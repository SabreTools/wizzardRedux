<?php

/*
TODO: Write checker for this site: http://www.openhandhelds.org/index.php?
TODO: Add source to the sources table (web/desktop)
TODO: Add new systems for ones not already included (All except Pandora, GP32?)
TODO: Add read/write from associated txt file
TODO: Study page layout to make sure that all files are found and catalogued.
TODO: Be careful of how the URLs are made (mostly 0,0,0,0 etc)
*/

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
foreach ($pages as $page)
{
	// Process the current page
	$query = get_data($page);
	
	// Process pages in filtered subcategories
	foreach ($categories as $cat)
	{
		parse($page, "?0,0,0,0,".$cat);
	}
}

function parse($page, $cat)
{
	$query = get_data($page.$cat);
	
	/*
	Look at example page: http://dl.openhandhelds.org/cgi-bin/dingoo.cgi?0,0,0,0,6,619
		for how a file page is laid out
		Name: (blah) is the filename that is downloaded
	*/
	
	// Everything before this is header and last updated files
	$query = explode("<TD>&nbsp;<FONT CLASS=\"mid\"><B>Browse</B></FONT></TD>", $query);
	$query = $query[1];
	
	// Everything after this is the most downloaded and footer
	$query = explode("<TD ALIGN=\"RIGHT\"><FONT CLASS=\"small\">Downloads from this category", $query);
	$query = $query[0];
	
	// Now separate into category and download pages
	$query = explode("<TD WIDTH=\"100\"><FONT CLASS=\"mid\"><B>&nbsp;Screenshot</B></FONT></TD>", $query);
	$categories = $query[0];
	$downloads = $query[1];
	
	// Set found variables
	$new = 0;
	$old = 0;
	
	// Get and parse all downloads on the page
	preg_match_all("/HREF='\?0,0,0,0,".$cat.",(.*?)'/", $downloads, $downloads);
	$downloads = $downloads[1];
	foreach ($downloads as $down)
	{
		$dquery = get_data($page.$cat.",".$down);
		
		var_dump($page.$cat.",".$down, $dquery);
		die();
	}
	
	// Get and parse all new categories 
	preg_match_all("/onclick=\"window\.location\.href='\?0,0,0,0,(.*?)'/", $categories, $categories);
	$categories = $categories[1];
	foreach ($categories as $catb)
	{
		parse($page, "?0,0,0,0,".$catb);
	}
}

?>