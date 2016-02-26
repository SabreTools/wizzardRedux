<?php

// Original code: The Wizard of DATz

$dirs = array(
	'http://www.pokemon-mini.net/downloads/',
);

print "<pre>check folders:\n\n";

foreach ($dirs as $dir)
{
	listDir($dir);
}

print "\nnew urls:\n\n";

print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"".$url[1]."\">".$url[0]."</a>\n";
}

print "</td><td><pre>";

foreach ($found as $url)
{
	print $url[1]."\n";
}

print "</td></tr></table>";

function listDir($dir)
{
	GLOBAL $found, $r_query;

	print "load: ".$dir."\n";

	$query = implode('', file($dir));
	$query = explode('<div class="wpfilebase-fileicon"><a href="', $query);
	$query[0] = null;

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$title = $url[2];
			$url = $url[0];

			$ext = explode('.', $url);
			$ext = $ext[count($ext) - 1];

			if (!$r_query[$url])
			{
				$found[] = array($title.'.'.$ext, $url);
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