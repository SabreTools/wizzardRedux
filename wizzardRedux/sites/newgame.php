<?php

// Original code: The Wizard of DATz

print "<pre>";

$newfiles = array(
	'http://www.newgame.ru/16bit/roms.htm',
);

foreach ($newfiles as $newfile)
{
	print "load ".$newfile."\n";
	$query = get_data($newfile);
 	$query = explode('<a href="roms/', str_replace('&amp;', '&', $query));
	$query[0] = null;

	$old = 0;
	$new = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$row = explode('"', $row);
			$row = $row[0];

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


	print "found new:".$new.", old:".$old."\n\n";
}

foreach ($found as $row)
{
	print "<a href=\"http://www.newgame.ru/16bit/roms/".$row."\">".$row."</a>\n";
}

?>