<?php

// Original code: The Wizard of DATz

print "<pre>";

$mainPages=Array(
	"http://www.tzxvault.org/index.htm",
	"http://www.tzxvault.org/Amstrad/index.htm",
	"http://www.tzxvault.org/C16/index.htm",
	"http://www.tzxvault.org/C64/index.htm",
	"http://www.tzxvault.org/Vic20/index.htm",
);

$pages = array();

foreach ($mainPages as $page)
{
	print "load ".$page."\n";
	$content = implode('', file($page));
	$content = explode('href="', $content);
	array_splice($content, 0, 1);

	foreach ($content as $row)
	{
		$row = explode('"', $row);
		$row = $row[0];
		print "found ".$row."\n";
		$pages[$row] = $row;
	}
}

foreach ($pages as $page)
{
	print "load ".$page."\n";

	$dirs = array();

	$new = 0;
	$old = 0;

	$content = implode('', file($page));
	$content = str_replace('&nbsp;', ' ', $content);

	$content2 = explode('<font color="#FF0000">', $content);
	$content2[0] = null;

	$content = explode('<a href="http://www.tzxvault.org/', $content);
	$content[0] = null;

	foreach ($content as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $url[0];
			$ext = explode('.', $url);
			
			if (count($ext) > 1)
			{
				$name = explode('</a>', $row);
				$name = strip_tags($name[0]);
				$name = explode('>', $name);
				$name = trim($name[1]);

				$addinf = explode('<td>', $row);
				$publ = explode('</td>', $addinf[1]);
				$publ = trim(strip_tags($publ[0]));
				if ($publ)
				{
					$name = $name." (".$publ.")";
				}
				$year = explode('</td>', $addinf[2]);
				$year = trim(strip_tags($year[0]));
				if ($year)
				{
					$name = $name." (".$year.")";
				}

				$url_split = explode('/', $url);

				if ($name)
				{
					$name = $name.".".$ext[count($ext) - 1];
				}
				else
				{
					$name = $url_split[count($url_split) - 1];
				}

				$url_split[count($url_split) - 1] = null;
				$url_split = implode('/', $url_split);
				
				$dirs[$url_split] = $url_split;

				if (!$r_query[$url])
				{
					$found[] = array($url, $name);
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

	foreach ($content2 as $row)
	{
		if ($row)
		{
			$name = explode('</font>', $row);
			$name = $name[0];
			$file = str_replace(' ', '', $name).".zip";

			$addinf = explode('<td>', $row);
			$publ = explode('</td>', $addinf[1]);
			$publ = trim(strip_tags($publ[0]));
			if ($publ)
			{
				$name = $name." (".$publ.")";
			}
			$year = explode('</td>', $addinf[2]);
			$year = trim(strip_tags($year[0]));
			if ($year)
			{
				$name = $name." (".$year.")";
			}

			foreach($dirs as $dir)
			{
				$url = $dir.$file;
				if (!$r_query[$url])
				{
					$found[] = array($url, $name.".zip");
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
}
sort($found);

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print $row[0]."\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=\"http://www.tzxvault.org/".$row[0]."\" target=_blank>".$row[1]."</a>\n";
}

print "</td></tr></table>";
?>