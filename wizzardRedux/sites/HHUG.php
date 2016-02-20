<?php

print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

	$URL_Array=array();

	for($x=1;$x<=10;$x++)
	{
		$new=0;
		$old=0;
		
		$url= "http://hhug.me/?tags=dumps&page=".$x;

		print "load: ".$url."\n";

		$query=implode ('', file ($url));
		$query=explode("<a href=\"uploads/dumps/",str_replace("\r\n",'',$query));
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
	
		if(!$new&&!$old) break;
	}

	foreach($URL_Array as $row)
	{
		print "<a href=\"http://hhug.me/uploads/dumps/".$row."\" target=_blank>".$row."</a>\n";
	}

?>