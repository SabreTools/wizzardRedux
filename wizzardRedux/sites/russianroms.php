<?php

// Original code: The Wizard of DATz

print "<pre>";

$query = get_data('http://russianroms.ru/');
$query = explode('?page_id=', $query);
$query[0] = null;

$urls = array();
foreach ($query as $row)
{
	if ($row)
	{
		$row = explode('"', $row);
		$row = $row[0];
		$urls[] = $row;
	}
}

foreach ($urls as $newfile)
{
	print "load ".$newfile."\n";
	$query = get_data("http://russianroms.ru/?page_id=".$newfile);
 	$query = explode('"><img src="http://russianroms.narod.ru/linkware.gif"', $query);
	$query[count($query) - 1] = null;

	$old = 0;
	$new = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$row = explode('"', $row);
			$row = $row[count($row) - 1];

			/*
			if (substr($row, 0, 4) != 'http')
			{
				$row = $dir.$row;
			}
			*/

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


	print "found new:".$new.", old:".$old."\n";
}

foreach ($found as $row)
{
	print "<a href=\"".$row."\">".$row."</a>\n";
}

?>