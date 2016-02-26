<?php

// Original code: The Wizard of DATz

print "<pre>";
	
$query = implode('', file("http://mapy.atari8.info/"));
$query = explode('<td height="216" background="gfx/menumenu.gif" valign="top">', $query);
$query = explode("</td>", $query[1]);
$query = explode("href=\"", $query[0]);
$query[0] = null;

$total = 0;
$new = 0;

foreach ($query as $row)
{
	if ($row)
	{
		$url = explode("\"", $row);
		$url = $url[0];
		
		if (!$r_query[$url])
		{
			$queryb = implode('', file("http://mapy.atari8.info/".$url));

			$name = explode("<strong>:: ", $queryb);
			$name = explode("</strong>", $name[1]);
			$name = $name[0];
			
			$author = explode('<a class="autor"', $queryb);
			$author = explode(">", $author[1]);
			$author = explode("<", $author[1]);
			$author = trim($author[0]);

			$dl = explode('href="stuff/', $queryb);
			$dl = explode("\"", $dl[1]);
			$dl = $dl[0];

			$found[] = array($url, strtr($name." (".$author.")", $GLOBALS['normalize_chars']),$dl);
			$new++;
		}

		$total++;
	}
}

print "\nfound ".$total.", new ".$new."\n";

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print "<a href=\"http://mapy.atari8.info/".$row[0]."\" target=_blank>".$row[0]."</a>\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=\"http://mapy.atari8.info/stuff/".$row[2]."\"  target=_blank>".$row[1].".zip</a>\n";
}

print "</td></tr></table>";


?>