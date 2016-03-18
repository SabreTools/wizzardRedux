<?php

// Original code: The Wizard of DATz

print "<pre>";

$r_query = array_flip($r_query);
$start = $r_query[0];

print "\nSearch for new uploads\n\n";

for ($x = $start; $x >= $start; $x++)
{
	$query = get_data("http://a8.fandal.cz/detail.php?files_id=".$x);

	$gametitle = explode('<div align="center"><br>', $query);
	$gametitle = explode('<br>', $gametitle[1]);
	$gametitle = trim($gametitle[0]);

	if ($gametitle)
	{
		$author = explode('<b>Author:</b>&nbsp;</td>'."\r\n".'                <td width="89%">', $query);
		$author = explode('</td>', $author[1]);
		$author = trim($author[0]);

		$year = explode('<b>Year:</b>', $query);
		$year = explode('</td>', $year[1]);
		$year = trim($year[0]);

		if ($author && $author != '?')
		{
			$gametitle = $gametitle." (".$author.")";
		}
		if ($year && $year != '?')
		{
			$gametitle = $gametitle." (".$year.")";
		}

		print $x."\t<a href=http://a8.fandal.cz/download.php?files_id=".$x." target=_blank>".$gametitle.".zip</a>\n";
		$last = $x;
	}
	else
	{
		print "stop by ".$x.", no data found";
		break;
	}
}

if ($last)
{
	$start = $last + 1;
}

print "\nnext startnr\t<a href=?action=onlinecheck&source=fandal&start=".($start).">".$start."</a>\n\n";

$query = get_data("http://a8.fandal.cz/stuff.php");
$query = explode('<span class="d13">', $query);
$query[0] = null;

foreach ($query as $row)
{
	if ($row)
	{
		$title = explode('</span> (', $row);
		$year = $title[1];
		$title = $title[0];
		$year = explode(')', $year);
		$year = $year[0];

		$title = $title." (Fandal's Stuff) (".$year.")";

		$url = explode('download.php?path=binaries', $row);
		$url = explode('"', $url[1]);
		$url = $url[0];

		print "<a href=http://a8.fandal.cz/download.php?path=binaries".$url." target=_blank>".$title.".zip</a>\n";
	}
}

?>