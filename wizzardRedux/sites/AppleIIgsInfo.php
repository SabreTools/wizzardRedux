<?php

// Original code: The Wizard of DATz

print "<pre>";

$max = 100000;

for ($page = 0; $page < $max; $page++)
{
	$dir = "http://www.apple-iigs.info/logiciels.php?arechercher=&begin=".(($page * 10) + 1);
	print "load: ".$dir."\n";
	$query = implode('', file($dir));
	$query = explode('detlogiciels.php?nom=', $query);
	array_splice($query, 0, 1);

	$new = 0;
	$old = 0;
	
	$notFound = true;
	
	foreach ($query as $row)
	{
		$row = explode('&origine', $row);
		$row = $row[0];
		$dir = "http://www.apple-iigs.info/detlogiciels.php?nom=".urlencode($row);

		print "load: ".$dir."\n";

		$queryb = implode('', file($dir));
		$queryb = explode("<a href='../", str_replace("\r", "", $queryb));
		array_splice($queryb, 0, 1);

		foreach ($queryb as $DL)
		{
			$DL = explode("'", $DL);
			$DL = $DL[0];

			$ext = explode('.', $DL);
			$ext = $ext[count($ext) - 1];

			if (!$r_query[$DL])
			{
				$found[] = Array($row.".".$ext, $DL);
				$new++;
				$r_query[$DL] = true;
			}
			else
			{
				$old++;
			}

		}

		$notFound = false;
	}

	if ($notFound)
	{
		$page=$max;
	}

	print "new: ".$new.", old: ".$old."\n";
}

$dirs = Array(
	"http://www.apple-iigs.info/logicielspartitions.php",
	"http://www.apple-iigs.info/logicielsgoldengrail.php",
);

foreach ($dirs as $dir)
{
	print "load: ".$dir."\n";
	
	$new = 0;
	$old = 0;
	
	$queryb = implode('', file($dir));
	$queryb = explode('<tr class', $queryb);
	array_splice($queryb, 0, 1);
	
	foreach ($queryb as $row)
	{
		$DL = explode('<a href="../', $row);
		$DL = explode('"', $DL[1]);
		$DL = $DL[0];
	
		$titel = explode('</tr>', $row);
		$titel = trim(strip_tags('<tr class'.$titel[0]));
	
		$ext = explode('.', $DL);
		$ext = $ext[count($ext) - 1];
	
		if (!$r_query[$DL])
		{
			$found[] = array($titel.".".$ext, $DL);
			$new++;
			$r_query[$DL] = true;
		}
		else
		{
			$old++;
		}
	
	}
	print "new: ".$new.", old: ".$old."\n";
}


$dir = "http://www.apple-iigs.info/revueinderauge.php";
print "load: ".$dir."\n";
$query = implode ('', file($dir));
$query = explode('Retour menu principal</a></li>', $query);
$query = explode('</ul>', $query[1]);
$query = explode("<li><a href='", $query[0]);
array_splice($query, 0, 1);

foreach ($query as $row)
{
	$page = explode("'", $row);
	$page = $page[0];

	$titel = explode("<", $row);
	$titel = explode(">", $titel[0]);
	$titel = $titel[1];

	$dir = "http://www.apple-iigs.info/".$page;
	print "load: ".$dir."\n";

	$new = 0;
	$old = 0;
	
	$queryb = implode ('', file($dir));
	$queryb = explode('<tr class', str_replace("\r", "", $queryb));
	array_splice($queryb, 0, 1);
	
	foreach ($queryb as $row)
	{
		$titel2 = explode('</tr>', $row);
		$titel2 = trim(strip_tags('<tr class'.$titel2[0]));
	
		$DLs = explode(" href='../", $row);
		array_splice($DLs, 0, 1);
	
		foreach ($DLs as $DL)
		{
			$DL = explode("'", $DL);
			$DL = $DL[0];
		
			$ext = explode('.', $DL);
			$ext = $ext[count($ext) - 1];
		
			if (!$r_query[$DL])
			{
				$found[] = array($titel." (".$titel2.").".$ext, $DL);
				$new++;
				$r_query[$DL] = true;
			}
			else
			{
				$old++;
			}
		}
	}
	print "new: ".$new.", old: ".$old."\n";

}

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach($found as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach($found as $url)
{
	print "<a href=\"http://www.apple-iigs.info/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>