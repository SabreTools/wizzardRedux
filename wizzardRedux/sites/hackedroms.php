<?php

$dirs=Array(
	'http://my.hacked.roms.free.fr/',
);

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

function listDir($dir){
	GLOBAL $newURLs, $r_query;

	print "load: ".$dir."\n";

	$query=implode ('', file ($dir));
	$query=explode('<a class="rom_lien" href="',$query);
	$query[0]=null;

	$new=0;
	$old=0;

	foreach($query as $row){
		if($row){
			$url=explode('"',$row);
			$url=$url[0];


			if(!$r_query[$url])
			{
				$newURLs[]=$url;
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old."\n";
}

print "<pre>check folders:\n\n";

foreach($dirs as $dir)
{
	listDir($dir);
}

print "\nnew urls:\n\n";

print "<table><tr><td><pre>";

foreach($newURLs as $url)
{
	print "<a href=\"".$url."\">".$url."</a>\n";
}

print "</td></tr></table>";

?>