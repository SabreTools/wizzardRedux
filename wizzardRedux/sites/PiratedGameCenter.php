<?php

	print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n",$r_query);
	$r_query=array_flip($r_query);

	$URL_Array=array();

	$new=0;
	$old=0;

	for($x=1;$x<6;$x++){
		$query=implode ('', file ("http://blog.naver.com/PostList.nhn?from=postList&blogId=kevinhwsohn&currentPage=".$x));
		$query=explode("encodedAttachFileUrl': '",$query);
		$query[0]=null;
		foreach($query as $row){
			if($row){
				$url=explode("'",$row);
				$url=$url[0];
				$title=explode("/",$url);
				$title=$title[count($title)-1];
				if(!$r_query[$title])
				{
					$URL_Array[]=Array($url,$title);
					$r_query[$title]=true;
					$new++;
				} else {
					$old++;
				}
			}
		}
	}

	print "found new ".$new.", old ".$old."\n"; 

	foreach($URL_Array as $row)
	{
		print "<a href=".$row[0]." target=_blank>".$row[1]."</a>\n";
	}

?>