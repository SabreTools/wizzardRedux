<?php

// Original code: The Wizard of DATz

$pages = array(
		'http://armas.cbm8bit.com/0to9taps.html',
		'http://armas.cbm8bit.com/ataps.html',
		'http://armas.cbm8bit.com/btaps.html',
		'http://armas.cbm8bit.com/ctaps.html',
		'http://armas.cbm8bit.com/dtaps.html',
		'http://armas.cbm8bit.com/etaps.html',
		'http://armas.cbm8bit.com/ftaps.html',
		'http://armas.cbm8bit.com/gtaps.html',
		'http://armas.cbm8bit.com/htaps.html',
		'http://armas.cbm8bit.com/itaps.html',
		'http://armas.cbm8bit.com/jtaps.html',
		'http://armas.cbm8bit.com/ktaps.html',
		'http://armas.cbm8bit.com/ltaps.html',
		'http://armas.cbm8bit.com/mtaps.html',
		'http://armas.cbm8bit.com/ntaps.html',
		'http://armas.cbm8bit.com/otaps.html',
		'http://armas.cbm8bit.com/ptaps.html',
		'http://armas.cbm8bit.com/qtaps.html',
		'http://armas.cbm8bit.com/rtaps.html',
		'http://armas.cbm8bit.com/staps.html',
		'http://armas.cbm8bit.com/ttaps.html',
		'http://armas.cbm8bit.com/utaps.html',
		'http://armas.cbm8bit.com/vtaps.html',
		'http://armas.cbm8bit.com/wtaps.html',
		'http://armas.cbm8bit.com/xtaps.html',
		'http://armas.cbm8bit.com/ytaps.html',
		'http://armas.cbm8bit.com/ztaps.html',
);

echo "<table>\n";
foreach ($pages as $newfile)
{
	echo "<tr><td>".$newfile."</td>\n";
	$query = get_data($newfile);
	
	preg_match_all("/<tr.*?>(.*?)<\/tr>/s", $query, $query);
	$query = $query[1];
	unset($query[0]);

	$old = 0;
	$new = 0;

	// For each table row, process and get links
	foreach ($query as $row)
	{
		preg_match("/<td.*?>(.+?)<\/td>.*?<td.*?<div.*?>(.*?)<\/div>.*?(<td.*?<\/td>)/s", $row, $rowinfo);
		$title = strip_tags($rowinfo[1]);
		$info = $rowinfo[2];
		preg_match_all("/<a href=.*tapescans\/victaps\/(.*?)>/", $rowinfo[3], $dls);
		$dls = $dls[1];

		// For each download found, see if we've included it already
		foreach ($dls as $dl)
		{
			$dl = str_replace("\"", "", $dl);
			$ext = pathinfo($dl, PATHINFO_EXTENSION);

			if ($r_query[$dl] != "")
			{
				$old++;
			}
			else
			{
				$found[] = array($title." (".$info.").".$ext, "http://armas.cbm8bit.com/tapescans/victaps/".$dl);
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
	echo "<a href='".$row[1]."'>".$row[1]."</a><br/>\n";
}

echo "<br/>\n";

?>