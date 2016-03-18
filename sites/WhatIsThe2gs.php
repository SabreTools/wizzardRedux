<?php

// Original code: The Wizard of DATz

print "<pre>";

$found = array();

$dir = "http://whatisthe2gs.apple2.org.za/";
print "load: ".$dir."\n";
$query = get_data($dir);
$query = explode('<ul id="catNav">', $query);
$query = explode('<li><a href="', $query[1]);
array_splice($query, 0, 1);

foreach ($query as $row)
{
	$section = explode('"', $row);
	$section = $section[0];

	$new = 0;
	$old = 0;

	$dir = "http://whatisthe2gs.apple2.org.za/".$section;
	print "load:  ".$dir."\n";
	$queryb = get_data($dir);
	$queryb = explode("<td class='caption'><a href='", $queryb);
	array_splice($queryb, 0, 1);

	foreach ($queryb as $rowb)
	{
		$page = explode("'", $rowb);
		$page = $page[0];

		$dir = "http://whatisthe2gs.apple2.org.za/".$page;
		print "load:   ".$dir."\n";
		$queryc = get_data($dir);

		$title = explode("<h1>", $queryc);
		$title = explode("<", $title[1]);
		$title = trim($title[0]);

		$year = explode(">Year:</span>", $queryc);
		$year = explode("<", $year[1]);
		$year = trim($year[0]);

		$pub = explode(">Publisher:</span>", $queryc);
		$pub = explode("<", $pub[1]);
		$pub = trim($pub[0]);

		$dls = explode('<a href="files/', $queryc);
		array_splice($dls, 0, 1);

		foreach ($dls as $DL)
		{
			$DL = explode('"', $DL);
			$DL = $DL[0];

			$ext = explode('.', $DL);
			$ext = $ext[count($ext) - 1];

			if (!$r_query[$DL])
			{
				$found[] = array($title." (".$pub.") (".$year.").".$ext, $DL);
				$new++;
				$r_query[$DL] = true;
			}
			else
			{
				$old++;
			}
		}
	}

	print "new: ".$new.", old: ".$old."\n";
}

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"http://whatisthe2gs.apple2.org.za/files/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>