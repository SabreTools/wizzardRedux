<?php

// Original code: The Wizard of DATz

print "<pre>";

$new = 0;
$old = 0;

$url= "http://eludevisibility.org/archive/";

print "load: ".$url."\n";

$query = get_data($url);
$query = explode(": <a href=\"", $query);
$query[0] = null;

foreach ($query as $row)
{
	if ($row)
	{
		$url = explode('"', $row);
		$url = $url[0];
		$title = explode("</a>", $row);
		$title = explode(">", $title[0]);
		$title = $title[1];
		if (!$r_query[$url])
		{
			$found[] = array($url, $title);
			$new++;
		}
		else
		{
			$old++;
		}
	}
}

print "found new ".$new.", old ".$old."\n";
print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print $row[0]."\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=\"".$row[0]."\" target=_blank>".$row[1]."</a>\n";
}

print "</td></tr></table>";
?>