<?php

// Original code: The Wizard of DATz

$add = array (
	1 	=> 	array (" (AtariArea)"),
	2	=>	array (" (Atarex)"),
	3	=>	array (" (not protected, working) (POLISH)",
					" (protected, not working) (POLISH)",
					" (not protected, working)"),
	4	=>	array (" (fixed loader)"),
	5	=>	array (" (nonstandard, fixed loader)",
					" (protected, not working)",
					" (nonstandard)",
					"(Oskar)",
					" (works with cas2sio, emu)",
					" (Sonix) (works with ape, cas2sio)"),
	6	=>	array (""),
	7	=>	array (" (Sikor Soft)"),
	8	=>	array (" (works with cas2sio) (Oskar)",
					" (works with emu) (Oskar)",
					" (Haga Software) (Oskar)"),
);

echo "<table>\n";
for ($x = 1; $x < 9; $x++)
{
	echo "<tr><td>http://cas-archive.pigwa.net/cas".$x.".htm</td>";
	$query = get_data("http://cas-archive.pigwa.net/cas".$x.".htm");
	
	preg_match_all("/(<p.*?<u>.*?)?<a href=\"ftp:\/\/ftp\.pigwa\.net\/stuff\/collections\/stryker\/cas\/(.*?)\">(.*?)<\/a>/s", $query, $query);
	
	$newrows = array();
	for ($index = 0; $index < sizeof($query[0]); $index++)
	{
		$newrows[] = array($query[0][$index], $query[2][$index], $query[3][$index]);
	}

	$addnr = 0;
	$new = 0;
	$old = 0;

	foreach ($newrows as $row)
	{
		$url = $row[1];
		
		if (!$r_query[$url])
		{
			$ext = explode('.', $url);
			$ext = $ext[count($ext) - 1];
			$name = trim(strtr($rom[2], $normalize_chars));
			
			$found[] = array($name.$add[$x][$addnr].".".$ext, "ftp://ftp.pigwa.net/stuff/collections/stryker/cas/".$url);
			$new++;
		}
		else
		{
			$old++;
		}
		
		if (strstr($row[0], '<u>'))
		{
			$addnr++;
		}
	}

	echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";
}
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>