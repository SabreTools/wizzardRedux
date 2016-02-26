<?php

// Original code: The Wizard of DATz

print "<pre>";

$newfiles = array(
	'http://heranbago.com/hax/hax/hack.htm',
	'http://sonar.heranbago.com/top.html',
);

foreach ($newfiles as $newfile)
{
	print "load ".$newfile."\n";
	$query = implode('', file($newfile));
 	$query = explode('<a href="', str_replace('&amp;', '&', $query));
	$query[0] = null;

	$old = 0;
	$new = 0;

	$dir = explode('/', $newfile);
	$dir[count($dir) - 1] = null;
	$dir = implode('/', $dir);

	foreach ($query as $row)
	{
		if ($row)
		{
			$row = explode('"', $row);
			$row = trim($row[0]);

			if (substr($row, 0, 4) != 'http')
			{
				$row = $dir.$row;
			}

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
	print "<a href=\"".$row."\">".$row."</a>\n";
}

?>