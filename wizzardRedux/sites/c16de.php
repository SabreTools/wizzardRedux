<?php

// Original code: The Wizard of DATz

$dirs = array(
	'http://c16.c64games.de/c16/tapes/',
	'http://c16.c64games.de/c16/demos/',
	'http://c16.c64games.de/c16/tools/',
	'http://c16.c64games.de/c16/spiele/',
	'http://c16.c64games.de/c16/basic/',
);

echo "<table>\n";
foreach ($dirs as $dir)
{
	listDir($dir);
}
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='".$row."'>".$row."</a><br/>\n";
}

echo "<br/>\n";

function listDir ($dir)
{
	GLOBAL $found, $r_query;
	
	$query = get_data($dir);
	
	preg_match_all("/<a href=\"(.*?)\">/i", $query, $query);
	$query = $query[1];
	for ($index = 0; $index < 5; $index++)
	{
		unset($query[$index]);
	}

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		$url = $dir.$row;

		if (substr($url, -1) == '/')
		{
			listDir($url);
		}
		else
		{
			if(!$r_query[$url])
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

	echo "<tr><td>".$dir."</td><td>Found new: ".$new.", old: ".$old."</tr>\n";
}

?>