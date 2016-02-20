<?php

$dirs=Array(
	'http://www.bjars.com/',
	'http://www.bjars.com/tools.html',
	'http://www.bjars.com/resources.html',
	'http://www.bjars.com/disassemblies.html',
	'http://www.bjars.com/sourcecode.html',
	'http://www.bjars.com/mygames.html',
	'http://www.bjars.com/hacks.html',
	'http://www.bjars.com/mygames.html',
	'http://www.bjars.com/7800.html',
	'http://www.bjars.com/original/CaveIn/cavein.htm',
);

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();


$bad_ext=Array(
	'asm',
	'bas',
	'bmp',
	'doc',
	'gif',
	'jpg',
	'pdf',
	'png',
	'html',
	'txt',
	'com',
);


function listDir($dir){
	GLOBAL $newURLs, $r_query,$bad_ext;

	print "load: ".$dir."\n";

	$query=implode ('', file ($dir));
	$query=str_replace(' HREF="',' href="',$query);
	$query=str_replace("\r","",$query);
	$query=str_replace("\n","",$query);
	$query=str_replace("\t"," ",$query);
	$query=explode(' href="http://www.bjars.com/',$query);
	array_splice ($query,0,1);

	$new=0;
	$old=0;

	foreach($query as $row){
		$url=explode('"',$row);
		$url=$url[0];
		$title=explode('</a>',$row);
		$title=trim(strip_tags('<a "'.$title[0]));

		$ext=explode('.',$url);
		$ext=$ext[count($ext)-1];
		
		if(!in_array($ext,$bad_ext)){
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
	print "<a href=\"http://www.bjars.com/".$url[1]."\">".$url[0]."</a>\n";
}


print "</td><td><pre>";

foreach($newURLs as $url)
{
	print $url[1]."\n";
}

print "</td></tr></table>";

?>