<?php

// Original code: The Wizard of DATz

$dirs = array(
	'http://www.bjars.com/',
	'http://www.bjars.com/tools.html',
	'http://www.bjars.com/resources.html',
	'http://www.bjars.com/disassemblies.html',
	'http://www.bjars.com/sourcecode.html',
	'http://www.bjars.com/mygames.html',
	'http://www.bjars.com/hacks.html',
	'http://www.bjars.com/mygames.html',
	'http://www.bjars.com/7800.html',
	'http://www.bjars.com/original/CaveIn/cavein.htm',
);

$bad_ext = Array(
	'asm',
	'bas',
	'bmp',
	'doc',
	'gif',
	'jpg',
	'pdf',
	'png',
	'html',
	'txt',
	'com',
);

echo "<table>\n";
foreach ($dirs as $dir)
{
	echo "<tr><td>".$dir."</td>";

	$query = get_data($dir);
	
	preg_match_all("/<a href=\"http:\/\/www\.bjars\.com\/(.*?)\".*?>(.*?)<\/a>/is", $query, $links);
	
	$newrows = array();
	for ($index = 1; $index < sizeof($links[0]); $index++)
	{
		$newrows[] = array($links[1][$index], preg_replace("/\s+/", " ", trim(strip_tags($links[2][$index]))));
	}
	
	$new = 0;
	$old = 0;

	foreach ($newrows as $row)
	{
		$url = $row[0];
		$title = $row[1];
		
		$ext = explode('.', $url);
		$ext = $ext[count($ext) - 1];

		if (!in_array($ext, $bad_ext))
		{
			if (!$r_query[$url])
			{
				$found[] = array($title.'.'.$ext, $url);
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}

	echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";
}
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='http://www.bjars.com/".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>