<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

	$page="http://c64tapes.org/games_list.php?title=^.&sort=title";

	print "load ".$page."\n";

	$content=implode ('', file ($page));
	$content=utf8_decode($content);
	$content=explode ('<tr>',$content);
	$content[0]=null;
	$content[1]=null;

	$URLs=Array();

	$new=0;
	$old=0;

	foreach($content as $row){
		if($row){
			if(substr_count($row, 'TAP icon"'))
			{
				$row=explode ('<td',$row);
				$id=strip_tags('<td'.$row[1]);
	
				$row[2]=str_replace(array('[',']'),array('(',')'),$row[2]);
				$name=trim(strip_tags('<td'.$row[2]));
				$row[4]=str_replace(array('<a class="year" href="year.php?id=1">[unkn]</a>',
												   ' (',')' ,'</a>' ,'<a' ,'[' ,']'),
									array(null    ,', ',null,')</a>','(<a',null,null),$row[4]);
				$add=trim(strip_tags('<td'.$row[4]));

				$dl_page=implode ('', file ("http://c64tapes.org/title.php?id=".$id));
				$dl_page=utf8_decode($dl_page);
	
				$url1=explode ('<td>Filename (TAP): </td>',$dl_page);
				$url1=explode ('</td>',$url1[1]);
				$url1=trim(strip_tags($url1[0]));
	
				$url2=explode ('<td>Filename (RAW): </td>',$dl_page);
				$url2=explode ('</td>',$url2[1]);
				$url2=trim(strip_tags($url2[0]));

				print $id."\t".$name.' '.$add."\n";
	
				if($url1){
					$id='taps/'.$url1;
					if(!$r_query[$id])	{
						$URLs[]=Array($id,$name.' '.$add.".zip");
						$new++;
					} else {
						$old++;
					}
				}
	
				if($url2){
					$id='raw/'.$url2;
					if(!$r_query[$id])	{
						$URLs[]=Array($id,$name.' '.$add.".zip");
						$new++;
					} else {
						$old++;
					}
				}
			}
		}
	}

	print "found new ".$new.", old ".$old."\n";

	print "<table><tr><td><pre>";
	
	foreach($URLs as $row)
	{
		print $row[0]."\n";
	}
	
	print "</td><td><pre>";
	
	foreach($URLs as $row)
	{
		print "<a href=\"http://c64tapes.org/".$row[0]."\" target=_blank>".$row[1]."</a>\n";
	}
	
	print "</td></tr></table>";


?>