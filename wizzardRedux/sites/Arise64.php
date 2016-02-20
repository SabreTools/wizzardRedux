<?php

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n",$r_query);
$r_query=array_flip($r_query);

$baseURL="http://arise64.pl/demos/";

$dirs1=Array(
      '_ntsc/',
      '0/',
      'a/',
      'b/',
      'c/',
      'd/',
      'e/',
      'f/',
      'g/',
      'h/',
      'i/',
      'j/',
      'k/',
      'l/',
      'm/',
      'n/',
      'o/',
      'p/',
      'q/',
      'r/',
      's/',
      't/',
      'u/',
      'v/',
      'w/',
      'x/',
      'y/',
      'z/',
);

$dirs2=Array(
      '_magazines/',
);

$dirs3=Array(
      '_unknown/',
);

$dirs4=Array(
      '_parties/',
);

$newURLs=Array();

function listDir($dir,$mode){
	GLOBAL $newURLs, $r_query, $baseURL;

	print "load: ".$dir."\n";
	$query=implode ('', file ($baseURL.$dir));
	$query=explode('Parent Directory</a>',$query);
	$query=explode('<a href="',$query[1]);
	$query[0]=null;

	$new=0;
	$old=0;
	$folder=0;

	foreach($query as $row){
		if($row){
			$url=explode('"',$row);
			$url=$url[0];

			if(substr($url,-1)=='/')
			{
				listDir(str_replace('&amp;','&',$dir.$url),$mode);
				$folder++;
			}
			else
			{
				if(!$r_query[str_replace('&amp;','&',$dir.$url)])
				{
					if($mode==1)
					{
						$author=explode('/',$dir);
						$author=$author[count($author)-2];
						$text=$author." (".substr($url,7,-4).") (".substr($url,0,4).")".substr($url,-4);
					}

					if($mode==2)
					{
						$author=explode('/',$dir);
						$language=$author[1];
						$author=$author[count($author)-2];
						$infos=explode('%20'.$author.'%20',$url);
						$text=$author." (".substr($infos[1],0,-4).") (".$infos[0].") (".$language.")".substr($url,-4);
					}

					if($mode==3)
					{
						$text="Unknown (".substr($url,7,-4).") (".substr($url,0,4).")".substr($url,-4);
					}

					if($mode==4)
					{
						$infos=explode('%20by%20',$url);
						$text=substr($infos[1],0,-4)." (".substr($infos[0],7).")";

						$info=explode('/',$dir);
						
						for($x=1;$x<count($info)-1;$x++)
						{
							$text=$text." (".str_replace('%20-%20',') (',$info[$x]).")";
                        }

						$text=$text.substr($url,-4);
					}

					$newURLs[]=array($dir.$url,urldecode ($text));
					$new++;
				}
				else
				{
					$old++;
				}
			}
		}
	}

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old.", folder:".$folder."\n";
}

print "<pre>check folders:\n\n";

foreach($dirs1 as $dir)
{
	if($dir)listDir($dir,1);
}

foreach($dirs2 as $dir)
{
	if($dir)listDir($dir,2);
}

foreach($dirs3 as $dir)
{
	if($dir)listDir($dir,3);
}

foreach($dirs4 as $dir)
{
	if($dir)listDir($dir,4);
}

print "\nnew urls:\n\n";

	print "<table><tr><td><pre>";

	foreach($newURLs as $row)
	{
		print "<a href=\"".$baseURL.$row[0]."\" target=_blank>".$row[1]."</a>\n";
	}

	print "</td><td><pre>";

	foreach($newURLs as $row)
	{
		print $row[0]."\n";
	}

	print "</td></tr></table>";


?>