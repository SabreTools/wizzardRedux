<?php

// Original code: The Wizard of DATz

print "<pre>";

$newfiles = array (
	'http://c64.rulez.org/pub/c64.hu/NEWFILES.txt',
	'http://c64.rulez.org/pub/c64/NEWFILES.txt',
	'http://c64.rulez.org/pub/c64/Hall_of_Fame/NEWFILES.txt',
	'http://c64.rulez.org/pub/plus4/NEWFILES.txt',
	'http://c64.rulez.org/pub/c128/NEWFILES.txt',
);

foreach ($newfiles as $newfile)
{
	print "load ".$newfile."\n";
	$query = implode('', file ($newfile));
 	$query = explode("\n", $query);
 	$dir = explode("/", $newfile);
	$dir[count($dir) - 1] = null;
 	$dir = implode("/", $dir);

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
			print "<a href=\"".$dir.$row."\">".$row."</a>\n";
			$new++;
		}
	}

	print "found new:".$new.", old:".$old."\n\n";
}

?>