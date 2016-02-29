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
	$query = get_data("http://cpccrackers.free.fr/".$dir);
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
		$bquery = get_data("http://cpccrackers.free.fr/".$dir."/index.php?voirdir=".$row);
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

?>