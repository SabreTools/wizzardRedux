<?php

// Original code: The Wizard of DATz

print "<pre>";

$start=1;

for ($x = $start; $x < $start + 100; $x++)
{
	$query = get_data("http://karpez.ucoz.ru/load/0-".$x);
	$query = explode('<div class="eTitle" style="text-align:left;"><a href="http://karpez.ucoz.ru/load/', $query);
	$query[0] = null;
	
	foreach ($query as $row)
	{
		if ($row)
		{
			$row = explode('"', $row);
			$row = $row[0];

			print "found ".$row;

			if ($r_query[$row] || $found[$row])
			{
				print " reject\n";
				$x = 1000;
			}
			else
			{
				print " add\n";
				$found[$row] = $row;
			}
		}
	}
}

$found2 = array();
foreach ($found as $newfile)
{
	$id = explode('-', $newfile);
	$id = $id[count($id) - 1];
	$url = get_data("http://karpez.ucoz.ru/load/".$newfile);
 	$url = explode('-'.$id.'-20"', $url);
 	$url = explode('http://karpez.ucoz.ru/load/', $url[0]);
 	$url = $url[count($url) - 1];
	if ($url)
	{
		$found2[] = array($newfile, $url.'-'.$id.'-20');
	}
	else
	{
		print "error for ".$newfile."\n";
	}
}

$found = $found2;
unset($found2);

print "\n\n";

foreach ($found as $row)
{
	print "<a href=\"http://karpez.ucoz.ru/load/".$row[1]."\">".$row[0]."</a>\n";
}

?>