<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n",$r_query);
	$r_query=array_flip($r_query);

$pages=Array(
	Array("http://www.stairwaytohell.com/roms/homepage.html","http://www.stairwaytohell.com/roms/"),
	Array("http://www.stairwaytohell.com/atom/wouterras/","http://www.stairwaytohell.com/atom/wouterras/"),
	Array("http://www.stairwaytohell.com/bbc/sthcollection.html","http://www.stairwaytohell.com/bbc/"),
	Array("http://www.stairwaytohell.com/bbc/archive/tapeimages/reclist.php?sort=dir&filter=.zip",
		"http://www.stairwaytohell.com/bbc/archive/tapeimages/",true),
	Array("http://www.stairwaytohell.com/bbc/archive/diskimages/reclist.php?sort=dir&filter=.zip",
		"http://www.stairwaytohell.com/bbc/archive/diskimages/",true),
	Array("http://www.stairwaytohell.com/bbc/other/educational/reclist.php?sort=name&filter=.zip",
		"http://www.stairwaytohell.com/bbc/other/educational/",true),
	Array("http://www.stairwaytohell.com/electron/uefarchive/reclist.php?sort=dir&filter=.zip",
		"http://www.stairwaytohell.com/electron/uefarchive/",true),
	Array("http://www.stairwaytohell.com/electron/t2p3/homepage.html",
		"http://www.stairwaytohell.com/electron/t2p3/"),
	Array("http://www.stairwaytohell.com/essentials/homepage.html",
		"http://www.stairwaytohell.com/essentials/"),
	Array("http://www.stairwaytohell.com/electron/dfs/homepage.html",
		"http://www.stairwaytohell.com/electron/dfs/"),
	Array("http://www.stairwaytohell.com/electron/adfs/homepage.html",
		"http://www.stairwaytohell.com/electron/adfs/"),
);

foreach ($pages as $page)
{
	print "load ".$page[0]."\n";

	$content=implode ('', file ($page[0]));
	$content=explode ('<A HREF="',preg_replace('/\s+/',' ',str_replace(
						Array("href=","\n","</a>","<a "),
						Array('HREF=',  '','</A>','<A '),$content)));
	$content[0]=null;

	$URLs=Array();

	$new=0;
	$old=0;
	
	foreach($content as $row){
		if($row){
			$url=explode ('"',$row);
			$suburl=explode ('/',$url[0]);
			$ext=explode('.',$url[0]);
			
			if(count($ext)>1)
			{
				$url=$page[1].$url[0];
	
				$name=explode ('</A>',$row);
				$name=strip_tags($name[0]);
				$name=explode ('>',$name);
				$name=trim($name[1]);
	
				$name=str_replace(".".$ext[count($ext)-1],'',$name);
	
				if($page[2])$name=$name." (".$suburl[count($suburl)-2].")";
	
				if(!$r_query[$url])	{
					$URLs[]=Array($url,$name.".".$ext[count($ext)-1]);
					$new++;
				} else {
					$old++;
				}
			}
		}
	}

	print "found new ".$new.", old ".$old."\n";

	print "<table><tr><td><pre>";
	
	foreach($URLs as $row)
	{
		print $row[0]."\n";
	}
	
	print "</td><td><pre>";
	
	foreach($URLs as $row)
	{
		print "<a href=\"".$row[0]."\" target=_blank>".$row[1]."</a>\n";
	}
	
	print "</td></tr></table>";
}

?>