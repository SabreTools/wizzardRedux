<?php
	$r_query=implode ('', file ($_GET["source"]."/urls.txt"));
	$r_query=explode ("\r\n", htmlentities($r_query));
	
	foreach($r_query as $row)
	{
		$row=explode("\t",$row);
		$r_query[$row[1]]=true;
		if($row[0])$start=$row[0];
	}

	print "<pre>Search for new uploads, start by ".$start."\n\n";

	$new=0;
	$old=0;

	for ($x=$start;$x<$start+50;$x++)
	{
		$query=trim(implode ('', file ("http://www.velus.be/cpc-".$x.".html")));

		$info=Array();

		$temp=explode ('>Nom : ', $query);
		$temp=explode ('<', $temp[1]);
		$info[]=$temp[0];

		$temp=explode ('>Copyright  : ', $query);
		$temp=explode ('<', $temp[1]);
		if($temp[0]) $info[]="(".$temp[0].")";

		$temp=explode ('>Date de création : ', $query);
		$temp=explode ('<', $temp[1]);
		if($temp[0]) $info[]="(".$temp[0].")";

		$temp=explode ('href="download/', $query);
		$temp=explode ('"', $temp[1]);
		$dl=$temp[0];

		$gametitle=implode(" ",$info);

		if(!$r_query[$dl])
		{
			print $x."\t".$dl."\t".$gametitle."\n";
			$r_query[$dl]=true;
			$new++;
		}else{
			$old++;
        }
	}

	print "\nnew: ".$new.",old: ".$old."\n<a href=CrackersVelus/xml.php>xml</a>";

?>