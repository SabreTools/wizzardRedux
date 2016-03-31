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
		$query = get_data($page."?0,0,0,0,".$cat);
	}
}

function parse($page)
{
	$query = get_data($page);
	
	/*
	 Here are things to look for:
	 	All categories are after:
	 		<TD>&nbsp;<FONT CLASS="mid"><B>Browse</B></FONT></TD>
	 	And before:
	 		<TD WIDTH="100"><FONT CLASS="mid"><B>&nbsp;Screenshot</B></FONT></TD>
	 	After which is any files until
	 		<TD ALIGN="RIGHT"><FONT CLASS="small">Downloads from this category
		
		All categories are of the form "?0,0,0,0,x"
		All files are of the form "?0,0,0,0,x,y" where x is the category
		
		Look at example page: http://dl.openhandhelds.org/cgi-bin/dingoo.cgi?0,0,0,0,6,619
			for how a file page is laid out
			Name: (blah) is the filename that is downloaded
	*/
}

?>