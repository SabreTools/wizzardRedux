<?php

$dirs=Array(
	'http://vg5000.free.fr/educatifs.htm',
	'http://vg5000.free.fr/jeux.htm',
	'http://vg5000.free.fr/utilitaires.htm',
	'http://vg5000.free.fr/compilation.htm',
);

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n",$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

function listDir($dir){
	GLOBAL $newURLs, $r_query;

	print "load: ".$dir."\n";
	$query=str_replace("\n",'',implode ('', file ($dir)));
	$query=explode(' href="',$query);
	$query[0]=null;

	$new=0;
	$old=0;

	foreach($query as $row){
		if($row){
			$url=explode('"',$row);
			$url=$url[0];

			$text=explode('</p>',$row);
			$text=trim(strip_tags('<a href="'.$text[0]));

			$ext=explode('.',$url);

			if(!$r_query[$url])
			{
				$newURLs[]=Array($url,$text.'.'.$ext[count($ext)-1]);
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
	if($dir)listDir($dir);
}

print "\nnew urls:\n\n";

	print "<table><tr><td><pre>";

	foreach($newURLs as $row)
	{
		print $row[0]."\n";
	}

	print "</td><td><pre>";

	foreach($newURLs as $row)
	{
		print "<a href=\"http://vg5000.free.fr/".$row[0]."\" target=_blank>".$row[1]."</a>\n";
	}

	print "</td></tr></table>";



?>