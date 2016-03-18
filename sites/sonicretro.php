<?php

// Original code: The Wizard of DATz

print "<pre>";

$pages = array();
$cats = array(
		"/Category:Hacks",
		"/Category:Prereleases",
		"/Category:Pirate_and_unlicensed_games"
);

foreach ($cats as $cat)
{
	print "load http://info.sonicretro.org".$cat."\n";
	$query = get_data("http://info.sonicretro.org".$cat);

	$query = explode('<h2>Subcategories</h2>', $query);
	$query = explode('</table>', $query[1]);
	$query = explode('<li><a href="', $query[0]);
	$query[0] = null;
	
	$new = 0;
	
	foreach ($query as $row)
	{
		if ($row)
		{
			$row = explode('"', $row);
			$row = $row[0];

			print "found ".$row."\n";

			$cats[$row] = $row;
			$new++;
	   	}
	}
	
	print "found cats ".$new."\n";
}

foreach ($cats as $cat)
{
	$old = 0;
	$new = 0;
	print "load http://info.sonicretro.org".$cat."\n";
	$query = get_data("http://info.sonicretro.org".$cat);
	$query = explode('<h2>Pages in category', $query);
	$query = explode('</table>', $query[1]);
	$query = explode('<li><a href="', $query[0]);
	$query[0] = null;

	foreach ($query as $row)
	{
		if ($row)
		{
			$row = explode('"', $row);
			$row = $row[0];

			print "found ".$row."\n";

	    	if ($pages[$row])
			{
				$old++;
			}
			else
			{
				$pages[$row] = $row;
				$new++;
			}
    	}
	}

	print "found sites add:".$new.", skipped:".$old."\n";
}

foreach ($pages as $page)
{
	print "load http://info.sonicretro.org".$page."\n";
	$query = get_data("http://info.sonicretro.org".$page);

	$title = explode('<title>', $query);
	$title = explode(' - Sonic Retro</title>', $title[1]);
	$title = $title[0];

 	$query = explode(' href="/images/', $query);
	array_splice($query, 0, 1);

	$old = 0;
	$new = 0;

	foreach ($query as $row)
	{
		$row = explode('"', $row);
		$row = $row[0];

		$file = explode('/', $row);
		$file = $file[count($file) - 1];

		print "load http://info.sonicretro.org/File:".$file."\n";

		$f_query = get_data("http://info.sonicretro.org/File:".$file);
	 	$f_query = explode(' href="/images/', $f_query);
		array_splice($f_query, 0, 1);

		$found_new = false;

		foreach ($f_query as $f_row)
		{
			$f_row = explode('"', $f_row);
			$f_row = $f_row[0];

			$f_file = explode('/', $f_row);
			$f_file = $f_file[count($f_file) - 1];
			
			print "found :".$f_row." # ".$title."~~~~".$f_file."\n";

	    	if ($r_query[$f_row])
	    	{
				$old++;
			}
			else
			{
				$found[$f_row] = array($f_row, $title."~~~~".$f_file);
				$new++;
				$found_new = true;
			}
		}

		if ($found_new)
		{
			$found[$row] = array($row, $title."~~~~".$file);
		}
	}

	print "found new:".$new.", old:".$old."\n";
}

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print $row[0]."\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=\"http://info.sonicretro.org/images/".$row[0]."\">".$row[1]."</a>\n";
}

print "</td></tr></table>";

?>