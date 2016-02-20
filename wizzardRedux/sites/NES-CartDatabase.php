<?php

print "<pre>";

	if($_GET["start"]) 
	{
		$start=$_GET["start"];
		$fp = fopen($_GET["source"]."/start.txt", "w");
		fwrite($fp,	$start);
		fclose($fp);
	}
	else
	{
		$start=implode ('', file ($_GET["source"]."/start.txt"));
	}

	$r_query=implode ('', file ($_GET["source"]."/crc.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

	$newCRC=Array();

	for ($id=$start;$id<$start+50;$id++)
	{
		$query=implode ('', file ("http://bootgod.dyndns.org:7777/profile.php?id=".$id));
		$query=str_replace("&nbsp;"," ",$query);
		$query=html_entity_decode($query);
		$query=str_replace("\n","",$query);
		
		$title=explode ('<td nowrap class="headingmain" valign="bottom" width="100%">',$query);
		$title=explode ('</td>',$title[1]);
		$title=trim(strip_tags($title[0]));
		
		$info=Array();
		
		$Catalog=explode ('<th >Catalog ID</th>',$query);
		$Catalog=explode ('</td>',$Catalog[1]);
		$Catalog=trim(strip_tags($Catalog[0]));
		$Catalog=str_replace(array("(",")"),"",$Catalog);
		if($Catalog) $info[]=$Catalog;
		
		$region=explode ('<th >Region</th>',$query);
		$region=explode ('</td>',$region[1]);
		$region=trim(strip_tags($region[0]));
		$region=str_replace(array("(",")"),"",$region);
		if($region) $info[]=$region;
		
		$Date=explode ('<th >Release Date</th>',$query);
		$Date=explode ('</td>',$Date[1]);
		$Date=trim(strip_tags($Date[0]));
		$Date=str_replace(array("(",")",","),"",$Date);
		if($Date) $info[]=$Date;
		
		$Publisher=explode ('<th >Publisher</th>',$query);
		$Publisher=explode ('</td>',$Publisher[1]);
		$Publisher=trim(strip_tags($Publisher[0]));
		$Publisher=str_replace(array("(",")"),"",$Publisher);
		if($Publisher) $info[]=$Publisher;
		
		$Developer=explode ('<th >Developer</th>',$query);
		$Developer=explode ('</td>',$Developer[1]);
		$Developer=trim(strip_tags($Developer[0]));
		$Developer=str_replace(array("(",")"),"",$Developer);
		if(($Developer)&&($Developer!=$Publisher)) $info[]=$Developer;
		
		$title=$title." (".implode(", ",$info).")";
		
		$ROMDetails=explode ('<td colspan="4" class="headingmain">ROM Details</td>',$query);
		$ROMDetails=explode ('<table >',$ROMDetails[1]);
		$ROMDetails=explode ('<tr class="textmain">',$ROMDetails[0]);
		
		for($x=1;$x<count($ROMDetails);$x++){
			$ROMDetail=explode ("<td",$ROMDetails[$x]);
			$ROMDetailType=substr(trim(strip_tags("<td".$ROMDetail[1])),0,3);
			if($ROMDetailType=="ROM"){
				$ROMDetailType="NES";
				$diff=1;
				$ROMDetailTitle="";
			}else{
				$diff=0;
				$ROMDetailTitle=trim(strip_tags("<td".$ROMDetail[2]));
				if($ROMDetailTitle=="Not Present"){
					$ROMDetailTitle="";
				} else {
					$ROMDetailTitle=" (".$ROMDetailTitle.")";
				}
			}
		
			$ROMDetailCRC=trim(strip_tags("<td".$ROMDetail[4-$diff]));

			if(!$r_query[$ROMDetailCRC]){
				$ROMDetailSize=explode(" ",trim(strip_tags("<td".$ROMDetail[3-$diff])));
				$ROMDetailSize=$ROMDetailSize[0]*1024;

				$ROMDetailSHA=explode('SHA-1:',$ROMDetail[4-$diff]);
				$ROMDetailSHA=explode('"',$ROMDetailSHA[1]);
				$ROMDetailSHA=$ROMDetailSHA[0];

				print"==================================================\n".
				"Filename          : ".$title.$ROMDetailTitle.".".$ROMDetailType."\n".
				"File Size         : ".$ROMDetailSize."\n".
				"CRC32             : ".$ROMDetailCRC."\n".
				"SHA1              : ".$ROMDetailSHA."\n".
				"MD5               : 0\n".
				"Extension         : ".$ROMDetailType."\n";

				$r_query[$ROMDetailCRC]=true;
				$newCRC[]=$ROMDetailCRC;
			}

			$last=$id;
		}
	}

	if($last) $start=$last+1;

	print "\nnext startnr\t<a href=?action=onlinecheck&source=NES-CartDatabase&start=".($start).">".$start."</a>\nchecked until\t".$id."\n";

	print implode("\n",$newCRC);

?>