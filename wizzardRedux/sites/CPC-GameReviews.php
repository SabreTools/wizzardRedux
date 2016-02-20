<?php

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$mainURL="ftp://www.cantrell.org.uk/ftp.nvg.ntnu.no/pub/cpc/";

$newURLs=Array();
$Header=Array();
$new=0;
$old=0;

print "<pre>load ".$mainURL."00_table.csv\n";

if (($handle = fopen($mainURL."00_table.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		if($Header)
		{
			if(!$r_query[$data[$Header["File Path"]]]){
				$ext=explode('.',$data[$Header["File Path"]]);
				$ext=$ext[count($ext)-1];
		
				$info=Array();
	
				foreach(Array(
					'ORIGINAL TITLE','ALSO KNOWN AS',
					'COMPANY','PUBLISHER','RE-RELEASED BY',
					'YEAR','LANGUAGE','MEMORY REQUIRED',
					'PUBLICATION','PUBLISHER CODE','BARCODE','DL CODE',
					'CRACKER','DEVELOPER','AUTHOR','DESIGNER','ARTIST','MUSICIAN'
				) as $key)
				{
					if($data[$Header[$key]])	$info[]=$data[$Header[$key]];
				}
	
				if($info){
					$title=$data[$Header[TITLE]].' ('.implode(') (',$info).').'.$ext;
				}else{
					$title=$data[$Header[TITLE]].'.'.$ext;
		        }
		
		 		if(!$data[$Header[TITLE]]) $title=$data[$Header["File Path"]];
	
				$newURLs[]=Array($data[$Header["File Path"]],$title);
				$new++;
			}else{
				$old++;
			}
		}
		else
		{
			$Header=$data;
			$Header=array_flip($Header);
        }

    }
    fclose($handle);
}



print "\nfound new: ".$new.", old ".$old.", urls:\n\n";

	print "<table><tr><td><pre>";

	foreach($newURLs as $row)
	{
		print $row[0]."\n";
	}

	print "</td><td><pre>";

	foreach($newURLs as $row)
	{
		print "<a href=\"".$mainURL.$row[0]."\" target=_blank>".$row[1]."</a>\n";
	}

	print "</td></tr></table>";
?>