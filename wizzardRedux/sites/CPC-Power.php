<?php

// Original code: The Wizard of DATz

print "<pre>";
print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=full>full</a>\n";
print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=updates>updates</a>\n";

if ($_GET["type"] == "updates")
{
	$full_query = implode('', file("../sites/".$source."_full.txt"));
	$full_query = explode("\r\n","\r\n".$full_query);
	$full_query = array_flip($full_query);
	
	$fullURLs = array(
		"/gamebasecpc/index.php?page=majlast",
		"/gamebasecpc/index.php?page=full",
	);
	
	foreach ($fullURLs as $fullURL)
	{
		$new = 0;
		$old = 0;

		print "load :".$fullURL."\n";
		$query = getHTML($fullURL);
		$query = explode('<img src="images/download.gif" />&nbsp; &nbsp;<a href="', $query);
		$query[0] = null;

		foreach ($query as $row)
		{
			if ($row)
			{
				$row = explode('"', $row);
				if (!$full_query[$row[0]])
				{
					print "<a href=\"http://www.cpc-power.com/gamebasecpc/".$row[0]."\">".$row[0]."</a>\n";
					$new++;
				}
				else
				{
					$old++;
                }
			}
		}

		print "found new ".$new.", old ".$old."\n";
	}
	
	sleep(1);
}

if ($_GET["type"] == "updates")
{
	$u_query = getHTML("/index.php?page=accueil");
	$u_query = explode('&amp;num=', $u_query);
	array_splice($u_query, 0, 1);
}
elseif ($_GET["type"] == "full")
{
	$u_query = array();

	if ($_GET["start"])
	{
		$start = $_GET["start"];
	}
	else
	{
		$start = 1;
	}

	$max = getHTML("/index.php?page=detail&num=1");
	$max = explode('index.php?page=detail&num=', $max);
	$max = explode("'", $max[3]);
	$max = $max[0];

	for ($x = $start; $x <= $max && $x < $start + 1000; $x++)
	{
        $u_query[] = $x;
	}
}

if ($u_query)
{
	foreach ($u_query as $u_page)
	{
		//sleep(1);
		$u_page = explode('"', $u_page);
		$u_page = $u_page[0];

		$dir = "/index.php?page=detail&onglet=dumps&num=".$u_page;

		$query = getHTML($dir);
		$query = explode('images/elementpart.gif', $query);

		array_splice($query, 0, 1);
	
		$new = 0;
		$old = 0;
		$error = 0;
	
		print "load ".$u_page."\n";

		foreach ($query as $row)
		{
			$url = explode('HexaDump.php?', $row);
			$url = explode('"', $url[1]);
			$url = $url[0];

			$crc = explode('CRC:', $row);
			$crc = explode(']', $crc[1]);
			$crc = $crc[0];

			$name = explode(';return false;">', $row);
			$name = explode('<', $name[1]);
			$name = $name[0];
			
			print "found ".$crc." # ".$name." # ".$url."\n";

			if (!$r_query[$crc])
			{
				if (!$url)
				{
					$error++;
				}
				else
				{
					$found[] = array($url, $name, $crc);
					$r_query[$crc] = true;
					$new++;
				}
			}
			else
			{
				$old++;
			}
		}

		print "result old:".$old." new:".$new." error:".$error."\n";

	}
}

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print $row[2]."\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=cpc-power/getfile.php?".$row[0]." target=_blank>".$row[1]."</a>\n";
}

print "</td></tr></table>";

if ($_GET["type"] == "full" && $max > $start+1000)
{
	print"\n<a href=?action=onlinecheck&source=".$_GET["source"]."&type=full&start=".($start+1000).">next ".($start+1000)."</a>\n
		<script>
		window.open('?action=onlinecheck&source=".$_GET["source"]."&type=full&start=".($start+1000)."','_blank');
		</script>
		";
}
	
function getHTML($target)
{
	GLOBAL $GLOBALS;

	$timeout = 100;  // Max time for stablish the conection

	$server  = 'www.cpc-power.com';           			 // Ziel domain
	$host    = 'www.cpc-power.com';           			 // Domain name
	//$target  = '/new.html';        		 // Ziel document
	$referer = 'http://www.cpc-power.com';   // ausgangs document
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

	$socket  = fsockopen( $server, $port, $errno, $errstr, $timeout );
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