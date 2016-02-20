<?php

print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

$new=0;
$old=0;

$query=implode ('', file ("https://sites.google.com/site/msxbasicgames/"));
$query=explode('<td class="td-file">',str_replace('&amp;','&',utf8_decode ($query)));
array_splice ($query,0,1);
foreach($query as $row){
	$url=explode('<a href="/site/msxbasicgames/archivos/',$row);
	$url=explode('"',$url[1]);
	$url=$url[0];

	$ext=explode('<br />',$row);
	$ext=explode('.',$ext[0]);
	$file=$ext[count($ext)-2];
	$ext=$ext[count($ext)-1];

	$title=explode('<td class="td-desc filecabinet-desc" dir="ltr">',$row);
	$title=explode('<',$title[1]);
	if($title[0]){
		$title=explode(' / ',$title[0]);
		$title=$title[0]." (".$title[1].")";
	}else{
		$title=$file;
    }

	if(!$r_query[$url])
	{
		$newURLs[]=Array($title.".".$ext,$url);
		$new++;
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
	print "<a href=\"https://sites.google.com/site/msxbasicgames/archivos/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>