<?php

print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

	$URL_Array=array();

	$murl= "http://superfamicom.org/blog/page/";

	for($x=1;$x<4;$x++){
		$new=0;
		$old=0;

		print "load: ".$murl.$x."\n";
		$query=implode ('', file ($murl.$x));
		$query=explode('<h2 class="posttitle"><a href="',$query);
		$query[0]=null;
	
		foreach($query as $row){
			if($row){
				$url=explode('"',$row);
				$url=$url[0];
				$title=explode("</a>",$row);
				$title=explode(">",$title[0]);
				$title=$title[1];
				if(!$r_query[$url])
				{
					$URL_Array[]=Array($url,$title);
					$new++;
				} else {
					$old++;
				}
			}
		}
		print "found new ".$new.", old ".$old."\n";
	}

	print "<table><tr><td><pre>";

	foreach($URL_Array as $row)
	{
		print $row[0]."\n";
	}

	print "</td><td><pre>";

	foreach($URL_Array as $row)
	{
		print "<a href=\"".$row[0]."\" target=_blank>".$row[1]."</a>\n";
	}

	print "</td></tr></table>";

	$URL_Array=array();
	$murl="http://superfamicom.org/blog/quick-rom-download-page/";
	$new=0;
	$old=0;

	print "load: ".$murl."\n";
	$query=implode ('', file ($murl));
	$query=explode("<div class='wdgpo wdgpo_standard_count'>",$query);
	$query=explode('<div id="sidebar">',$query[1]);



		$query=explode('<a href="',str_replace('>','"',$query[0]));
		$query[0]=null;
	
		foreach($query as $row){
			if($row){
				$url=explode('"',$row);
				$url=$url[0];
				if(!$r_query[$url])
				{
					$URL_Array[]=$url;
					$new++;
				} else {
					$old++;
				}
			}
		}
	
	print "found new ".$new.", old ".$old."\n";

	print "<table><tr><td><pre>";

	foreach($URL_Array as $row)
	{
		print "<a href=\"".$row."\" target=_blank>".$row."</a>\n";
	}

	print "</td></tr></table>";


?>