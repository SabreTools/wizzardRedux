<?php

// Original code: The Wizard of DATz

print "<pre>";

$new = 0;
$old = 0;

$query= get_data("https://sites.google.com/site/msxbasicgames/");
$query = explode('<td class="td-file">', str_replace('&amp;', '&', utf8_decode($query)));
array_splice($query, 0, 1);
foreach ($query as $row)
{
	$url = explode('<a href="/site/msxbasicgames/archivos/', $row);
	$url = explode('"', $url[1]);
	$url = $url[0];

	$ext = explode('<br />', $row);
	$ext = explode('.',$ext[0]);
	$file = $ext[count($ext) - 2];
	$ext = $ext[count($ext) - 1];

	$title = explode('<td class="td-desc filecabinet-desc" dir="ltr">', $row);
	$title = explode('<', $title[1]);
	if ($title[0])
	{
		$title = explode(' / ', $title[0]);
		$title = $title[0]." (".$title[1].")";
	}
	else
	{
		$title = $file;
    }

	if (!$r_query[$url])
	{
		$found[] = array($title.".".$ext, $url);
		$new++;
	}
	else
	{
		$old++;
	}
}
	
print "new: ".$new.", old: ".$old."\n";

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"https://sites.google.com/site/msxbasicgames/archivos/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>