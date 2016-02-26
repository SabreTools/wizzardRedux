<?php

// Original code: The Wizard of DATz

$dirs = array(
	'Disks/',
	'Roms/',
	'Tapes/',
);

print "<pre>check folders:\n\n";

foreach ($dirs as $dir)
{
	if ($dir)
	{
		listDir($dir);
	}
}

print "\nnew urls:\n\n<table><tr><td><pre>";

foreach($found as $url)
{
	print "<a href=http://archive.worldofdragon.org/archive/index.php?dir=".$url.">".urldecode ($url)."</a>\n";
}

print "</td></tr></table>";

function listDir($dir)
{
	GLOBAL $found, $r_query;

	print "load: ".$dir."\n";
	$query = implode('', file("http://archive.worldofdragon.org/archive/index.php?dir=".$dir));
	$query = explode('<table class="autoindex_table">',$query);
	$query = explode('<a class="autoindex_a" href="/archive/index.php?dir=', $query[1]);
	$query[0] = null;
	$query[1] = null;

	$new = 0;
	$old = 0;
	$folder = 0;

	foreach($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $url[0];

			if (substr($url, -1) == '/')
			{
				listDir($url);
				$folder++;
			}
			else
			{
				if (!$r_query[urldecode (str_replace('&amp;', '&', $url))])
				{
					$found[] = $url;
					$new++;
				}
				else
				{
					$old++;
				}
			}
		}
	}

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old.", folder:".$folder."\n";
}
?>