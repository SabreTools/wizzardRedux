<?php

print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

function loadDir($dir){
	GLOBAL $r_query, $newURLs;
	$new=0;
	$old=0;
	$folder=0;

	print "open ".$dir."\n";
	$query=implode ('', file ("https://api2.wuala.com/previewSorted/".$dir."?il=1&ff=0&key=ROMCollections"));

	$publicFolders=explode('publicFolders',$query);
	$publicFolders=explode('breadcrumb',$publicFolders[1]);
	$publicFolders=explode('url="https://www.wuala.com/',$publicFolders[0]);
	array_splice ($publicFolders,0,1);

	foreach($publicFolders as $publicFolder){
		$publicFolder=explode('"',$publicFolder);
		$publicFolder=$publicFolder[0];
		loadDir($publicFolder);
		$folder++;
	}

	$publicFiles=explode('publicFiles',$query);
	$publicFiles=explode('publicFolders',$publicFiles[1]);
	$publicFiles=explode('<item ',$publicFiles[0]);
	array_splice ($publicFiles,0,1);

	foreach($publicFiles as $publicFile){
		$url=explode('url="https://www.wuala.com/',$publicFile);
		$url=explode('"',$url[1]);
		$url=$url[0];
		
		$name=explode('name="',$publicFile);
		$name=explode('"',$name[1]);
		$name=$name[0];

		if(!$r_query[$url])
		{
			$newURLs[]=Array($name,$url);
			$new++;
		}
		else
		{
			$old++;
		}
	}

	print "close ".$dir."\n";
	print "new: ".$new.", old: ".$old.", folder: ".$folder."\n";
}

loadDir('srost/');

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach($newURLs as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach($newURLs as $url)
{
	print "<a href=\"https://content.wuala.com/contents/".$url[1]."?dl=1&key=ROMCollections\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>