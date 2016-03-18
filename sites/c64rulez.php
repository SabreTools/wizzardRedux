<?php

// Original code: The Wizard of DATz

$newfiles = array (
	'http://c64.rulez.org/pub/c64.hu/NEWFILES.txt',
	'http://c64.rulez.org/pub/c64/NEWFILES.txt',
	'http://c64.rulez.org/pub/c64/Hall_of_Fame/NEWFILES.txt',
	'http://c64.rulez.org/pub/plus4/NEWFILES.txt',
	'http://c64.rulez.org/pub/c128/NEWFILES.txt',
);

echo "<table>\n";
foreach ($newfiles as $newfile)
{
	echo "<tr><td>".$newfile."</td>";
	$query = get_data($newfile);
	
	preg_match_all("/^(\S.*\/.*\.\S+)$/m", $query, $query);
	$query = $query[1];

	$old = 0;
	$new = 0;

	foreach ($query as $row)
	{
    	if ($r_query[$row])
		{
			$old++;
		}
		else
		{
			$found[] = array($row, $dir.$row);
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