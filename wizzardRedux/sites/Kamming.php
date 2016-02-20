<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

$newfiles=Array(
	'http://www.vdisk.cn/kamming07',
);

	$found = Array();

foreach($newfiles as $newfile){
	print "load ".$newfile."\n";
	$query=implode ('', file ($newfile));
 	$query=explode ("<div class='filename'><a href='/down/index/", $query);
	$query[0]=null;

	$old=0;
	$new=0;

	foreach($query as $row){
		if($row){
			$row=explode ("'", $row);
			$row=$row[0];

	    	if($r_query[$row])
			{
				$old++;
			}else{
				$found[]=$row;
				$new++;
			}
		}
	}

	foreach($found as $row){
		print "<a href=http://www.vdisk.cn/down/index/".$row.">".$row."</a>\n";
	}

	print "found new:".$new.", old:".$old."\n\n";
}


?>