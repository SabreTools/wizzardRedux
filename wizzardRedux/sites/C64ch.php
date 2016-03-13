<?php

// Original code: The Wizard of DATz

// TODO: Fix naming issue (comes up with "Array" for one part)
// TODO: Find end of list without having to add start

$x = implode('', array_flip($r_query));

echo "<table>\n";
while (true)
{
	echo "<tr><td>http://c64.ch/demos/realdetail.php?id=".$x."</td>";
	
	$query = trim(get_data("http://c64.ch/demos/realdetail.php?id=".$x));
	
	// If the page redirects to the main page, the game with that ID doesn't exist
	if (strpos($query, "C64.CH - The C64 Demo Portal - News") !== FALSE)
	{
		break;
	}

	preg_match("/<td style=\"background:url\(\/img\/m\/t2b\.gif\);\" colspan=\"3\" width=\"432\"><span class=\"mt\">(.*?) by <a.*?>(.*?)<\/a>/", $query, $gametitle);
	$gameauthor = trim($gametitle[2]);
	$gametitle = trim(str_replace(array(' (', ')'), array(', ', ''), $gametitle[1]));

	$info = array();
	$info[] = $gametitle;
	
	preg_match("/\/demos\/list\.php\?year=(.*?)&amp;source=year/", $query, $year);
	$year = $year[1];
	
	if ($year !== NULL)
	{
		$info[] = $year;
	}

	preg_match("/\/demos\/list\.php\?source=party&partyid=.*?\">(.*?)<\/a>/", $query, $party);
	$party = trim(str_replace(array(' (', ')'), array(', ', ''), $party[1]));

	if ($party !== NULL)
	{
		$info[] = $party;
	}

	$gametitle = $gameauthor." (".implode(") (", $info).").zip";
	
	$found[] = array($gametitle, "http://c64.ch/demos/download.php?id=".$x);
	echo "<td>Found new: 1, old: 0</tr>\n";
	
	$x++;
}
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>