<?php

// Original code: The Wizard of DATz

print "<pre>check folders:\n\n";

$dirs = array(
	'',
	'alchemist-software',
	'books',
	'compilations',
	'csscgc',
	'demos',
	'disks-inform',
	'games',
	'games-extras',
	'interface2-roms',
	'magazines',
	'misc',
	'music/bonustracks',
	'timex',
	'trdos',
	'utils',
	'zx81/games',
);

$dirs = array_flip($dirs);

//$directory = implode('', file($_GET["source"]."/ls-lR"));
$directory = implode('', file("ftp://ftp.worldofspectrum.org/pub/sinclair/ls-lR"));
$directory = explode("\n", $directory);

$curdir = "";
$lastdir = "#";
$check = false;

foreach ($directory as $curentry)
{
	if (substr($curentry, 0, 2) == "./")
	{
		if ($lastdir != "#")
		{
			print "close: ".$curdir."\n";
			print "new: ".$new.", old: ".$old."\n";
		}
		$curdir = substr($curentry, 2, -1);

		if ($dirs[$curdir])
		{
			print "open: ".$curdir."\n";
			$lastdir = $curdir;
			$check = true;
		}
		elseif(strpos('*'.$curdir, $lastdir))
		{
			print "open: ".$curdir."\n";
		}
		else
		{
			print "ignore: ".$curdir."\n";
			$lastdir = "#";
			$check = false;
		}
		$new = 0;
		$old = 0;
    }
    elseif (strtolower(substr($curentry, -4)) == ".zip" && $check)
    {
		$curfile = explode(":", $curentry);
		$curfile = substr($curfile[1], 3);
		$curfile = "pub/sinclair/".$curdir."/".$curfile;
		
		if ($r_query[$curfile])
		{
			$old++;
		}
		else
		{
			$found[] = $curfile;
			$new++;
		}
	}
}

print "\nnew urls:\n\n";

foreach ($found as $url)
{
	print "<a href=\"ftp://ftp.worldofspectrum.org/".str_replace('#','%23',$url)."\">".$url."</a>\n";
}

?>