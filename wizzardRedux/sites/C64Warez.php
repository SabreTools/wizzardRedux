<?php

print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

$query=implode ('', file ('http://c64warez.com/'));
$query=explode('<a href="http://c64warez.com/files/',$query);
array_splice ($query,0,1);

foreach($query as $row){
	$row=explode('"',$row);
	$row=$row[0];

	$parts=explode('/',$row);

	if($parts[0]!='get_file'){
		print "load ".$row."\n";

		$queryb=implode ('', file ('http://c64warez.com/files/'.str_replace(' ','%20',$row)));
		$queryb=explode('<a href="http://c64warez.com/files/get_file/',$queryb);
		array_splice ($queryb,0,1);

		$new=0;
		$old=0;

		foreach($queryb as $rowb){
			$type=explode('<',$rowb);
			$type=explode('>',$type[2]);
			$type=$type[1];
			$rowb=explode('"',$rowb);
			$id=$rowb[0];
			$titel="{".$type."}".$rowb[2]." (".$parts[1].")";

			if(!$r_query[$id])
			{
				$newURLs[]=Array($titel,$id);
				$new++;
				$r_query[$id]=true;
			}
			else
			{
				$old++;
			}
		}

		print "new: ".$new.", old: ".$old."\n";
	}
}

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach($newURLs as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach($newURLs as $url)
{
	print "<a href=\"http://c64warez.com/files/get_file/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>