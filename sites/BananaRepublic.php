<?php

// Original code: The Wizard of DATz

$newfiles = array(
	'http://www.elysium.filety.pl/All_files.txt',
	'http://www.elysium.filety.pl/GamesArchive.txt',
);

echo "<table>\n";
foreach ($newfiles as $newfile)
{
	$new = 0;
	$old = 0;	

	echo "<tr><td>".$newfile."</td>";
	$query = get_data($newfile);
 	$query = explode("\r\n", str_replace('-->', '   ', $query));

	foreach ($query as $row)
	{
		$row = explode("\t: ", $row);
		$row = $row[1];
		if ($row)
		{
	    	if ($r_query[$row])
	    	{
				$old++;
			}
			else
			{
				$found[] = $row;
				$new++;
			}
		}
	}
	echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";
}
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>\n";
}

foreach ($found as $row)
{
	preg_match("/\.\/(.*\/(.*?)\/(.*?)\.(.*))/", $row, $name);
	print "<a href=\"http://www.elysium.filety.pl/".$name[1]."\">".$name[2]." (".$name[3].").".$name[4]."</a><br/>\n";
}

echo "<br/>\n";

?>