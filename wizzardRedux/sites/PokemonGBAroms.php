<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

$newfiles=Array(
	'http://pokemon-gba.wen.ru/original-version.html',
	'http://pokemon-gba.wen.ru/hacked-version.html',
);

	$found = Array();

foreach($newfiles as $newfile){
	print "load ".$newfile."\n";
	$query=implode ('', file ($newfile));
 	$query=explode ('<a href="/gba/',$query);
	$query[0]=null;

	$old=0;
	$new=0;

	foreach($query as $row){
		if($row){
			$dl=explode ('"', $row);
			$dl=$dl[0];
			$title=explode ('</a', $row);
			$title=explode ('>', $title[0]);
			$title=trim($title[count($title)-1]);

	    	if($r_query[$title])
			{
				$old++;
			}else{
				$found[]=ARRAY($dl,$title);
				$new++;
			}
		}
	}

	foreach($found as $row){
		print "<a href=\"http://pokemon-gba.wen.ru/gba/".$row[0]."\">".$row[1]."</a>\n";
	}

	print "found new:".$new.", old:".$old."\n\n";
}


?>