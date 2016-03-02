<?php

// Original code: The Wizard of DATz

print "<pre>check folders:\n";

$dirs = array(
	'alchemist-software',
	'books',
	'compilations',
	'csscgc',
	'demos',
	'disks-inform',
	'games',
	'games-extras/',
	'interface2-roms',
	'magazines',
	'misc',
	'music/bonustracks',
	'timex',
	'trdos',
	'utils',
	'zx81/games',
);

$badexts = array(
		".doc",
		".gif",
		".jpg",
		".pdf",
		".png",
		".rtf",
		".tif",
		".txt",
);

$new = 0;
$bad = 0;
$old = 0;

foreach ($dirs as $dir)
{
	checkdir($dir);
}

function checkdir($url)
{
	global $badexts, $badnames, $r_query, $found, $dirs, $new, $bad, $old;

	$directory = get_data("http://www.worldofspectrum.org/pub/sinclair/".$url);
	preg_match_all("/<li><a href=\"(.*)\">.*<\/a><\/li>/", $directory, $urls);
	
	foreach ($urls[1] as $curentry)
	{
		$isfile = strpos(strrev($curentry), "/") !== 0;
		$newurl = $url.(strpos($curentry, "/") === 0 || strpos(strrev($url), "/") === 0 ? "" : "/").$curentry;
		
		if ($isfile)
		{
			$ext = strtolower(substr($curentry, sizeof($curentry) - 5));
			
			if (in_array($ext, $badexts))
			{
				$bad++;
			}
			elseif (!$r_query["pub/sinclair/".$newurl])
			{
				//print "found: ".$newurl."\n";
				$found[] = $newurl;
				$new++;
			}
			else
			{
				$old++;
			}
		}
		elseif (strpos($curentry, "/") !== 0)
		{
			//print "dir: ".$newurl."\n";
			checkdir($newurl);
		}
	}
}

print "\nnew: ".$new." bad: ".$bad." old: ".$old;

print "\nnew urls:\n\n";

foreach ($found as $url)
{
	print "<a href=\"http://www.worldofspectrum.org/".str_replace('#','%23',$url)."\">".$url."</a>\n";
}

?>