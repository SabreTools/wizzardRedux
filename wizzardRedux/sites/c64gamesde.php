<?php

// Original code: The Wizard of DATz

$ids = array();

/*
$translator = array(
	1 => 'c',
	2 => 't',
	3 => 'd',
	4 => 'o',
);
*/

$translator2 = array(
	'c' => 'Cartridge',
	't' => 'Tape',
	'd' => 'Disk',
	'o' => 'Original',
);

echo "<table>\n";
for ($x = 1; $x <= 4; $x++)
{
	$dir = "http://c64games.de/phpseiten/spiele.php?aufruf=true&medium=".$x;
	
	$query = get_data($dir);
	
	preg_match_all("/spieledetail\.php\?filnummer=(.*?)\"/", $query, $ids);
	$ids = $ids[1];
	echo "<tr><td>".$dir."</td><td>Found new: ".count($ids).", old: ???</tr>\n";
}

foreach($ids as $id)
{
	$dir = "http://c64games.de/phpseiten/spieledetail.php?filnummer=".$id;

	$old = 0;
	$new = 0;

	$query = get_data($dir);
	
	preg_match("/<span style=\"color:red;font:Bold 14px Arial, Helvetica;\"><i>(.*?)<\/i><\/span>/", $query, $title);
	$title = trim($title[1]);
	
	preg_match("/Jahr:<\/td><td><span style=\"color:white;font:Bold 12px Arial, Helvetica;\">(.*?)<br><\/td>/", $query, $year);
	$year = trim($year[1]);
	
	preg_match("/Eingespielt am:<\/td><td><span style=\"color:white;font:Bold 12px Arial, Helvetica;\">(.*?)<\/td>/", $query, $uploaded);
	$uploaded = date('Ymd', strtotime(trim($uploaded[1])));
	
	preg_match("/Hersteller:<\/td><td><span style=\"color:white;font:Bold 12px Arial, Helvetica;\">(.*?)<\/td>/", $query, $manufacturer);
	$manufacturer = $manufacturer[1];
	
	preg_match_all("/\.\.\/hugo\.php\?art=(.*?)\.(.*?)\"/", $query, $query);
	$newrows = array();
	for ($index = 1; $index < sizeof($query[0]); $index++)
	{
		$newrows[] = array($query[1][$index], $query[2][$index]);
	}

	foreach ($newrows as $row)
	{
    	if ($r_query[$uploaded.'#'.$id])
    	{
			$old++;
		}
		else
		{
			$found[] = array("{".$uploaded.'#'.$id."}".$title." (".$manufacturer.") (".$year.") (".$translator2[$row[0][0]].").".$row[1], $row[0].".".$row[1]);
			$new++;
		}
	}

	echo "<tr><td>".$dir."</td><td>Found new: ".$new.", old: ".$old."</tr>\n";
}
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='http://c64games.de/hugo.php?art=".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>