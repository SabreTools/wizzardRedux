<?php

print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();


$query=implode ('', file ('http://www.nanowasp.org/'));
$query=explode('<script src="v',$query);
$query=explode('"',$query[1]);
$query=$query[0];

$dir = 'http://www.nanowasp.org/v'.$query;
print $dir."\n";
$lines = gzfile($dir);

$query='';
foreach ($lines as $line) {
    $query=$query.$line;
}

$query=explode('{title:"',$query);
array_splice ($query,0,1);

$new=0;
$old=0;

foreach($query as $row){
	$titel=explode('"',$row);
	$titel=$titel[0];

	$author=explode(',author:"',$row);
	$author=explode('"',$author[1]);
	$author=$author[0];

	$url=explode(',url:"',$row);
	$url=explode('"',$url[1]);
	$url=$url[0];

	$ext=explode('.',$url);
	$ext=$ext[count($ext)-1];

	if($author){
		$titel=$titel." (".$author.").".$ext;
	}else{
		$titel=$titel.".".$ext;
	}

	if(!$r_query[$url])
	{
		$newURLs[]=Array($titel,$url);
		$new++;
		$r_query[$url]=true;
	}
	else
	{
		$old++;
	}
}

print "new: ".$new.", old: ".$old."\n";

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach($newURLs as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach($newURLs as $url)
{
	print "<a href=\"http://www.nanowasp.org/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>