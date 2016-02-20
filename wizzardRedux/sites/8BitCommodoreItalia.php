<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n",$r_query);
	$r_query=array_flip($r_query);


	$content=implode ('', file ("http://www.8bitcommodoreitalia.com/index.php?option=com_phocadownload&view=sections&Itemid=101"));
	$content=explode ('<div class="componentheading">Download generale</div>', $content);
	$content=explode ('<div id="phoca-dl-most-viewed-box">', $content[1]);
	$content=explode ('<h3>', $content[0]);
	$content[0]=null;
	foreach($content as $row){
		$URLs=Array();
		if($row){
			$new=0;
			$old=0;
        	$section=explode ('</a>',$row);
			$section=strip_tags($section[0]);
			print "found :".$section."\n";
        	$categories=explode ('<a href="/',$row);
			$categories[0]=null;
			$categories[1]=null;
			foreach($categories as $categorie){
				if($categorie){
					$categorie=explode ('"',$categorie);
					$categorie=$categorie[0];
					print "load :".$categorie."\n";

					$content2=implode ('', file ("http://www.8bitcommodoreitalia.com/".$categorie."&limit=0"));
					$content2=explode ('<div class="pd-float"><a href="', $content2);
					$content2[0]=null;
					foreach($content2 as $dl){
						if($dl){
							$url=explode ('"',$dl);
							$url=$url[0];
							$url=str_replace('&view=file&id=','&view=category&download=',html_entity_decode($url));

							$name=explode ('>',$dl);
							$name=explode ('<',$name[1]);
							$name=$name[0];

							if(!$r_query[$url])	{
								$URLs[]=Array($url,$name.".zip");
								$new++;
								$r_query[$url]=true;
							} else {
								$old++;
							}
						}
					}

					print "found new ".$new.", old ".$old."\n";
				}
			}
		}
		print "<table><tr><td><pre>";

		foreach($URLs as $row)
		{
			print "<a href=\"http://www.8bitcommodoreitalia.com/".$row[0]."\" target=_blank>".$row[1]."</a>\n";
		}
		
		print "</td><td><pre>";
		
		foreach($URLs as $row)
		{
			print $row[0]."\n";
		}
	
		print "</td></tr></table>";
		}

?>