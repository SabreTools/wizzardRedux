<?php

// Original code: The Wizard of DATz

print "<pre>";

$dirs = array();

$max = 10000;

$last = null;

for ($page = 1; $page < $max; $page++)
{
	$dir = "http://zxdemo.org/productions/?count=200&page=".$page;
	print "load: ".$dir."\n";
	$query = implode('', file($dir));
	$query = explode('<tr class="',$query);
	array_splice($query, 0, 1);

	$new = 0;
	$old = 0;

	$notFound = true;

	if ($last != $query[0])
	{
		$last = $query[0];

		foreach ($query as $row)
		{
			$row = explode('<td', $row);
			$title = trim(strip_tags('<td'.$row[2]));
	
			$info = array();
	
			for ($x = 4; $x < 7; $x++)
			{
				$temp = trim(strip_tags('<td'.$row[$x]));
				if ($temp)
				{
					$info[] = $temp;
				}
			}
	
			if ($info)
			{
				$title = $title." (".implode(") (", $info).")";
			}
	
			$title = preg_replace('/\s+/', " ", $title);
	
			$DLs = explode('href="http://www.zxdemo.org', $row[3]);
			array_splice($DLs, 0, 1);

			foreach ($DLs as $DL)
			{
				$DL = explode('"', $DL);
				$DL = $DL[0];
	
				$ext = explode('.', $DL);
				$ext = $ext[count($ext) - 1];
	
				if (!$r_query[$DL])
				{
					$found[] = array($title.".".$ext, $DL);
					$new++;
				}
				else
				{
					$old++;
				}

				$notFound=false;
	        }
		}
	}
	
	if ($notFound)
	{
		$page = $max;
	}

	print "new: ".$new.", old: ".$old."\n";
}

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"http://zxdemo.org".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>