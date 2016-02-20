<?php

$dirs=Array(
	'http://cah4e3.shedevr.org.ru/dumping_2016.php',
	'http://cah4e3.shedevr.org.ru/dumping_2015.php',
	'http://cah4e3.shedevr.org.ru/dumping_2014.php',
	'http://cah4e3.shedevr.org.ru/dumping_2013.php',
	'http://cah4e3.shedevr.org.ru/dumping_2012.php',
	'http://cah4e3.shedevr.org.ru/dumping_2011.php',
	'http://cah4e3.shedevr.org.ru/dumping_2010.php',
	'http://cah4e3.shedevr.org.ru/dumping_2009.php',
	'http://cah4e3.shedevr.org.ru/dumping_2008.php',
	'http://cah4e3.shedevr.org.ru/dumping_2007.php',
	'http://cah4e3.shedevr.org.ru/dumping_2006.php',
	'http://cah4e3.shedevr.org.ru/dumping_2005.php',
	'http://cah4e3.shedevr.org.ru/dumping_2004.php',
	'http://cah4e3.shedevr.org.ru/dumping_2003.php',
	'http://cah4e3.shedevr.org.ru/dumping_other.php',
	'http://cah4e3.shedevr.org.ru/dumping_sachen.php',
	'http://cah4e3.shedevr.org.ru/decr.php',
);

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

function listDir($dir){
	GLOBAL $newURLs, $r_query;

	print "load: ".$dir."\n";
	$query=implode ('', file ($dir));
	$query=explode(' href="',$query);
	$query[0]=null;

	$new=0;
	$old=0;
	$other=0;

	foreach($query as $row){
		if($row){
			$url=explode('"',$row);
			$url=$url[0];

			$ext=explode('.',$url);

			if($ext[count($ext)-1]=='rar')
			{
				if(!$r_query[$url])
				{
					$newURLs[]=$url;
					$new++;
				}
				else
				{
					$old++;
				}
			}
			else
			{
				$other++;
            }
		}
	}

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old.", other:".$other."\n";
}

print "<pre>check folders:\n\n";

foreach($dirs as $dir)
{
	if($dir)listDir($dir);
}

print "\nnew urls:\n\n";

foreach($newURLs as $url)
{
	print "<a href=\"http://cah4e3.shedevr.org.ru/".$url."\">".$url."</a>\n";
}

?>