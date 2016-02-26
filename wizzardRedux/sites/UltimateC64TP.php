<?php

// Original code: The Wizard of DATz


print "<pre>";

$page="http://tapes.c64.no/main_tapelist.php?group=all&orderby=title";

print "load ".$page."\n";

$content=implode ('', file ($page));
$content=explode ('<table border=0 cellpadding=0 cellspacing=0 bgcolor="#000000" width="100%">',$content);
$content=explode ('<tr ',$content[1]);
$content[0]=null;

$new = 0;
$old = 0;

foreach ($content as $row)
{
	if ($row)
	{
		$row = explode('<td>', $row);

		$id = explode("<a href='", $row[1]);
		$id = explode("'", $id[1]);
		$id = $id[0];

		$info = array();
		$temp = trim(strip_tags($row[3]));
		if ($temp)
		{
			$info[] = $temp;
		}
		$temp = trim(strip_tags($row[2]));
		if ($temp)
		{
			$info[] = $temp;
		}
		$temp = trim(strip_tags($row[5]));
		if ($temp)
		{
			$info[] = $temp;
		}

		$name = trim(strip_tags($row[1]))." (".implode(") (", $info).").zip";

		if (!$r_query[$id])
		{
			$found[] = array($id, $name);
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
	print "<a href=\"http://tapes.c64.no/".$row[0]."\" target=_blank>".$row[1]."</a>\n";
}

print "</td></tr></table>";


?>