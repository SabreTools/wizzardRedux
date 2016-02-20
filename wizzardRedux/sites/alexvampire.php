<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

$newfiles=Array(
	'https://alexvampire.wordpress.com/feed/',
);

	$found = Array();

foreach($newfiles as $newfile){
	print "load ".$newfile."\n";
	$query=implode ('', file($newfile));
 	$query=explode ('<link>',$query);
	$query[0]=null;

	$old=0;
	$new=0;

	foreach($query as $row){
		if($row){
			$dl=explode ('</link>', $row);
			$dl=$dl[0];

	    	if($r_query[$dl])
			{
				$old++;
			}else{
				$found[]=$dl;
				$new++;
			}
		}
	}

	foreach($found as $row){
		print "<a href=\"".$row."\">".$row."</a>\n";
	}

	print "found new:".$new.", old:".$old."\n\n";
}


?>