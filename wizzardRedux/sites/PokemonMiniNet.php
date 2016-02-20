<?php

$dirs=Array(
	'http://www.pokemon-mini.net/downloads/',
);

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

function listDir($dir){
	GLOBAL $newURLs, $r_query;

	print "load: ".$dir."\n";

	$query=implode ('', file ($dir));
	$query=explode('<div class="wpfilebase-fileicon"><a href="',$query);
	$query[0]=null;

	$new=0;
	$old=0;

	foreach($query as $row){
		if($row){
			$url=explode('"',$row);
			$title=$url[2];
			$url=$url[0];

			$ext=explode('.',$url);
			$ext=$ext[count($ext)-1];

			if(!$r_query[$url])
			{
				$newURLs[]=array($title.'.'.$ext,$url);
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
	print "<a href=\"".$url[1]."\">".$url[0]."</a>\n";
}


print "</td><td><pre>";

foreach($newURLs as $url)
{
	print $url[1]."\n";
}

print "</td></tr></table>";

?>