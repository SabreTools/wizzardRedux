<?php

// Original code: The Wizard of DATz

$dirs = array(
	'http://c16.c64games.de/c16/tapes/',
	'http://c16.c64games.de/c16/demos/',
	'http://c16.c64games.de/c16/tools/',
	'http://c16.c64games.de/c16/spiele/',
	'http://c16.c64games.de/c16/basic/',
);

print "<pre>check folders:\n\n";

foreach ($dirs as $dir)
{
	listDir($dir);
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
	$query = explode('>Parent Directory<', $query);
	if ($query[1])
	{
		$query = $query[1];
	}
	else
	{
		$query = $query[0];
	}
	$query = str_replace(' HREF="', ' href="', $query);
	$query = explode(' href="', $query);
	$query[0] = null;

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $dir.$url[0];

			if (substr($url, -1) == '/')
			{
				listDir($url);
			}
			else
			{
				if(!$r_query[$url])
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
	print "new: ".$new.", old: ".$old."\n";
}

?>