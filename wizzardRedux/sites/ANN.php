<?php
 
$dirs=Array(
	'http://ann.hollowdreams.com/anndisks.html',
);

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

function listDir($dir){
	GLOBAL $newURLs, $r_query;

	print "load: ".$dir."\n";
	$query=implode ('', file ($dir));
	$query=explode(' href="',$query);
	$query[0]=null;

	$new=0;
	$old=0;
	$other=0;

	foreach($query as $row){
		if($row){
			$url=explode('"',$row);
			$url=$url[0];

			$ext=explode('.',$url);


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
	print "new: ".$new.", old: ".$old.", other:".$other."\n";
}

print "<pre>check folders:\n\n";

foreach($dirs as $dir)
{
	if($dir)listDir($dir);
}

print "\nnew urls:\n\n";

foreach($newURLs as $url)
{
	print "<a href=\"http://ann.hollowdreams.com/".$url."\">".$url."</a>\n";
}

?>