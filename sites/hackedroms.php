<?php

// Original code: The Wizard of DATz

$dirs = array(
	'http://my.hacked.roms.free.fr/',
);

print "<pre>check folders:\n\n";

foreach ($dirs as $dir)
{
	print "load: ".$dir."\n";

	$query = get_data($dir);
	$query = explode('<a class="rom_lien" href="', $query);
	$query[0] = null;

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $url[0];


			if (!$r_query[$url])
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

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old."\n";
}

print "\nnew urls:\n\n";

print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"".$url."\">".$url."</a>\n";
}

print "</td></tr></table>";


?>