<?php

// Original code: The Wizard of DATz

$dirs = array(
 	'Bonux/',
	'Downloads/',
);

print "<pre>check folders:\n\n";

foreach ($dirs as $dir)
{
	print "load: ".$dir."\n";
	$query = implode('', file("http://cpccrackers.free.fr/".$dir));
	$query = explode('</h1>', $query);
	$query = explode('href="index.php?voirdir=', $query[1]);
	array_splice($query, 0, 1);

	foreach ($query as $row)
	{
		$row = explode('"', $row);
		$row = $row[0];
		
		$new = 0;
		$old = 0;

		print "load: ".$row."\n";
		$bquery = implode('', file("http://cpccrackers.free.fr/".$dir."/index.php?voirdir=".$row));
		$bquery = explode('<td class="g2c" width="120px">', $bquery);
		$bquery = explode('href="', $bquery[1]);
		array_splice($bquery, 0, 1);

		foreach ($bquery as $brow)
		{
			$brow = explode('"', $brow);
			$url = $brow[0];
			
			if (!$r_query[urldecode($dir.$url)])
			{
				$found[] = $dir.$url;
				$new++;
			}
			else
			{
				$old++;
			}
		}

		print "close: ".$row."\n";
		print "new: ".$new.", old: ".$old."\n";
	}
}

print "\nnew urls:\n\n<table><tr><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"http://cpccrackers.free.fr/".$url."\">".urldecode($url)."</a>\n";
}

print "</td></tr></table>";

/*function listDir($dir)
{
	GLOBAL $found, $r_query;

	print "load: ".$dir."\n";
	$query = str_replace('&amp;', '&', implode('', file("http://cpccrackers.free.fr/".$dir)));
	$query = explode('>Parent Directory<', $query);
	$query = explode('<A HREF="', $query[1]);
	$query[0] = null;

	$new = 0;
	$old=0;
	$folder = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $url[0];

			if (substr($url, -1) == '/')
			{
				listDir($dir.$url);
				$folder++;
			}
			else
			{
				if (!$r_query[urldecode($dir.$url)])
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
	}

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old.", folder:".$folder."\n";
}
*/

?>