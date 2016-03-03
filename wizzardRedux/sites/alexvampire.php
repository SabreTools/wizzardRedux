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
 	$query = explode('<link>', $query);
	$query[0] = null;

	$old = 0;
	$new = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$dl = explode('</link>', $row);
			$dl = $dl[0];

	    	if ($r_query[$dl])
			{
				$old++;
			}
			else
			{
				$found[] = $dl;
				$new++;
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
	echo "<a href='".$row."'>".$row."</a><br/>\n";
}

echo "<br/>\n";

?>