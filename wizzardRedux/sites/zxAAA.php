<?php

print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();
$dirs=Array();

$max=100000;

for($pagetype=0;$pagetype<2;$pagetype++){
	for($page=1;$page<$max;$page++){
		$dir = "http://zxaaa.untergrund.net/view_demos.php?t=".$pagetype."&np=".$page;
		print "load: ".$dir."\n";
		$query=implode ('', file ($dir));
		$query=explode('  <tr',$query);
		array_splice ($query,0,1);
	
		$new=0;
		$old=0;
	
		$notFound=true;
	
		foreach($query as $row){
			$row=explode('<td',$row);
			$title=trim(strip_tags('<td'.$row[1]));
	
		$info=Array();
	
			for($x=4;$x<9;$x++)
			{
				$temp=trim(strip_tags('<td'.$row[$x]));
				if($temp)$info[]=$temp;
			}
	
			if($info)$title=$title." (".implode(") (",$info).")";
	
			$DLs=explode('get.php?f=',$row[1]);
			array_splice ($DLs,0,1);
	
			foreach($DLs as $DL){
				$DL=explode('"',$DL);
				$DL=$DL[0];
	
				$ext=explode('.',$DL);
				$ext=$ext[count($ext)-1];
	
				if(!$r_query[$DL])
				{
					$newURLs[]=Array($title.".".$ext,$DL);
					$new++;
					$r_query[$DL]=true;
				}
				else
				{
					$old++;
				}
	
				$notFound=false;
	        }
		}
		
		if($notFound) $page=$max;

		print "new: ".$new.", old: ".$old."\n";
	}
}

$dirs=Array(
	'http://zxaaa.untergrund.net/DEMA.html',
	'http://zxaaa.untergrund.net/INTRA.html',
);

$dirsb=Array(
	Array('intbis1998.html','BIS From Rush'),
	Array('intbis1999.html','BIS From Rush'),
	Array('intbis2000.html','BIS From Rush'),
	Array('intbis2001.html','BIS From Rush'),
	Array('intbis2002.html','BIS From Rush'),
	Array('intbis2003.html','BIS From Rush'),
	Array('intbis2004.html','BIS From Rush'),
	Array('intbis2005.html','BIS From Rush'),
	Array('intbis2006.html','BIS From Rush'),
	Array('intbis2007.html','BIS From Rush'),
	Array('sgteam2007.html','SG-Team'),
	Array('sgteam2008.html','SG-Team'),
	Array('sgteam2009.html','SG-Team'),
	Array('sgteam2010.html','SG-Team'),
	Array('sgteam2011.html','SG-Team'),
	Array('sgteam2012.html','SG-Team'),
	Array('STARYO.html','Diski Staryo',true),
	Array('DISKAAA.html','Disk Colection\'s AAA',true),
	Array('DISKAAA2.html','Disk Colection\'s AAA',true),
	Array('DISKAAA3.html','Disk Colection\'s AAA',true),
	Array('DISKAAA4.html','Disk Colection\'s AAA',true),
	Array('DISALON.html','Wlodek',true),
	Array('IRONDISK.html','Iron',true),
	Array('ZXCHIP.html','ZX CHIP',true),
	Array('ZXCHIP2.html','ZX CHIP',true),
	Array('FICUS.html','Ficus Demo Collections',true),
	Array('FICUS2.html','Ficus Demo Collections',true),
	Array('FICUSG.html','Flash Inc Games Collections',true),
	Array('FICUSG2.html','Flash Inc Games Collections',true),
	Array('flashrelize.html','Flash Inc Relize',true),
	Array('MAGICSOFTSYS.html','',true),
);

foreach($dirs as $dir){
	print "load: ".$dir."\n";

	$query=implode ('', file ($dir));
	$query=explode('<td><a href="',$query);
	array_splice ($query,0,1);

	foreach($query as $row){
		$row=explode('"',$row);
		if($row[6])$dirsb[]=Array($row[0],$row[6]);
	}
}

foreach($dirsb as $dir){
	$cdir = "http://zxaaa.untergrund.net/".$dir[0];
	print "load: ".$cdir."\n";

		$new=0;
		$old=0;

	$query=implode ('', file ($cdir));
	$query=str_replace("alt='",'alt="',$query);
	$query=str_replace("'>",'">',$query);
	$query=explode('<a href="',$query);
	array_splice ($query,0,1);

	foreach($query as $row){
		$DL=explode('"',$row);
		$DL=$DL[0];

		$title=explode('alt="',$row);
		$title=explode('"',$title[1]);
		$title=$title[0];

		if($title){
			$ext=explode('.',$DL);
			$ext=$ext[count($ext)-1];

			if($dir[2]){
				$titel=$dir[1]." ".$title.".".$ext;
			}else{
				$titel=$title." (".$dir[1].").".$ext;
			}
	
			if(!$r_query[$DL])
			{
				$newURLs[]=Array($titel,$DL);
				$new++;
				$r_query[$DL]=true;
			}
			else
			{
				$old++;
			}
		}
	}

	print "new: ".$new.", old: ".$old."\n";
}

$dirsc=array();

$dir="http://zxaaa.untergrund.net/GAME.html";
print "load: ".$dir."\n";
$query=implode ('', file ($dir));
$query=explode("<a href=\"javascript:jumpto('",$query);
array_splice ($query,0,1);

foreach($query as $row){
	$row=explode("'",$row);
	$dirsc[]=Array($row[0],'ZX Chip Volgodonsk Games Collections');
}

foreach($dirsc as $dir){
	$cdir = "http://zxaaa.untergrund.net/".$dir[0];
	print "load: ".$cdir."\n";

		$new=0;
		$old=0;

	$query=implode ('', file ($cdir));
	$query=explode('<a href="',$query);
	array_splice ($query,0,1);

	foreach($query as $row){
		$DL=explode('"',$row);
		$DL=$DL[0];

		$title=explode('>[',$row);
		$title=explode(']<',$title[1]);
		$title=$title[0];

		if($title){
			$ext=explode('.',$DL);
			$ext=$ext[count($ext)-1];

			$titel=$dir[1]." ".$title.".".$ext;

			if(!$r_query[$DL])
			{
				$newURLs[]=Array($titel,$DL);
				$new++;
				$r_query[$DL]=true;
			}
			else
			{
				$old++;
			}
  		}
	}

	print "new: ".$new.", old: ".$old."\n";
}


print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach($newURLs as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach($newURLs as $url)
{
	print "<a href=\"http://zxaaa.untergrund.net/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>