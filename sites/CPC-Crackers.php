<?php

// Original code: The Wizard of DATz

$dirs = array(
 	'Bonux/',
	'Downloads/',
);

echo "<table>\n";
foreach ($dirs as $dir)
{
	echo "<tr><td colspan=2><b>".$dir."</b></td></tr>";
	$query = get_data("http://cpccrackers.free.fr/".$dir);
	
	preg_match_all("/<a rel=\"nofollow\" href=\"index\.php\?voirdir=(.*?)\" class=\".*?\">.*?<\/a>/", $query, $pages);
	$pages = $pages[1];

	foreach ($pages as $row)
	{
		$new = 0;
		$old = 0;

		echo "<tr><td>".$row."</td>";
		$bquery = get_data("http://cpccrackers.free.fr/".$dir."/index.php?voirdir=".$row);
		
		preg_match_all("/<tr class=\"pair\"><td><a rel=\"nofollow\" href=\"(.*?)\" title=\".*?\">(?:<img.*?>)?(.*?)<\/a><\/td><td align=\"right\" class=\"ctp\"/", $bquery, $urls);
		
		$newurls = array();
		for ($index = 0; $index < sizeof($urls[0]); $index++)
		{
			$newurls[] = array($urls[1][$index], $urls[2][$index]);
		}

		foreach ($newurls as $url)
		{
			if (!$r_query[urldecode($dir.$url[0])])
			{
				$found[] = array($url[1], "http://cpccrackers.free.fr/".$dir.$url[0]);
				$new++;
			}
			else
			{
				$old++;
			}
		}
		
		echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";
	}
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