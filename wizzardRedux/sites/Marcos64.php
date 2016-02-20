<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

$pages=Array(
	"http://marcos64.orgfree.com/programas.html",
	"http://marcos64.orgfree.com/varias/crackesp.html",
	"http://marcos64.orgfree.com/varias/castellanos.html",
	"http://marcos64.orgfree.com/varias/loadnrun.html",
	"http://marcos64.orgfree.com/varias/plus4.html",
);

foreach ($pages as $page)
{
	print "load ".$page."\n";

	$content=implode ('', file ($page));
	$content=explode ('<a href="',$content);
	$content[0]=null;

	$URLs=Array();

	$new=0;
	$old=0;

	$page=explode ('/',$page);
	$page[count($page)-1]=null;
	$page=implode ('/',$page);
	
	foreach($content as $row){
		if($row){
			$url=explode ('"',$row);
			$url=$url[0];
			$ext=explode('.',$url);
			$url=$page.$url;

			if(count($ext)>1)
			{
				$name=explode ('</a>',$row);
				$name=trim(strip_tags('<a href="'.$name[0]));

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