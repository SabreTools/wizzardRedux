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

foreach ($pages as $newfile)
{
	$query = get_data($newfile); // Read the whole page into one string
	$query = preg_replace('/(\s+)/',' ', $query); // Remove all whitespace
	$query = preg_replace('/(href=)("?)(\S+?)("?)(>)/','\1"\3"\5', $query); // Make sure all hrefs are quoted properly
	$query = explode ('<tr ',$query); // Separate lines based on table rows
	unset($query[0]); // The first item is never a match so unset it

	$old = 0;
	$new = 0;

	// For each table row, process and get links
	foreach ($query as $row)
	{
		// If the row is not empty or null
		if ($row)
		{
			$row = explode('<td', $row); // Separate lines based on table cells
			$title = trim(str_replace(" ( NO SCAN YET )", "", strip_tags('<td'.$row[1]))); // Extract the title from the row
			$info = trim(strip_tags('<td'.$row[2])); // Extract the information from the row
			$dls = explode('tapescans/victaps/', $row[3]); // Get any downloads that can be found in the row
			unset($dls[0]); // The first item is never a match so unset it

			// For each download found, see if we've included it already
			foreach ($dls as $dl)
			{
				// If the download is not empty or null
				if ($dl)
				{
					$dl = explode('"', $dl);
					$dl = $dl[0];
						
					$ext = explode('.', $dl);
					$ext = $ext[count($ext)-1];

					if ($r_query[$dl] != "")
					{
						$old++;
					}
					else
					{
						$found[] = array($dl, $title." (".$info.").".$ext);
						$new++;
					}
				}
			}
		}
	}

	echo "Loading ".$newfile.$tab;
	echo "Found new: ".$new.", old: ".$old."<br/>";
}

echo "\n";

foreach ($found as $row)
{
	echo htmlspecialchars($row)."<br/>";
	echo "<a href='http://armas.cbm8bit.com/tapescans/victaps/".$row[0]."'>".$row[0]."</a><br/>";
}

?>