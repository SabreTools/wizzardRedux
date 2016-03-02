<?php

// Original code: The Wizard of DATz

print "<pre>";

$new = 0;
$old = 0;

$query = get_data("http://unofficial-cd32-ports.blogspot.co.nz/search?max-results=100");
$query = explode("<h3 class='post-title entry-title' itemprop='name'>\n<a href='", $query);
$query[0] = null;
foreach ($query as $row)
{
	if ($row)
	{
		$url = explode("'", $row);
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
	print "<a href=".$row[0]." target=_blank>".$row[1]."</a>\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=".$row[0]." target=_blank>".$row[0]."</a>\n";
}

print "</td></tr></table>";


?>