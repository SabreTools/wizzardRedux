<?php

// Original code: The Wizard of DATz

print "<pre>";

$dir = "http://apple2online.com/";
print "load: ".$dir."\n";
$query = get_data($dir);
$query = explode("index.php?p=", $query);
array_splice($query, 0, 1);

foreach ($query as $row)
{
	$row = explode('"', $row);
	$new = 0;
	$old = 0;

	$dir = "http://apple2online.com/index.php?p=".$row[0];
	print "load: ".$dir."\n";
	$queryb = get_data($dir);
	$queryb = explode('ddlevelsmenu', str_replace('&amp;', '&', $queryb));
	$queryb = explode('web_documents/', $queryb[1]);
	array_splice($queryb, 0, 1);

	foreach ($queryb as $row)
	{
		$DL = explode('"', $row);
		$DL = $DL[0];

		$ext = explode('.', $DL);
		$ext = $ext[count($ext) - 1];

		$alt = explode('/', $DL);
		$alt = $alt[count($alt) - 1];

		$title = explode('</a>', $row);
		$title = trim(strip_tags('<a href="'.$title[0]));
		if (!$title)
		{
			$title = $alt;
		}
		else
		{
			$title = $title.".".$ext;
        }

		if (!$r_query[$DL])
		{
			$found[] = array($title, $DL);
			$new++;
			$r_query[$DL] = true;
		}
		else
		{
			$old++;
		}
    }

	print "new: ".$new.", old: ".$old."\n";
}


print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"http://apple2online.com/web_documents/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>