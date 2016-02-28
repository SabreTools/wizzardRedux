<?php

// Original code: The Wizard of DATz

$dirULRs = array(
	'library' => true,
);

$badRoot = array(
	'res' => true,
	'winboards' => true,
	'irc' => true,
	'links' => true,
	'about' => true,
	'contact' => true,
	'login' => true,
	'logout' => true,
	'<%= URL %>' => true,
);

$error = 0;

/*
if (!$_GET["cookie"])
{
	print"<form method=\"get\" action=\"?\">
	<input name=action value=onlinecheck type=hidden>
	<input name=source value=WinWorld type=hidden>
	<input name=type value=forum type=hidden>
	<input name=cookie type=text>
	<input type=submit></form>";
}
else
{
*/
	print "<pre>check folders:\n\n";
	listDir("library","library", $_GET["cookie"], 1);
//}

print "\nnew urls:\n\n";

foreach ($found as $url)
{
	print "<a href=\"".$url[0]."\">".$url[1]."</a>\n";
}

print "errors: " . $error;

function listDir($dir, $ref, $cookie, $deep)
{
	GLOBAL $found, $r_query, $dirULRs, $badRoot, $error;

	print $deep." - load [dir] : ".$dir."\n";

	$query = file_get_contents("https://winworldpc.com/".$dir, false, stream_context_create(array(
			'http'=>array(
					'method'	=>	"GET",
					'header'	=>	"Referer: https://winworldpc.com/".$ref." \r\n".
					"Cookie: winworld_session=".$cookie." \r\n",
			))));

	$query = str_replace("http:///","http://wdl1.winworldpc.com/", $query);

	$query = explode('<a href="', $query);
	array_splice($query, 0, 1);

	$found = false;

	foreach ($query as $row)
	{
		$url = explode('"', $row);
		$url = trim($url[0], "/");

		$root = explode('/', $url);
		$root = $root[0];
		if (!$badRoot[$root] && $url && !$dirULRs[$url])
		{
			$dirULRs[$url] = true;
				
			$splitURL = explode('://', $url);

			if ($splitURL[1])
			{
				$splitURL = explode('.winworldpc.com/', $splitURL[1]);
				if ($splitURL[1] && $splitURL[0])
				{	
					if (substr($splitURL[1], 0, 2) == "./")
					{
						$splitURL = substr($splitURL[1], 2);
					}
					else
					{
						$splitURL = $splitURL[1];
					}

					if (!$r_query[$splitURL])
					{
						$found[] = array($url, $splitURL);
						$r_query[$splitURL] = true;
						print $deep." - found [file] : ". $url."\n";
					}
					else
					{
						print $deep." - reject [file] : ". $url."\n";
					}

					$found=true;
				}
				else
				{
					//print "reject [external url] : ". $url."\n";
				}
			}
			else
			{
				listDir($url, $dir, $cookie, $deep+1);
			}
		}
		else 
		{
			//print "reject [allready done] : ". $url."\n";
		}
	}

	print $deep." - close [dir] : ".$dir."\n";

	$dir = explode('/',$dir);

	if ($dir[2] == "from" && !$found)
	{
		//	die("found no download");
		$error++;
	}
}

?>