<?php

// Original code: The Wizard of DATz

print "<pre>";

for ($x = 1; $x < 100; $x++)
{
	$new = 0;
	$old = 0;

	$page = "http://smartlip.com/symbian/flist.php?cat=roms/&sort=date&red=&b=".$x;
	print "load ".$page."\n";
	$query = get_data($page);
 	$query = explode('download.php?file=', str_replace('&amp;', '&', $query));
	array_splice($query, 0, 1);

	foreach ($query as $row)
	{
		$DL = explode("'", $row);
		$DL = $DL[0];

		if (!$r_query[$DL])
		{
			$found[] = $DL;
			$new++;
			$r_query[$DL] = true;
		}
		else
		{
			$old++;
			$x = 100;
		}
	}

	print "new: ".$new.", old: ".$old."\n";
}


print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"http://smartlip.com/symbian/download.php?file=".$url."\">".$url."</a>\n";
}

print "</td></tr></table>";

?>