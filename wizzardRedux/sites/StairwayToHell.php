<?php

// Original code: The Wizard of DATz

print "<pre>";

$pages = array(
	array("http://www.stairwaytohell.com/roms/homepage.html","http://www.stairwaytohell.com/roms/"),
	array("http://www.stairwaytohell.com/atom/wouterras/","http://www.stairwaytohell.com/atom/wouterras/"),
	array("http://www.stairwaytohell.com/bbc/sthcollection.html","http://www.stairwaytohell.com/bbc/"),
	array("http://www.stairwaytohell.com/bbc/archive/tapeimages/reclist.php?sort=dir&filter=.zip",
		"http://www.stairwaytohell.com/bbc/archive/tapeimages/",true),
	array("http://www.stairwaytohell.com/bbc/archive/diskimages/reclist.php?sort=dir&filter=.zip",
		"http://www.stairwaytohell.com/bbc/archive/diskimages/",true),
	array("http://www.stairwaytohell.com/bbc/other/educational/reclist.php?sort=name&filter=.zip",
		"http://www.stairwaytohell.com/bbc/other/educational/",true),
	array("http://www.stairwaytohell.com/electron/uefarchive/reclist.php?sort=dir&filter=.zip",
		"http://www.stairwaytohell.com/electron/uefarchive/",true),
	array("http://www.stairwaytohell.com/electron/t2p3/homepage.html",
		"http://www.stairwaytohell.com/electron/t2p3/"),
	array("http://www.stairwaytohell.com/essentials/homepage.html",
		"http://www.stairwaytohell.com/essentials/"),
	array("http://www.stairwaytohell.com/electron/dfs/homepage.html",
		"http://www.stairwaytohell.com/electron/dfs/"),
	array("http://www.stairwaytohell.com/electron/adfs/homepage.html",
		"http://www.stairwaytohell.com/electron/adfs/"),
);

foreach ($pages as $page)
{
	print "load ".$page[0]."\n";

	$content = get_data($page[0]);
	$content = explode('<A HREF="', preg_replace('/\s+/', ' ', str_replace(
						array("href=", "\n", "</a>", "<a "),
						array('HREF=', '', '</A>', '<A '), $content)));
	$content[0] = null;

	$found = array();

	$new = 0;
	$old = 0;
	
	foreach ($content as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$suburl = explode('/', $url[0]);
			$ext = explode('.', $url[0]);
			
			if (count($ext) > 1)
			{
				$url = $page[1].$url[0];
	
				$name = explode('</A>', $row);
				$name = strip_tags($name[0]);
				$name = explode('>', $name);
				$name = trim($name[1]);
	
				$name = str_replace(".".$ext[count($ext) - 1], '', $name);
	
				if ($page[2])
				{
					$name = $name." (".$suburl[count($suburl) - 2].")";
				}
	
				if (!$r_query[$url])
				{
					$found[] = array($url, $name.".".$ext[count($ext) - 1]);
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
}

?>