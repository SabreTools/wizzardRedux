<?php

// Original code: The Wizard of DATz

print "<pre>";

$page = "http://msxcas2rom.zxq.net/";

print "load ".$page."\n";

$old = 0;
$new = 0;

$content = implode('', file($page));
$content = explode('<a href="', $content);
$content[0] = null;

foreach ($content as $row)
{
	if ($row)
	{
		$url = explode('"', $row);
		$url = $url[0];
		$ext = explode('.', $url);

		$title = explode('</a>', $row);
		$title = trim(strip_tags('<a href="'.$title[0].'</a>'));

		if (!$r_query[$url])
		{
			$found[] = array($url, $title.".".$ext[count($ext) - 1]);
			$new++;
		}
		else
		{
			$old++;
		}
	}
}

print "new ".$new.", old ".$old."\n";

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print $row[0]."\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=\"".$page.$row[0]."\" target=_blank>".$row[1]."</a>\n";
}

print "</td></tr></table>";
?>