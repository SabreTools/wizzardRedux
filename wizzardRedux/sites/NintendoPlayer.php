<?php

// Original code: The Wizard of DATz

print "<pre>";

$pages = array(
	"http://www.nintendoplayer.com/prototype/",
	"http://www.nintendoplayer.com/unreleased/",
);

foreach ($pages as $page)
{
	print "load ".$page."\n";

	$content = implode('', file($page));
	$content = explode('<a href="', $content);
	$content[0] = null;

	$new = 0;
	$old = 0;

	foreach ($content as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$text = explode('<', $url[1]);
			$text = explode('>', $text[0]);
			$text = $text[1];
			$url = $url[0];
			
			if ($text == 'Publicly Dumped')
			{
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
	}

	print "found new ".$new.", old ".$old."\n";
}

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print "<a href=\"".$row."\">".$row."</a>\n";
}

print "</td></tr></table>";


?>