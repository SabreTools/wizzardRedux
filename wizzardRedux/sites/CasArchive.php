<?php

// Original code: The Wizard of DATz

print "<pre>";

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

for ($x = 1; $x < 9; $x++)
{
	print "load http://cas-archive.pigwa.net/cas".$x.".htm\n";
	$query = get_data("http://cas-archive.pigwa.net/cas".$x.".htm");
	$query = explode('<a href="ftp://ftp.pigwa.net/stuff/collections/stryker/cas/', $query);
	$query[0] = null;
	$addnr = 0;
	$count = 0;
	$new = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $url[0];

			if (!$r_query[$url])
			{
				$ext = explode('.', $url);
				$ext = $ext[count($ext) - 1];
				$name = explode('">', $row);
				$name = explode('<br>', $name[1]);
				$name = strip_tags($name[0]);
				$name = trim(strtr($name, $GLOBALS['normalize_chars']));

				print "<a href=\"ftp://ftp.pigwa.net/stuff/collections/stryker/cas/".$url."\" >".$name.$add[$x][$addnr].".".$ext."</a>\n";
				$found[] = $url;
				$new++;
			}
			
			$count++;
			if (strstr($row, '<u>'))
			{
				$addnr++;
			}
		}
	}

	print "found: ".$count.", new: ".$new."\n";
}

print "\nurls:\n\n";

foreach ($found as $row)
{
	print $row."\n";
}

?>