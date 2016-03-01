<?php

// Original code by Matt Nadareski (darksabre76)
// Based on original code by The Wizard of DATz

$baseURL = "http://www.ntscene.com";
$new = 0;
$old = 0;
$founddirs = array();

print "<pre>";

$newfiles = array(
		$baseURL."/archive/",
);

foreach ($newfiles as $newfile)
{
	load_dir($newfile);
}

foreach ($found as $new)
{
	print "<a href='".$new[0]."'>".$new[1]."</a>\n";
}

function load_dir($dir)
{
	GLOBAL $baseURL, $found, $founddirs, $new, $old;
	
	print "load ".$dir."\n";
	$query = get_data($dir);
	
	// Get all directory links from the page
	preg_match_all("/href=\"(\/archive\/index.php\?dir=[^\"\']+\/)\">/", $query, $matches);
	
	foreach ($matches[1] as $subdir)
	{
		if (!$founddirs[$subdir])
		{
			print "found dir: ".$subdir."\n";
			$founddirs[$subdir] = true;
			load_dir($baseURL.$subdir);
		}
	}
	
	// Get all the file links from the page
	preg_match_all("/(file=[^\"\']+)/", $query, $matches);
	
	foreach ($matches[1] as $file)
	{
		if (!$r_query[$dir."&".$file])
		{
			print "found: ".$dir."&".urldecode($file)."\n";
			$new++;
			$name = explode("=", $file);
			$name = name[1];
			$found[] = array($dir."&".$file, $name);
		}
		else
		{
			$old++;
		}
	}
}

?>