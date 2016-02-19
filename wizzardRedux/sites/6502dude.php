<?php

// Original code: The Wizard of DATz

$base_dl_url = "http://armas.cbm8bit.com/tapescans/victaps/";

$pages = Array(
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
	$query = implode ('', file ($newfile));
	$query = preg_replace('/(\s+)/',' ', $query); // Remove all whitespace
	$query = preg_replace('/(href=)("?)(\S+?)("?)(>)/','\1"\3"\5', $query); // Make sure all hrefs are quoted properly
	$query = explode ('<tr ',$query);
	$query[0] = null;

	$old = 0;
	$new = 0;

	foreach ($query as $row)
	{
		if($row != "")
		{
			$row = explode('<td', $row);
			$title = trim(str_replace(" ( NO SCAN YET )", "", strip_tags('<td'.$row[1])));
			$info = trim(strip_tags('<td'.$row[2]));
			$dls = explode ('tapescans/victaps/', $row[3]);
			$dls[0] = null;

			foreach ($dls as $dl)
			{
				if($dl != "")
				{
					$dl = explode ('"', $dl);
					$dl = $dl[0];
						
					$ext = explode ('.', $dl);
					$ext = $ext[count($ext)-1];

					if($r_query[$dl] != "")
					{
						$old++;
					}
					else
					{
						$found[] = Array($dl,$title." (".$info.").".$ext);
						$new++;
					}
				}
			}
		}
	}

	echo "Loading ".$newfile.$tab;
	echo "Found new: ".$new.", old: ".$old."<br/>";
}

echo "<h2>New files:</h2>";

foreach ($found as $row)
{
	echo "<a href='".$base_dl_url.$row[0]."'>".$row[0]."</a><br/>";
}

echo "\n";

?>