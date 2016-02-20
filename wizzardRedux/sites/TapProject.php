<?php

print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();
$dirs=Array();

$query=implode ('', file ("http://biotoxin.speccy.org/index.html"));
$query=explode('<td><b><a href="',$query);
array_splice ($query,0,1);
foreach($query as $url){
	$url=explode('"',$url);
	$dirs[]="http://biotoxin.speccy.org/".$url[0];
}


foreach($dirs as $dir){
	print "load: ".$dir."\n";
	$query=implode ('', file ($dir));
	$query=explode('<tr>',$query);
	array_splice ($query,0,1);

	$new=0;
	$old=0;

	foreach($query as $row){
		$row=explode('<td',$row);
		$url=explode('href="',$row[1]);
		$url=explode('"',$url[1]);
		$url=$url[0];

		if($url){
			$ext=explode('.',$url);
			$ext=$ext[count($ext)-1];
	
			$title=trim(strip_tags('<td'.$row[1]));
			$info=Array();
	
			for($x=2;$x<5;$x++)
			{
				$temp=trim(strip_tags('<td'.$row[$x]));
				if(($temp)&&($temp!="-"))$info[]=$temp;
			}
	
			if($info)$title=$title." (".implode(") (",$info).")";
	
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
	}

	print "new: ".$new.", old: ".$old."\n";
}

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach($newURLs as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach($newURLs as $url)
{
	print "<a href=\"http://biotoxin.speccy.org/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>