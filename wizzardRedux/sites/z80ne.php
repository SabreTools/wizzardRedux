<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

$newfiles=Array(
	'http://www.z80ne.com/m20/index.php?argument=sections/download/wrm20/wrm20.inc',
);

	$found = Array();

foreach($newfiles as $newfile){
	print "load ".$newfile."\n";
	$query=implode ('', file ($newfile));
 	$query=explode ('<A href="', str_replace('&amp;','&',$query));
	$query[0]=null;

	$old=0;
	$new=0;

	foreach($query as $row){
		if($row){
			$row=explode ('"', $row);
			$row=trim($row[0]);


	    	if($r_query[$row])
			{
				$old++;
			}else{
				$found[]=$row;
				$new++;
			}
		}
	}


	print "found new:".$new.", old:".$old."\n\n";
}

	foreach($found as $row){
		print "<a href=\"http://www.z80ne.com/m20/".$row."\">".$row."</a>\n";
	}

?>