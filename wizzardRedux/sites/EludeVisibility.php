<?php

print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n",$r_query);
	$r_query=array_flip($r_query);

	$URL_Array=array();

	$new=0;
	$old=0;
	
	$url= "http://eludevisibility.org/archive/";

	print "load: ".$url."\n";

	$query=implode ('', file ($url));
	$query=explode(": <a href=\"",$query);
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
?>