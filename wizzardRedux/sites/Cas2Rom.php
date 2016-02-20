<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n",$r_query);
	$r_query=array_flip($r_query);

$page="http://msxcas2rom.zxq.net/";

$URLs=Array();

	print "load ".$page."\n";

	$old=0;
	$new=0;

	$content=implode ('', file ($page));
	$content=explode ('<a href="',$content);
	$content[0]=null;

	foreach($content as $row){
		if($row){
			$url=explode ('"',$row);
			$url=$url[0];
			$ext=explode('.',$url);

			$title=explode('</a>',$row);
			$title=trim(strip_tags('<a href="'.$title[0].'</a>'));

				if(!$r_query[$url])	{
					$URLs[]=array($url,$title.".".$ext[count($ext)-1]);
					$new++;
				} else {
					$old++;
				}
		}
	}

	print "new ".$new.", old ".$old."\n";

	print "<table><tr><td><pre>";

foreach($URLs as $row)
{
	print $row[0]."\n";
}

	print "</td><td><pre>";

foreach($URLs as $row)
{
	print "<a href=\"".$page.$row[0]."\" target=_blank>".$row[1]."</a>\n";
}

	print "</td></tr></table>";
?>