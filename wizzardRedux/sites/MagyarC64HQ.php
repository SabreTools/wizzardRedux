<?php
print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n",$r_query);
$r_query=array_flip($r_query);

$pages=implode ('', file ("http://c64hq.hu/menu.htm"));
$pages=explode ('<A HREF="games/',$pages);
$pages[0]=null;

$otherPages=Array();
$URLs=Array();

$totalold=0;
$totalnew=0;

function loadPage($page)
{
	GLOBAL $totalold,$totalnew,$otherPages, $URLs, $GLOBALS, $r_query;

	$sub=explode ('/',$page);

	$page="http://c64hq.hu/games/".$page;
	print "load ".$page."\n";
	$query=implode ('', file ($page));
	$query=str_replace(array("\n","\r","<br>","<BR>",'href="JavaScript','href="javascript','href="http',
	'</a>','<a href="'),array('','','','','','','',
	'</A>','<A HREF="'),$query);

	$page=explode ('/',$page);
	$page[count($page)-1]=null;
	$page=implode ('/',$page);

	$other_sides=explode ('<P><B><font color="#0000ff">1',$query);
	$other_sides=explode ('<P>',$other_sides[1]);
	$other_sides=explode ('<A HREF="',$other_sides[0]);
	$other_sides[0]=null;

	foreach ($other_sides as $side){
		if($side){
			$side=explode ('"',$side);
			$side=$side[0];
			$otherPages[$side]=$sub[0].'/'.$side;
		}
	}

	$content=explode ('<table width=600 border=0 cellspacing=0 cellpadding=0>',$query);
	$content[0]=null;

	$new=0;
	$old=0;

	foreach ($content as $row){
		if($row){
			$name=explode ('<font color="#000000"><b>',$row);
			$name=explode ('</b>',$name[1]);
			$name=explode ('<b>',$name[0]);
			$name=trim($name[0]);

			$info=explode ('align="right"><font color="#000000">',$row);
			$info=explode ('</font>',$info[1]);
			$info=str_replace(array('?',', '),array('x',') ('),trim($info[0]));
		
			$name=$name." (".$info.")";

			$links=explode ('</font></center></td></table>',$row);
			$links=explode ('<A HREF="',$links[0]);
			$links[0]=null;
			foreach ($links as $url){
				if($url){
					$furl=explode('"',$url);
					$furl=$page.$filecontent=html_entity_decode($furl[0]);
					$ext=explode('.',$furl);

					$add=explode('</A>',$url);
					$add=strip_tags('<A HREF="'.$add[0]);

					if(!$r_query[$furl])	{
						$URLs[]=Array($furl,strtr($name." (".$add.")", $GLOBALS['normalizeChars']).".".$ext[count($ext)-1]);
						$new++;
						$totalnew++;
					} else {
						$old++;
						$totalold++;
					}
                }
			}
		}
	}
	print "found new ".$new.", old ".$old."\n";
}

foreach ($pages as $page){
	if($page){
		$page=explode ('"',$page);
		$page=$page[0];
		loadPage($page);
	}
}

foreach ($otherPages as $page){
	loadPage($page);
}

	print "\ntotal found new ".$totalnew.", old ".$totalold."\n";
	print "<table><tr><td><pre>";
	
	foreach($URLs as $row)
	{
		print $row[0]."\n";
	}
	
	print "</td><td><pre>";
	
	foreach($URLs as $row)
	{
		print "<a href=\"".$row[0]."\" target=_blank>".$row[1]."</a>\n";
	}
	
	print "</td></tr></table>";


?>