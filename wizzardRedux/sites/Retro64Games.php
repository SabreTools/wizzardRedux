<?php

print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

$dirs=implode ('', file ("http://retro64games.net/C64files/"));
$dirs=explode('<a href="',$dirs);

foreach($dirs as $dir){
	$dir=explode('"',$dir);
	$dir=$dir[0];

	$ext=explode('.',$dir);
	$ext=$ext[1];

	if($ext=="html"){
		$new=0;
		$old=0;

		print "load: ".$dir."\n";
		$query=implode ('', file ("http://retro64games.net/C64files/".$dir));
		$query=explode('<table width="100%" cellspacing="2" cellpadding="0">', html_entity_decode($query));
		array_splice ($query,0,1);

		foreach($query as $row){
			$Game=explode('Game:',$row);
			$Game=explode("\n",$Game[1]);
			$Game=trim(strip_tags($Game[0]));
		
			if($Game){
				$Publisher=explode('Publisher:',$row);
				$Publisher=explode("\n",$Publisher[1]);
				$Publisher=trim(strip_tags($Publisher[0]));
	
				$Year=explode('Year:',$row);
				$Year=explode("\n",$Year[1]);
				$Year=trim(strip_tags($Year[0]));
	
				$DL=explode('<a href="',$row);
				$DL=explode('"',$DL[1]);
				$DL=$DL[0];
	
				$ext=explode('.',$DL);
				$ext=$ext[count($ext)-1];
	
				$title=str_replace('?','x',$Game." (".$Publisher.") (".$Year.")").".".$ext;
	
				if(!$r_query[$DL])
				{
					$newURLs[]=Array($title,$DL);
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
}


function listDir($dir){
	GLOBAL $newURLs, $r_query;

	print "load: ".$dir."\n";

	$query=implode ('', file ($dir));
	$query=explode('> Parent Directory<',html_entity_decode($query));
	if($query[1]){
		$query=$query[1];
	}else{
		$query=$query[0];
	}
	$query=explode(' href="',$query);
	$query[0]=null;

	$new=0;
	$old=0;

	foreach($query as $row){
		if($row){
			$url=explode('"',$row);
			$title=$url[0];
			$url=$dir.$url[0];

			if(substr($url, -1)=='/'){
				listDir($url);
			}else{
				$url=str_replace('http://retro64games.net/C64files/','',$url);

				if(!$r_query[$url])
				{
					$newURLs[]=Array($title,$url);
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

listDir("http://retro64games.net/C64files/c64games/");

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach($newURLs as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach($newURLs as $url)
{
	print "<a href=\"http://retro64games.net/C64files/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>