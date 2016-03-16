<?php

// Original code: The Wizard of DATz

$dir = "http://apple2online.com/";
$query = get_data($dir);

preg_match_all("/index\.php\?p=(.*?)\"/", $query, $query);
$query = $query[1];

echo "<table>\n";
foreach ($query as $row)
{
	$new = 0;
	$old = 0;

	$dir = "http://apple2online.com/index.php?p=".$row;
	echo "<tr><td>".$dir."</td>";
	$queryb = get_data($dir);
	
	preg_match_all("/title=\"(.*?)\" href=\"web_documents\/(.*?)\"/", $queryb, $queryb);
	
	$newrows = array();
	for ($index = 1; $index < sizeof($queryb[0]); $index++)
	{
		$newrows[] = array($queryb[1][$index], $queryb[2][$index]);
	}

	foreach ($newrows as $row)
	{
		$ext = pathinfo($row[1], PATHINFO_EXTENSION);

		$alt = explode('/', $row[1]);
		$alt = $alt[count($alt) - 1];

		$title = $row[0];
		
		// In the conditions where the page doesn't have a download
		if ($ext == "" && $alt == "" && $title == NULL)
		{
			continue;
		}
		
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
			$found[] = array($title, "http://apple2online.com/web_documents/".$DL);
			$new++;
			$r_query[$DL] = true;
		}
		else
		{
			$old++;
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
	echo "<a href='http://www.apple-iigs.info/".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>