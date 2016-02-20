<?php

$dirs=Array(
	'http://amstrad.es/juegosamstrad/todos-los-juegos/index.html',
	'http://amstrad.es/juegosamstrad/demos/index.html',
	'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index.html',
	'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_1.html',
	'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_2.html',
	'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_3.html',
	'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_4.html',
	//'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_5.html',
	//'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_6.html',
	//'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_7.html',
	//'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_8.html',
	'http://amstrad.es/programas/amsdos/utilidades/index.html',
	'http://amstrad.es/programas/amsdos/educativos/index.html',
	'http://gx4000.amstrad.es/juegos/index.html',
	'http://gx4000.amstrad.es/juegos/index_1.html',
	'http://gx4000.amstrad.es/juegos/index_2.html',
	'http://gx4000.amstrad.es/juegos/index_3.html',
	'http://gx4000.amstrad.es/juegos/index_4.html',
	'http://gx4000.amstrad.es/juegos/index_5.html',
	'http://gx4000.amstrad.es/juegos/index_6.html',
	'http://gx4000.amstrad.es/juegos/index_7.html',
	'http://gx4000.amstrad.es/juegos/index_8.html',
	'http://amstrad.es/publicaciones/publicaciones/cintas/index.html',
);

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

function listDir($dir){
	GLOBAL $newURLs, $r_query;

	print "load: ".$dir."\n";
	$query=str_replace("\r\n",'',implode ('', file ($dir)));
	$query=explode('<p>  <a href="',$query);

	if(!$query[1])	{
		$query=explode('<a class="teaserlink" href="',$query[0]);
	}

	$query[0]=null;

	$t_dir=explode ("/",$dir);
	$t_dir[count($t_dir)-1]=null;
	$t_dir=implode ("/",$t_dir);

	foreach($query as $row){
		if($row){
			$new=0;
			$old=0;

			$url=explode('"',$row);
			$url=$t_dir.$url[0];

			Print "found: ".$url."\n";

			$text=explode('</a>',$row);
			$ext=explode('<',$text[1]);
			$text=explode('>',$text[0]);
			$text=trim($text[1]);
			$ext=trim($ext[0]);
			$text=strtr($text.' ('.str_replace(', ',') (',$ext).')', $GLOBALS['normalizeChars']);
			$query2=implode ('', file ($url));
			$query2=explode('<a href="',$query2);
			$query2[0]=null;

			$dl_dir=explode ("/",$url);
			$dl_dir[count($dl_dir)-1]=null;
			$dl_dir=implode ("/",$dl_dir);

			foreach($query2 as $dl){
				if($dl){
					$url2=explode('"',$dl);
					$url2=$dl_dir.$url2[0];
					
					$ext=explode(".",$url2);
					$ext=$ext[count($ext)-1];

					$dltext=explode('</a>',$dl);
					$dltext=trim(strip_tags('<a href="'.$dltext[0]));

					if(($dltext)&&($dltext!='Share')){
						if(!$r_query[$url2])
						{
							$newURLs[]=Array($url2,$text.' {'.$dltext.'}.'.$ext);
							$new++;
						}
						else
						{
							$old++;
						}
					}
				}	
			}

			print "new: ".$new.", old: ".$old."\n";
		}
	}
}

print "<pre>check folders:\n\n";

foreach($dirs as $dir)
{
	if($dir)listDir($dir);
}

print "\nnew urls:\n\n";

	print "<table><tr><td><pre>";

	foreach($newURLs as $row)
	{
		print $row[0]."\n";
	}

	print "</td><td><pre>";

	foreach($newURLs as $row)
	{
		print "<a href=\"".$row[0]."\" target=_blank>".$row[1]."</a>\n";
	}

	print "</td></tr></table>";



?>