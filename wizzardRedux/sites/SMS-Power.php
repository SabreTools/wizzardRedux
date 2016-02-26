<?php

// Original code: The Wizard of DATz

$dev_dir = 'http://www.smspower.org/Homebrew/Index?q=order=name';
$trans_dir = 'http://www.smspower.org/Translations/index';
$hack_dir = 'http://www.smspower.org/Hacks/Index';

print "<pre>check folders:".$dev_dir."\n\n";

$query = implode('', file($dev_dir));
$query = str_replace('?', "'", $query);
$query = explode("<a class='wikilink' href='http://www.smspower.org/Homebrew/", $query);
array_splice($query, 0, 1);

$checked = array();

foreach ($query as $dir)
{
 	$dir = explode("'", $dir);
	$dir = "http://www.smspower.org/Homebrew/".$dir[0];

	if (!$checked[$dir])
	{
		listDir($dir);
		$checked[$dir] = true;
	}
}

print "<pre>check folders:".$hack_dir."\n\n";

$query = implode('', file($hack_dir));
$query = str_replace('?', "'", $query);
$query = explode("<a class='wikilink' href='http://www.smspower.org/Hacks/", $query);
array_splice($query, 0, 1);

$checked = array();

foreach ($query as $dir)
{
 	$dir = explode("'",$dir);
	$dir = "http://www.smspower.org/Hacks/".$dir[0];

	if (!$checked[$dir])
	{
		listDir($dir);
		$checked[$dir] = true;
	}
}

print "<pre>check folders:".$trans_dir."\n\n";

$tquery = implode('', file($trans_dir));
$tquery = str_replace('?', "'", $tquery);
$tquery = explode("<li class='frame' style='display: inline-block; text-align: center; padding: 1em; margin: 1em;'><a class='wikilink' href='", $tquery);
$tquery[0] = null;

foreach ($tquery as $tdir)
{
	if ($tdir)
	{
		$tdir = explode("'", $tdir);
		$tdir = $tdir[0];

		print "<pre>check folders:".$tdir."\n\n";

		$query = implode('', file($tdir));
		$query = str_replace('?', "'", $query);
		$query = explode("<div><a class='wikilink' href='", $query);
		$query[0] = null;
		
		foreach ($query as $dir)
		{
			if ($dir)
			{
				$dir = explode("'", $dir);
				$dir = $dir[0];
				listDir($dir);
			}
		}
	}
}

print "\nnew urls:\n\n";

foreach ($found as $url)
{
	print "<a href=\"".$url."\">".$url."</a>\n";
}

$r_query = implode('', file("../sites/".$source."_crc.txt"));
$r_query = explode("\r\n","\r\n".$r_query);
$r_query = array_flip($r_query);

$dump_dir = 'http://www.smspower.org/Dumps/Index';

print "<pre>check folders:".$dump_dir."\n\n";

$query = implode('', file($dump_dir));
$query = str_replace('?', "'", $query);
$query = explode("<div class='fpltemplate'>", $query);
$query = explode("</div>", $query[1]);
$query = explode("href='", $query[0]);
$query[0] = null;

$new = 0;
$old = 0;

foreach ($query as $url)
{
	if ($url)
	{
		$url = explode("'", $url);
		$url = $url[0];

		// print $url."\n";

		$query2 = implode('', file($url));

		$crc = explode("CRC32</dt><dd>", $query2);
		$crc = explode("\n", $crc[1]);
		$crc = $crc[0];

		$titel = explode("Name</dt><dd>", $query2);
		$titel = explode("\n", $titel[1]);
		$titel = $titel[0];

		$type = explode("System</dt><dd>", $query2);
		$type = explode("\n", $type[1]);
		$type = $type[0];

		$zip = explode("Filename</dt><dd>", $query2);
		$zip = explode("\n", $zip[1]);
		$zip = $zip[0];

		if (!$r_query[$crc])
		{
			print $crc."\t".$titel.".".$type."\t".$zip."\n";
			$r_query[$crc] = true;
			$new++;
		}
		else
		{
			$old++;
		}
	}
}

print "new: ".$new.", old: ".$old."\n";

function listDir($dir)
{
	GLOBAL $found, $r_query;

	print "load: ".$dir."\n";
	$query = implode('', file($dir));
	$query = str_replace('?', "'", $query);
	$query = explode(" href='", $query);

	$query[0] = null;

	$new = 0;
	$old = 0;
	$other = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode("'", $row);
			$url = $url[0];

			$ext = explode('.', $url);

			if ($ext[count($ext) - 1] == 'zip')
			{
				if (!$r_query[$url])
				{
					$found[] = $url;
					$new++;
				}
				else
				{
					$old++;
				}
			}
			else
			{
				$other++;
			}
		}
	}

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old.", other:".$other."\n";
}

?>