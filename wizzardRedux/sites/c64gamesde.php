<?php

// Original code: The Wizard of DATz

print "<pre>";

$files = array();

$translator = array(
	1 => 'c',
	2 => 't',
	3 => 'd',
	4 => 'o',
);

$translator2 = array(
	'c' => 'Cartridge',
	't' => 'Tape',
	'd' => 'Disk',
	'o' => 'Original',
);

for ($x = 1; $x <= 4; $x++)
{
	$dir = "http://c64games.de/phpseiten/spiele.php?aufruf=true&medium=".$x;

	$old = 0;
	$new = 0;

	$query = get_data($dir);
	$query = explode('spieledetail.php?filnummer=', $query);
	$query[0] = null;

	foreach ($query as $row)
	{
		if ($row)
		{
			$row = explode('"', $row);
			$row = $row[0];

			$found[$row] = $row;
			$new++;
		}
	}

	print "load: ".$dir."\t";
	print "found: ".$new."\n";
}

foreach($found as $id)
{
	$dir = "http://c64games.de/phpseiten/spieledetail.php?filnummer=".$id;

	$old = 0;
	$new = 0;

	$query = implode('', file($dir));
	$query = explode('../hugo.php?art=', $query);

	$titel = explode('<span style="color:red;font:Bold 14px Arial, Helvetica;"><i> ', $query[0]);
	$titel = explode(' </i></span>', $titel[1]);
	$titel = $titel[0];

	$year = explode('Jahr:</td><td><span style="color:white;font:Bold 12px Arial, Helvetica;">', $query[0]);
	$year = explode('<', $year[1]);
	$year = $year[0];

	$Eingespielt = explode('Eingespielt am:</td><td><span style="color:white;font:Bold 12px Arial, Helvetica;"> ', $query[0]);
	$Eingespielt = explode('<', $Eingespielt[1]);
	$Eingespielt = date('Ymd', strtotime($Eingespielt[0]));

	$manufactor = explode('Hersteller:</td><td><span style="color:white;font:Bold 12px Arial, Helvetica;">', $query[0]);
	$manufactor = explode('<', $manufactor[1]);
	$manufactor = $manufactor[0];

	$query[0] = null;

	foreach ($query as $row)
	{
		if ($row)
		{
			$row = explode('"', $row);
			$row = $row[0];

			$ext = explode('.', $row);
			$ext = $ext[count($ext) - 1];

	    	if ($r_query[$Eingespielt.'#'.$id])
	    	{
				$old++;
			}
			else
			{
				$files[] = array($Eingespielt.'#'.$id, $row, $titel." (".$manufactor.") (".$year.") (".$translator2[$row[0]].").".$ext);
				$new++;
			}
		}
	}

	print "load ".$dir."\t";
	print "found new:".$new.", old:".$old."\n";
}
	
print "<table><tr><td><pre>";

foreach ($files as $row)
{
	print $row[0]."\n";
}
print "</td><td><pre>";

foreach ($files as $row)
{
	print "<a href=\"http://c64games.de/hugo.php?art=".$row[1]."\">".$row[2]."</a>\n";
}

print "</td></tr></table>";

?>