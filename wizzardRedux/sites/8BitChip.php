<?php

// Original code: The Wizard of DATz

$base_dl_url = "http://www.8bitchip.info/";
$max = 10000;

for ($xpage = 0; $xpage < $max; $xpage++)
{
	sleep(1);
	$dir = "/atari/ASTGA/astgam.php?s=7&o=".($xpage*50);
	print "load: ".$dir."\n";
	$query = getHTML($dir);
	$query = explode('<tr   onMouseOut=', $query);
	array_splice($query,0,1);

	$new = 0;
	$old = 0;

	$notFound = true;

	foreach ($query as $row)
	{
		$row = explode('<td', $row);
		$url = explode("href='", $row[7]);
		$url = explode("'", $url[1]);
		$url = $url[0];

		if ($url != "")
		{
			$notFound = false;

			$title = trim(strip_tags('<td'.$row[1]));
			$info = Array();
	
			for ($x = 2; $x < 5; $x++)
			{
				$temp = trim(strip_tags('<td'.$row[$x]));
				if ($temp && $temp != "author" && $temp != "n/a")
				{
					$info[] = $temp;
				}
			}

			if ($info)
			{
				$title = $title." (".implode(") (",$info).")";
			}

			sleep(1);
			$dir = "/atari/ASTGA/".$url;
			print "load: ".$dir."\n";
			$queryb = getHTML($dir);
			$queryb = explode('<a href="', $queryb);
			array_splice($queryb, 0, 1);

			$dir = explode('/', $dir);
			$dir[count($dir) - 1] = null;
			$dir = implode('/', $dir);

			foreach ($queryb as $dl)
			{
				$dl = explode('"', $dl);
				$dl = $dl[0];

				$url = $dir.$dl;

				$ext = explode('.', $dl);
				$add = $ext[count($ext) - 2];
				$ext = $ext[count($ext) - 1];
				
				if (strtolower($ext) == "zip")
				{
					print "found: ".$title."{".$add."}.".$ext." # ".$url."\n";
	
					if (!$r_query[$url])
					{
						$found[] = Array($title."{".$add."}.".$ext, $url);
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

	if ($notFound)
	{
		$xpage=$max;
	}

	print "new: ".$new.", old: ".$old."\n";
}

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach($found as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach($found as $url)
{
	print "<a href=\"http://www.8bitchip.info".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

function getHTML($target){
	GLOBAL $GLOBALS;

	$timeout = 500;  // Max time for stablish the conection

	$server  = 'www.8bitchip.info';           			 // Target domain
	$host    = 'www.8bitchip.info';           			 // Domain name
	//$target  = '/new.html';        		 // Target document
	$referer = 'http://www.8bitchip.info';   // Ouput document
	$port    = 80;

	$method = "GET";
	if (is_array($gets))
	{
		$getValues = '?';
		foreach ($gets AS $name => $value)
		{
			$getValues .= urlencode($name)."=".urlencode($value).'&';
		}
		$getValues = substr($getValues, 0, -1);
	}
	else
	{
		$getValues = '';
	}

	if (is_array($posts))
	{
		foreach ($posts AS $name => $value)
		{
			$postValues .= urlencode($name)."=".urlencode($value).'&';
		}
		$postValues = substr($postValues, 0, -1);
		$method = "POST";
	}
	else
	{
		$postValues = '';
	}

	$request = "$method $target$getValues HTTP/1.1\r\n";
	$request .= "Accept: ".$GLOBALS[_SERVER][HTTP_ACCEPT]."\r\n";
	$request .= "Referer: ".$referer."\r\n";
	$request .= "Accept-Language: ".$GLOBALS[_SERVER][HTTP_ACCEPT_LANGUAGE]."\r\n";
	if ($method == "POST")
	{
		$request .= "Content-Type: application/x-www-form-urlencoded\r\n";
	}
	$request .= "UA-CPU: ".$GLOBALS[HTTP_UA_CPU]."\r\n";
	$request .= "Accept-Encoding: deflate\r\n";
	$request .= "User-Agent:".$GLOBALS[_SERVER][HTTP_USER_AGENT]."\r\n";
	$request .= "Host: $host\r\n";
	if ($method == "POST")
	{
		$request .= "Content-Length: ".strlen( $postValues )."\r\n";
	}
	$request .= "Connection: ".$GLOBALS[_SERVER][HTTP_CONNECTION]."\r\n";
	if ($method == "POST")
	{
		$request .= "Cache-Control: no-cache\r\n\r\n".$postValues;
	}

	$socket = fsockopen($server, $port, $errno, $errstr, $timeout);
	fputs($socket, $request."\r\n");

	$ret = '';
	$lastlen = 1;
	socket_set_timeout($socket, 2);
	while ($lastlen > 0)
	{
		$temp = fread($socket, 1024*4);
		$lastlen = strlen($temp);
		$ret .= $temp;
	}
	fclose($socket);

	return $ret;
}

?>