<?php

include "ru2lat.php";

print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

$dir="http://spectrum4ever.org/fulltape.php?go=releases&scr=1";

print "load: ".$dir."\n";
$query=implode ('', file ($dir));
$query=ru2lat($query);
$query=str_replace("\r\n","",$query);
$query=explode('<div id="littletipwrapper">',$query);
$query=explode('</div>',$query[1]);
$query=explode('<tr>',$query[0]);
array_splice ($query,0,1);

$new=0;
$old=0;

foreach($query as $row){
	$row=explode('<td',$row);
	$url=explode("href='",$row[2]);
	$url=explode("'",$url[1]);
	$url=$url[0];
	if($url){
		$title=trim(strip_tags('<td'.$row[2]));
		$info=Array();
	
		for($x=3;$x<8;$x++)
		{
			if($x!=6){
				$temp=trim(strip_tags('<td'.$row[$x]));
				if($x==5)$temp=str_replace('_','',$temp);
				if($temp)$info[]=$temp;
			}
		}
	
		if($info)$title=$title." (".implode(") (",$info).")";

		if(!$r_query[$url])
		{
			$newURLs[]=Array($title,$url);
			$new++;
			$r_query[$url]=true;
		}
		else
		{
			$old++;
		}
	}
}

print "new: ".$new.", old: ".$old."\n";

$dir="http://spectrum4ever.org/fulltape.php?go=studios";
print "load: ".$dir."\n";
$query=implode ('', file ($dir));
$query=ru2lat($query);
$query=explode('fulltape.php?go=studio&id=',$query);
array_splice ($query,0,1);

foreach($query as $row){
	$id=explode('"',$row);
	$id=$id[0];
	$title=explode('</a>',$row);
	$title=explode('>',$title[0]);
	$title=trim($title[1]);
	$dir2="http://spectrum4ever.org/fulltape.php?go=studio&id=".$id;
	print "load: ".$dir2."\n";
	$query2=implode ('', file ($dir2));
	$query2=ru2lat($query2);
	$query2=explode('<div class="cian">',$query2);
	array_splice ($query2,0,1);

	$new=0;
	$old=0;

	foreach($query2 as $row2){
		$title2=explode('</div>',$row2);
		$title2=trim(strip_tags($title2[0]));
		$title2=str_replace(" &nbsp; "," - ",$title2);
		$title2=str_replace(" &nbsp;","",$title2);
	
		$part=explode('<td',$row2);
	
		$main_url=explode('download.php?t=t&id=',$part[0]);
		$main_url=explode('"',$main_url[1]);
		$main_title=explode('<',$main_url[1]);
		$main_title=explode('>',$main_title[0]);
		$main_url=$main_url[0];
		$main_title=explode('.',$main_title[1]);
		array_splice($main_title,-1,1);
		$main_title=implode('.',$main_title);
	
		if($main_url)
		{
			$url="download.php?t=t&id=".$main_url;
			if(!$r_query[$url])
			{
				$newURLs[]=Array($title." ".$title2." (".$main_title.")",$url);
				$new++;
				$r_query[$url]=true;
			}
			else
			{
				$old++;
			}
		}
	
		for($x=1;$x<3;$x++){
			$query3=explode('<li>',$part[$x]);
			array_splice ($query3,0,1);
			foreach($query3 as $row3){
	        	$main_url=explode('download.php?t=fulltape&id=',$row3);
				$main_url=explode('"',$main_url[1]);
				$main_title=explode('<',$main_url[1]);
				$main_title=explode('>',$main_title[0]);
				$main_url=$main_url[0];
				$main_title=$main_title[1];
	
				$url="download.php?t=fulltape&id=".$main_url;
				if(!$r_query[$url])
				{
					$newURLs[]=Array($title." ".$title2." (Side ".$x.") (".$main_title.")",$url);
					$new++;
				}
				else
				{
					$old++;
				}
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
	print "<a href=\"http://spectrum4ever.org/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>