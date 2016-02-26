<?php

// Original code: The Wizard of DATz

$dir="http://www.vectrexmuseum.com/mirror/vgdb/";

print "<pre>check folders:\n\n";
print "load: ".$dir."\n";

$new = 0;
$old = 0;

$query = implode('', file($dir));
$query = explode('<td bgcolor="#DBDBDB" height="19"><font color="#000000" ><b>Code</b></font> </td>', $query);

$query = explode('</center>', $query[1]);
$query = str_replace(
	array("\n", '<span class="small">', '</span>'),
	array(" ", ' (', ')'),
	$query[0]);

$query = explode('<tr>', $query);

foreach ($query as $row)
{
	$row = explode('<td ', $row);
	$title = trim(strip_tags('<td '.$row[2]));
	$title = preg_replace('/\s+/', " ", $title);

	$title = str_replace(
		array("( (", "((", "))"),
		array("(", "(", ")" ),
		$title);

	$year = trim(strip_tags('<td '.$row[3]));
	$manufactor = trim(strip_tags('<td '.$row[4]));

	$url = explode('<a href="', $row[2]);
	$url = explode('"', $url[1]);
	$url = $url[0];

	if ($url)
	{
		print '<span style="display:none" >';
		$dl = implode('', file($dir.$url));
		print '</span>';
		$dl = explode('<a href="../ROMS/', $dl);
		if ($dl[1])
		{
			$dl = explode('"', $dl[1]);
			$dl = $dl[0];

			$ext = explode('.', $dl);
			$ext = $ext[count($ext) - 1];

			$title = $title." (".$year.") (".$manufactor.").".$ext;

			if (!$r_query[$dl])
			{
				$found[] = array($title, $dl);
				$new++;
			}
			else
			{
				$old++;
			}
		}
    }
}

print "new: ".$new.", old: ".$old."\n";
print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"".$dir."ROMS/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td><td><pre>";

foreach ($found as $url)
{
	print $url[1]."\n";
}

print "</td></tr></table>";

?>