<?php
print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newfiles=Array();

$start=1;

for($x=$start;$x<$start+100;$x++){
	$query=implode ('', file ("http://karpez.ucoz.ru/load/0-".$x));
	$query=explode ('<div class="eTitle" style="text-align:left;"><a href="http://karpez.ucoz.ru/load/',$query);
	$query[0]=null;
	
	foreach($query as $row){
		if($row){
			$row=explode ('"', $row);
			$row=$row[0];

			print "found ".$row;

			if(($r_query[$row])||($newfiles[$row])){
				print " reject\n";
				$x=1000;
			}else{
				print " add\n";
				$newfiles[$row]=$row;
			}
		}
	}
}

$found = Array();

foreach($newfiles as $newfile){
	$id=explode ('-', $newfile);
	$id=$id[count($id)-1];
	$url=implode ('', file ("http://karpez.ucoz.ru/load/".$newfile));
 	$url=explode ('-'.$id.'-20"', $url);
 	$url=explode ('http://karpez.ucoz.ru/load/', $url[0]);
 	$url=$url[count($url)-1];
	if($url){
		$found[]=Array($newfile,$url.'-'.$id.'-20');
	}else{
		print "error for ".$newfile."\n";
	}
}

print "\n\n";

foreach($found as $row){
	print "<a href=\"http://karpez.ucoz.ru/load/".$row[1]."\">".$row[0]."</a>\n";
}

?>