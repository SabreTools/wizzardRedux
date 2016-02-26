<?php

// Original code: The Wizard of DATz

$dirs = array(
	'http://www.sacnews.net/adamcomputer/downloads/',
);

print "<pre>check folders:\n\n";

foreach($dirs as $dir)
{
	if ($dir)
	{
		listDir($dir);
	}
}

print "\nnew urls:\n\n";

foreach ($found as $url)
{
	print "<a href=\"".$url."\">".$url."</a>\n";
}

function listDir($dir)
{
	GLOBAL $found, $r_query;

	print "load: ".$dir."\n";
	$query = implode('', file($dir));
	$query = explode(' href="', $query);
	$query[0] = null;

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $url[0];

			$ext = explode('.', $url);

			if (!$r_query[$dir.$url])
			{
				$found[] = $dir.$url;
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old."\n";
}

?>