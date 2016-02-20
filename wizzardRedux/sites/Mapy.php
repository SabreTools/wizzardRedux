<?php
	print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n",$r_query);
	$r_query=array_flip($r_query);

	$query=implode ('', file ("http://mapy.atari8.info/"));
 	$query=explode ('<td height="216" background="gfx/menumenu.gif" valign="top">', $query);
 	$query=explode ("</td>", $query[1]);
	$query=explode ("href=\"",$query[0]);
	$query[0]=null;

	$found=0;
	$new=0;

	foreach ($query as $row)
	{
		if($row)
		{
			$url=explode ("\"",$row);
			$url=$url[0];
			
			if(!$r_query[$url]){
				$queryb=implode ('', file ("http://mapy.atari8.info/".$url));

 				$name=explode ("<strong>:: ", $queryb);
 				$name=explode ("</strong>", $name[1]);
 				$name=$name[0];
 				
				$author=explode ('<a class="autor"', $queryb);
 				$author=explode (">", $author[1]);
 				$author=explode ("<", $author[1]);
 				$author=trim($author[0]);

				$dl=explode ('href="stuff/', $queryb);
				$dl=explode ("\"",$dl[1]);
 				$dl=$dl[0];

				$URLs[]=Array($url,strtr($name." (".$author.")", $GLOBALS['normalizeChars']),$dl);
				$new++;
			}

			$found++;
		}
    }

	print "\nfound ".$found.", new ".$new."\n";

	print "<table><tr><td><pre>";

	foreach($URLs as $row)
	{
		print "<a href=\"http://mapy.atari8.info/".$row[0]."\" target=_blank>".$row[0]."</a>\n";
	}

	print "</td><td><pre>";

	foreach($URLs as $row)
	{
		print "<a href=\"http://mapy.atari8.info/stuff/".$row[2]."\"  target=_blank>".$row[1].".zip</a>\n";
	}

	print "</td></tr></table>";


?>