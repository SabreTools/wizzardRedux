<?php

// Original code: The Wizard of DATz

$newfiles = array(
	'https://alexvampire.wordpress.com/feed/',
);

echo "<table>\n";
foreach ($newfiles as $newfile)
{
	echo "<tr><td>".$newfile."</td>";
	$query = get_data($newfile);
	
	preg_match_all("/<link>(.*?)<\/link>/", $query, $query);
	$query = $query[1];

	$old = 0;
	$new = 0;

	foreach ($query as $row)
	{
    	if (isset($r_query[$row]) && $r_query[$row] !== NULL)
		{
			$old++;
		}
		else
		{
			$found[] = array($row, $row);
			$new++;
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