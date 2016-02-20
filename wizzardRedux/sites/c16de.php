<?php

$dirs=Array(
	'http://c16.c64games.de/c16/tapes/',
	'http://c16.c64games.de/c16/demos/',
	'http://c16.c64games.de/c16/tools/',
	'http://c16.c64games.de/c16/spiele/',
	'http://c16.c64games.de/c16/basic/',
);

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

function listDir($dir){
	GLOBAL $newURLs, $r_query;

	print "load: ".$dir."\n";

	$query=implode ('', file ($dir));
	$query=explode('>Parent Directory<',$query);
	if($query[1]){
		$query=$query[1];
	}else{
		$query=$query[0];
	}
	$query=str_replace(' HREF="',' href="',$query);
	$query=explode(' href="',$query);
	$query[0]=null;

	$new=0;
	$old=0;

	foreach($query as $row){
		if($row){
			$url=explode('"',$row);
			$url=$dir.$url[0];

			if(substr($url, -1)=='/'){
				listDir($url);
			}else{
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

foreach($newURLs as $url)
{
	print "<a href=\"".$url."\">".$url."</a>\n";
}

?>