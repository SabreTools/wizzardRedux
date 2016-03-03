<?php

// Original code: The Wizard of DATz

$max = 10000;

for ($xpage = 0; $xpage < $max; $xpage++)
{
	sleep(1); // Pause between reads so not to overload the server
	$dir = "/atari/ASTGA/astgam.php?s=7&o=".($xpage * 50);
	print "load: ".$dir."\n";
	$query = get_data($dir);
	$query = explode('<tr   onMouseOut=', $query);
	array_splice($query, 0, 1);

	$new = 0;
	$old = 0;

	$notFound = true;

	foreach ($query as $row)
	{
		$row = explode('<td', $row);
		$url = explode("href='", $row[7]);
		$url = explode("'", $url[1]);
		$url = $url[0];

		if ($url != "")
		{
			$notFound = false;

			$title = trim(strip_tags('<td'.$row[1]));
			$info = Array();
	
			for ($x = 2; $x < 5; $x++)
			{
				$temp = trim(strip_tags('<td'.$row[$x]));
				if ($temp && $temp != "author" && $temp != "n/a")
				{
					$info[] = $temp;
				}
			}

			if ($info)
			{
				$title = $title." (".implode(") (",$info).")";
			}

			sleep(1);
			$dir = "/atari/ASTGA/".$url;
			print "load: ".$dir."\n";
			$queryb = get_data($dir);
			$queryb = explode('<a href="', $queryb);
			array_splice($queryb, 0, 1);

			$dir = explode('/', $dir);
			$dir[count($dir) - 1] = null;
			$dir = implode('/', $dir);

			foreach ($queryb as $dl)
			{
				$dl = explode('"', $dl);
				$dl = $dl[0];

				$url = $dir.$dl;

				$ext = explode('.', $dl);
				$add = $ext[count($ext) - 2];
				$ext = $ext[count($ext) - 1];
				
				if (strtolower($ext) == "zip")
				{
					print "found: ".$title."{".$add."}.".$ext." # ".$url."\n";
	
					if (!$r_query[$url])
					{
						$found[] = array($url, $title."{".$add."}.".$ext);
						$new++;
					}
					else
					{
						$old++;
					}
				}
			}
		}
	}

	if ($notFound)
	{
		$xpage = $max;
	}

	print "new: ".$new.", old: ".$old."\n";
}

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='http://www.8bitchip.info".$row[0]."'>".$row[1]."</a><br/>\n";
}

?>