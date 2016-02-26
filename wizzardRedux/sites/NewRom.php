<?php

// Original code: The Wizard of DATz

$dirs = array(
	'/page1.htm',
	'/page2.htm',
	'/page3.htm',
);

print "<pre>check folders:\n\n";

foreach ($dirs as $dir)
{
	if ($dir)
	{
		listDir($dir);
	}
}

print "\nnew urls:\n\n";

foreach ($found as $url)
{
	print "<a href=\"http://rufiles.narod.ru/".$url."\">".$url."</a>\n";
}

function getHTML($target)
{
	GLOBAL $GLOBALS;

	$timeout = 100;  // Max time for stablish the conection

	$server  = 'rufiles.narod.ru';           			 // Ziel domain
	$host    = 'rufiles.narod.ru';           			 // Domain name
	//$target  = '/new.html';        		 // Ziel document
	$referer = 'http://rufiles.narod.ru';   // ausgangs document
	$port    = 80;

	$method = "GET";
	if (is_array($gets))
	{
		$getValues = '?';
		foreach ($gets as $name => $value)
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
		foreach ($posts as $name => $value)
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

	$request  = "$method $target$getValues HTTP/1.1\r\n";
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
		$temp = fread($socket, 1024 * 4);
		$lastlen = strlen($temp);
		$ret .= $temp;
	}
	fclose($socket);

	return $ret;
}

function listDir($dir)
{
	sleep(2);
	GLOBAL $found, $r_query;

	print "load: ".$dir."\n";
	$query = getHTML($dir);
	$query = explode('<a href="', $query);
	$query[0] = null;

	$new = 0;
	$old = 0;
	$other = 0;

	foreach($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $url[0];

			$ext = explode('.', $url);

			if ($ext[count($ext) - 1] == '7z')
			{
				if (!$r_query[$url])
				{
					$found[] = $url;
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

?>