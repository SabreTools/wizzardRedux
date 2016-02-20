<?php
function getHTML($target){
	GLOBAL $GLOBALS;

/*	$timeout = 100;  // Max time for stablish the conection
	
	$server  = 'info.sonicretro.org';           			 // Ziel domain
	$host    = 'info.sonicretro.org';           			 // Domain name
	//$target  = '/new.html';        		 // Ziel document
	$referer = 'http://info.sonicretro.org';   // ausgangs document
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
	$request .= "Accept-Encoding: none\r\n";
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

	$ret = preg_replace('/\r\n(\d{1,4})\r\n/','',$ret);*/
	
	$ret=file_get_contents("http://info.sonicretro.org".$target, false,stream_context_create(array(
	'http'=>array(
		'method'	=>	"GET",
		'header'	=>	"Referer: http://info.sonicretro.org \r\n",
	))));
	
	return $ret;
}

print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$pages=Array();
$found=Array();
$cats=Array();
$cats[]="/Category:Hacks";
$cats[]="/Category:Prereleases";
$cats[]="/Category:Pirate_and_unlicensed_games";

foreach($cats as $cat){
	print "load http://info.sonicretro.org".$cat."\n";
	$query=getHTML($cat);

	$query=explode('<h2>Subcategories</h2>',$query);
	$query=explode('</table>',$query[1]);
	$query=explode('<li><a href="',$query[0]);
	$query[0]=null;
	
	$new=0;
	
	foreach($query as $row){
		if($row){
			$row=explode ('"', $row);
			$row=$row[0];

			print "found ".$row."\n";

			$cats[$row]=$row;
			$new++;
	   	}
	}
	
	print "found cats ".$new."\n";
}

foreach($cats as $cat){
	$old=0;
	$new=0;
	print "load http://info.sonicretro.org".$cat."\n";
	$query=getHTML($cat);
	$query=explode('<h2>Pages in category',$query);
	$query=explode('</table>',$query[1]);
	$query=explode('<li><a href="',$query[0]);
	$query[0]=null;

	foreach($query as $row){
		if($row){
			$row=explode ('"', $row);
			$row=$row[0];

			print "found ".$row."\n";

	    	if($pages[$row])
			{
				$old++;
			}else{
				$pages[$row]=$row;
				$new++;
			}
    	}
	}

	print "found sites add:".$new.", skipped:".$old."\n";
}

foreach($pages as $page){
	print "load http://info.sonicretro.org".$page."\n";
	$query=getHTML($page);

	$title=explode('<title>',$query);
	$title=explode(' - Sonic Retro</title>',$title[1]);
	$title=$title[0];

 	$query=explode (' href="/images/', $query);
	array_splice ($query,0,1);

	$old=0;
	$new=0;

	foreach($query as $row){
			$row=explode ('"', $row);
			$row=$row[0];

			$file=explode ('/', $row);
			$file=$file[count($file)-1];

			print "load http://info.sonicretro.org/File:".$file."\n";

			$f_query=getHTML("/File:".$file);
		 	$f_query=explode (' href="/images/', $f_query);
			array_splice ($f_query,0,1);

			$found_new=false;


			foreach($f_query as $f_row){
				$f_row=explode ('"', $f_row);
				$f_row=$f_row[0];

				$f_file=explode ('/', $f_row);
				$f_file=$f_file[count($f_file)-1];
				
				print "found :".$f_row." # ".$title."~~~~".$f_file."\n";

		    	if($r_query[$f_row]){
					$old++;
				}else{
					$found[$f_row]=Array($f_row,$title."~~~~".$f_file);
					$new++;
					$found_new=true;
				}
			}

			if($found_new) $found[$row]=Array($row,$title."~~~~".$file);
	}

	print "found new:".$new.", old:".$old."\n";
}

print "<table><tr><td><pre>";

foreach($found as $row){
	print $row[0]."\n";
}

print "</td><td><pre>";

foreach($found as $row){
	print "<a href=\"http://info.sonicretro.org/images/".$row[0]."\">".$row[1]."</a>\n";
}

print "</td></tr></table>";


?>