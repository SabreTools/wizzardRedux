<?php
	$r_query=implode ('', file ($_GET["source"]."/found.txt"));
	$r_query=explode ("\r\n",$r_query);
	$r_query=array_flip($r_query);

	print "<pre>";

	$found=array();

	$add=Array(
		1 	=> 	Array(" (AtariArea)"),
		2	=>	Array(" (Atarex)"),
		3	=>	Array(" (not protected, working) (POLISH)"," (protected, not working) (POLISH)"," (not protected, working)"),
		4	=>	Array(" (fixed loader)"),
		5	=>	Array(" (nonstandard, fixed loader)"," (protected, not working)"," (nonstandard)","(Oskar)"," (works with cas2sio, emu)"," (Sonix) (works with ape, cas2sio)"),
		6	=>	Array(""),
		7	=>	Array(" (Sikor Soft)"),
		8	=>	Array(" (works with cas2sio) (Oskar)"," (works with emu) (Oskar)"," (Haga Software) (Oskar)"),
	);

	for ($x=1;$x<9;$x++)
	{
		print "load http://cas-archive.pigwa.net/cas".$x.".htm\n";
		$query=implode ('', file ("http://cas-archive.pigwa.net/cas".$x.".htm"));
		$query=explode ('<a href="ftp://ftp.pigwa.net/stuff/collections/stryker/cas/', $query);
		$query[0]=null;
		$addnr=0;
		$count=0;
		$new=0;
	
		foreach($query as $row){
			if($row){
				$url=explode ('"',$row);
				$url=$url[0];

				if(!$r_query[$url])
				{
					$ext=explode ('.',$url);
					$ext=$ext[count($ext)-1];
	
					$name=explode ('">',$row);
					$name=explode ('<br>',$name[1]);
					$name=strip_tags($name[0]);
					$name=trim(strtr($name, $GLOBALS['normalizeChars']));
	
					print "<a href=\"ftp://ftp.pigwa.net/stuff/collections/stryker/cas/".$url."\" >".$name.$add[$x][$addnr].".".$ext."</a>\n";
					$found[]=$url;
					$new++;
				}
				
				$count++;
				if(strstr($row,'<u>'))$addnr++;
			}
		}

		print "found: ".$count.", new: ".$new."\n";
	}

	print "\nurls:\n\n";

	foreach ($found as $row){
		print $row."\n";
	}

?>