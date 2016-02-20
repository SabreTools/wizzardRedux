<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

$newfiles=Array(
	'http://www.elysium.filety.pl/All_files.txt',
	'http://www.elysium.filety.pl/GamesArchive.txt',
);

$found = Array();

foreach($newfiles as $newfile){
	$new=0;
	$old=0;	

	print "load ".$newfile."\n";
	$query=implode ('', file ($newfile));
 	$query=explode ("\r\n", str_replace('-->','   ',$query));

	foreach($query as $row){
		$row=explode ("\t: ", $row);
		$row=$row[1];
		if($row){
	    	if($r_query[$row]){
				$old++;
			}else{
				$found[]=$row;
				$new++;
			}
		}
	}
	print "found new:".$new.", old:".$old."\n";
}
	print "<table><tr><td><pre>";

	foreach($found as $row){
		print $row."\n";
	}

	print "</td><td><pre>";

	foreach($found as $row){
		$row=explode ("] ./", $row);
		$row=$row[1];
		$row2 = explode("/",$row);
		print "<a href=\"http://www.elysium.filety.pl/".$row."\">".$row2[count($row2)-1]."</a>\n";
	}

	print "</td><td><pre>";

	foreach($found as $row){
		$row=explode ("] ./", $row);
		$row=$row[1];
		$row2 = explode("/",$row);
		$title = explode(".",$row2[count($row2)-1]);
		$ext = $title[count($title)-1];
		$title[count($title)-1]=null;
		$title = implode(".",$title);

		print "<a href=\"http://www.elysium.filety.pl/".$row."\">".$row2[count($row2)-2]." (".substr($title,0,-1).").".$ext."</a>\n";
	}

	print "</td></tr></table>";
?>