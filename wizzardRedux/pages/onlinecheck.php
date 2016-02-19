<?php

/*
Check for new downloadable ROMs from all available sites

Requires:
	source		The sourcename to check against

Note: This is the most tedious one of all. All of the checks should be named as "sites/<sitename>.php".

If the page is sent with no param, generate a list of all possible checks. Each check has a
<sitename>.txt file next to it that designates what files have already been found. Use
the existing <sitename>/onlinecheck.php files for either reference or wholesale repurposing.

TODO: Retool existing onlinecheck.php files to follow the new format
TODO: Add a way to figure out if a site is dead based on the original list that WoD created
TODO: Figure out more todos
*/

if (!isset($_GET["source"]))
{
	// List all files, auto-generate links to proper pages
	$files = scandir("sites/");
	foreach ($files as $file)
	{
		if (preg_match("/^.*\.php$/", $file))
		{
			echo "<a href=\"?page=onlinecheck&source=".chop($file, ".php")."&debug=1\">".htmlspecialchars($file)."</a><br/>";
		}
	}

	echo "<br/><a href='".$path_to_root."/index.php'>Return to home</a>";

	die();
}
elseif (!file_exists("sites/".$_GET["source"].".php"))
{
	echo "<b>The file you supply must be in /wod/sites/</b><br/>";
	echo "<a href='index.php'>Return to home</a>";

	die();
}

// If we get to this point, we assume that it's good
include_once("sites/".$_GET["source"].".php");

?>