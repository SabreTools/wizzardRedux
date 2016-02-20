<?php

function getHTML($target){
	GLOBAL $GLOBALS;

	$timeout = 100;  // Max time for stablish the conection
	
	$server  = 'vic20.it';           			 // Ziel domain
	$host    = 'vic20.it';           			 // Domain name
	//$target  = '/new.html';        		 // Ziel document
	$referer = 'http://vic20.it';   // ausgangs document
	$port    = 80;

	$method = "GET";
	if ( is_array( $gets ) ) {
	   $getValues = '?';
	   foreach( $gets AS $name => $value ){
	       $getValues .= urlencode( $name ) . "=" . urlencode( $value ) . '&';
	   }
	   $getValues = substr( $getValues, 0, -1 );
	} else {
	   $getValues = '';
	}
	
	if ( is_array( $posts ) ) {
	   foreach( $posts AS $name => $value ){
	       $postValues .= urlencode( $name ) . "=" . urlencode( $value ) . '&';
	   }
	   $postValues = substr( $postValues, 0, -1 );
	   $method = "POST";
	} else {
	   $postValues = '';
	}
	
	$request  = "$method $target$getValues HTTP/1.1\r\n";
	$request .= "Accept: ".$GLOBALS[_SERVER][HTTP_ACCEPT]."\r\n";
	$request .= "Referer: ".$referer."\r\n";
	$request .= "Accept-Language: ".$GLOBALS[_SERVER][HTTP_ACCEPT_LANGUAGE]."\r\n";
	if ($method == "POST" ) $request .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$request .= "UA-CPU: ".$GLOBALS[HTTP_UA_CPU]."\r\n";
	$request .= "Accept-Encoding: deflate\r\n";
	$request .= "User-Agent:".$GLOBALS[_SERVER][HTTP_USER_AGENT]."\r\n";
	$request .= "Host: $host\r\n";
	if ($method == "POST" ) $request .= "Content-Length: ".strlen( $postValues )."\r\n";
	$request .= "Connection: ".$GLOBALS[_SERVER][HTTP_CONNECTION]."\r\n";
	if ($method == "POST" ) $request .= "Cache-Control: no-cache\r\n\r\n".$postValues;

	$socket  = fsockopen( $server, $port, $errno, $errstr, $timeout );
	fputs( $socket, $request."\r\n" );

	$ret = '';
	$lastlen=1;
	  	socket_set_timeout($socket, 2);
	while ($lastlen>0)
	{
		$temp = fread($socket,1024*4);
		$lastlen=strlen($temp);
	    $ret .=$temp;
	}
	fclose( $socket );
	
	return $ret;
}


$dirs=Array(
	Array('PaperSoft Prima serie','http://vic20.it/papersoft/1-1.html?ckattempt=1'),
	Array('PaperSoft Prima serie','http://vic20.it/papersoft/1-2.html?ckattempt=1'),
	Array('PaperSoft Seconda serie','http://vic20.it/papersoft/2-2.html?ckattempt=1'),
	Array('PaperSoft Terza serie','http://vic20.it/papersoft/3-2.html?ckattempt=1'),
);

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();

function listDir($dir){
	GLOBAL $newURLs, $r_query;

	print "load: ".$dir[1]."\n";

	$query=getHTML($dir[1]);
	$query=str_replace('<div style="text-align: center;">','<p>',$query);
	$query=explode('<p><strong><font face="Verdana"><font size="3">',$query);
	$query[0]=null;

	$new=0;
	$old=0;

	foreach($query as $row){
		if($row){
			$title=explode('<a',$row);
			$title=$dir[0]." - ".trim(strip_tags($title[0]));

			$urls=explode('"http://files.vic20.it/papersoft/programs/',$row);
			$urls[0]=null;
			
			foreach($urls as $url){
				if($url){
					$url=explode('"',$url);
					$url=$url[0];			

					$split=explode('.',$url);

					if(!$r_query[$url])
					{
						$newURLs[]=array($title.' ('.$split[0].').'.$split[1],$url);
						$new++;
					}
					else
					{
						$old++;
					}

				}
			}


		}
	}

	print "close: ".$dir[1]."\n";
	print "new: ".$new.", old: ".$old."\n";
}

print "<pre>check folders:\n\n";

foreach($dirs as $dir)
{
	listDir($dir);
}

print "\nnew urls:\n\n";

print "<table><tr><td><pre>";

foreach($newURLs as $url)
{
	print "<a href=\"http://files.vic20.it/papersoft/programs/".$url[1]."\">".$url[0]."</a>\n";
}


print "</td><td><pre>";

foreach($newURLs as $url)
{
	print $url[1]."\n";
}

print "</td></tr></table>";

?>