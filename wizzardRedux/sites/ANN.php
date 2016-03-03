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
	$query = explode(' href="', $query);
	$query[0] = null;

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $url[0];

			$ext = explode('.', $url);


			if (!$r_query[$url])
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