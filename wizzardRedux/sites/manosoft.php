<?php
print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$page="http://www.manosoft.it/?page_id=1050";

$URLs=Array();

print "load ".$page."\n";

$old=0;
$new=0;

$content=implode ('', file ($page));
$content=explode ('<a href="',$content);
array_splice ($content,0,1);

foreach($content as $row){
	$url=explode ('"',$row);
	$url=$url[0];

	if(!$r_query[$url])	{
		$URLs[]=$url;
		$new++;
	} else {
		$old++;
	}
}

print "new ".$new.", old ".$old."\n";

print "<table><tr><td><pre>";

foreach($URLs as $row)
{
	print "<a href=\"".$row."\" target=_blank>".$row."</a>\n";
}

print "</td></tr></table>";
?>