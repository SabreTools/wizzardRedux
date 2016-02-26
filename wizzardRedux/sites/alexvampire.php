<?php

// Original code: The Wizard of DATz

$newfiles = array(
	'https://alexvampire.wordpress.com/feed/',
);

foreach ($newfiles as $newfile)
{
	print "load ".$newfile."<br/>\n";
	$query = get_data($newfile);
 	$query = explode('<link>', $query);
	$query[0] = null;

	$old = 0;
	$new = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$dl = explode('</link>', $row);
			$dl = $dl[0];

	    	if ($r_query[$dl])
			{
				$old++;
			}
			else
			{
				$found[] = $dl;
				$new++;
			}
		}
	}

	foreach ($found as $row)
	{
		print "<a href=\"".$row."\">".$row."</a><br/>\n";
	}

	print "found new:".$new.", old:".$old."<br/>\n<br/>\n";
}

?>