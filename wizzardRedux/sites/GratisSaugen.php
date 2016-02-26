<?php

// Original code: The Wizard of DATz

print "<pre>";

$pages = array(
	"http://www.gratissaugen.de/erbsen/c64dash.html",
	"http://www.gratissaugen.de/erbsen/liste.html",
	"http://www.gratissaugen.de/erbsen/plans.html",
	"http://www.gratissaugen.de/erbsen/enc64dash.html",
	"http://www.gratissaugen.de/erbsen/general.html",
);

foreach ($pages as $page)
{
	print "load ".$page."\n";

	$content = implode('', file($page));
	$content = str_replace("\r\n", '', $content);
	$content = explode('<a href="', $content);
	$content[0] = null;

	$new = 0;
	$old = 0;

	$page = explode('/', $page);
	$page[count($page) - 1] = null;
	$page = implode('/', $page);

	foreach ($content as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $url[0];
			$ext = explode('.', $url);
			
			$url = $page.$url;
			
			if (count($ext) > 1)
			{
				$name = explode('</a>', $row);
				$name = strip_tags('<a href="'.$name[0]);

				if (!$r_query[$url])
				{	
					$found[] = array($url, $name.".".$ext[count($ext) - 1]);
					$new++;
					$r_query[$url] = true;
				}
				else 
				{
					$old++;
				}
			}
		}
	}

	print "found new ".$new.", old ".$old."\n";

	print "<table><tr><td><pre>";
	
	foreach($found as $row)
	{
		print $row[0]."\n";
	}
	
	print "</td><td><pre>";
	
	foreach($found as $row)
	{
		print "<a href=\"".$row[0]."\" target=_blank>".$row[1]."</a>\n";
	}
	
	print "</td></tr></table>";
}

?>