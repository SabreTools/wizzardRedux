<?php

// Original code: The Wizard of DATz
 
$dirs = array(
	'http://ann.hollowdreams.com/anndisks.html',
);

echo "<table>\n";
foreach ($dirs as $dir)
{
	echo "<tr><td>".$dir."</td>";
	$query = get_data($dir);
	preg_match_all("/ href=\"(.*?)\"/", $query, $query);
	$query = $query[1];

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		$ext = explode('.', $row);
	
		if (!$r_query[$row])
		{
			$found[] = array($row, $row);
			$new++;
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
	echo "<a href='".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>