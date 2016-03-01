<?php

// Original code: The Wizard of DATz

print "<pre>";

$page = "http://www.manosoft.it/?page_id=1050";

print "load ".$page."\n";

$old = 0;
$new = 0;

$content = get_data($page);
$content = explode('<a href="', $content);
array_splice($content, 0, 1);

foreach ($content as $row)
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

print "new ".$new.", old ".$old."\n";

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print "<a href=\"".$row."\" target=_blank>".$row."</a>\n";
}

print "</td></tr></table>";
?>