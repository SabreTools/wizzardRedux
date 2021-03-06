<?php

// Original code: The Wizard of DATz

print "<pre>";

$dir = "http://digitaldream.totalh.net/giochi-c64.htm";
print "load: ".$dir."\n";
$query = getHTML($dir);

$query = explode('<tr><td><a href="giochi/', html_entity_decode($query));
array_splice($query, 0, 1);

$new = 0;
$old = 0;

$notFound = true;

foreach ($query as $DL)
{
	$DL = explode('"', $DL);
	$DL = $DL[0];

	if (!$r_query[$DL])
	{
		$found[] = $DL;
		$new++;
	}
	else
	{
		$old++;
	}
}

print "new: ".$new.", old: ".$old."\n";

print "\nnew urls:\n\n";

foreach ($found as $url)
{
	print $url."\n";
}

// Can't use get_data($url) because of parsing issues with the site
function getHTML($target)
{
	$timeout = 100;  // Max time for stablish the conection
	$server = 'digitaldream.totalh.net';
	$host = 'digitaldream.totalh.net';
	//$target  = '/new.html';
	$referer = 'http://digitaldream.totalh.net/index.htm';
	$port = 80;

	$request="GET /giochi-c64.htm HTTP/1.1
Host: digitaldream.totalh.net
Connection: close
User-Agent: Googlebot/2.1 (+http://www.googlebot.com/bot.html)
Accept-Charset: ISO-8859-1,UTF-8;q=0.7,*;q=0.7
Cache-Control: no-cache
Accept-Language: de,en;q=0.7,en-us;q=0.3
Referer: http://web-sniffer.net/
";

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

?>