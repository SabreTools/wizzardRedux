<?php

// Original code: The Wizard of DATz

$drive = "Z:/"; // Local drive where EAB is mounted

// Use this to replace drive calls
$remote = getFtpConnection("ftp://ftp:any@ftp.grandis.nu/");

$dirs = array(
	'Atari',
	'Commodore_Amiga',
	'Commodore_C128',
	'Commodore_C16,_C116_&_Plus-4',
	'Commodore_C64',
	'Commodore_C65',
	'Commodore_MAX_Machine_&_VIC10',
	'Commodore_PET',
	'Commodore_VIC20', 
);

$skip_query = implode('', file("../sites/".$source."_skip.txt"));
$skip_query = explode("\n", "\n".$skip_query);
$skip_query = array_flip($skip_query);

$search_ok = true;

print "<pre>check folders:\n\n";

foreach ($dirs as $dir)
{
	if ($search_ok && $dir)
	{
		listDir($dir);
	}
}

if ($search_ok)
{
	$fp = fopen("../sites/".$source."_skip.txt", "w");
	fwrite($fp, "");
	fclose($fp);
}

print "\nnew urls:\n\n";

foreach($found as $url)
{
	print "<a href=\"ftp://ftp:amiga@grandis.nu/".str_replace('#', '%23', $url[0])."\">".$url[1]."</a>\n";
}

function listDir($dir)
{
	sleep(1);

	GLOBAL $drive, $r_query, $found, $skip_query, $search_ok;

	print "open: ".$dir."\n";

	$other = 0;
	$new = 0;
	$old = 0;
	$folder = 0;

	$handle = opendir($drive.$dir);

	if(!$handle)
	{
		$search_ok = false;
	}
	else
	{
		while (false != ($file = readdir($handle)))
		{
			$file2 = "{".str_replace("/", "}{", $dir)."}".$file;

			if ($r_query[$file2])
			{
				$old++;
			}
			else
			{
				$filetype = filetype($drive.$dir."/".$file);

				if ($filetype && $search_ok)
				{
					if ($filetype == "file" && $file != ".listing")
					{
						$found[] = array($dir."/".$file, $file2);
						$new++;
					}
					elseif ($filetype == "dir" && $file != "." && $file != "..")
					{
						$folder++;
						if (!$skip_query[$dir."/".$file])
						{
							listDir($dir."/".$file);
						}
						else
						{
							print "skip: ".$dir."/".$file."\n";
						}
					}
				}
				else
				{
					$search_ok = false;
					break;
				}
			}
		}
		closedir($handle);
	}

	if ($search_ok)
	{
		print "close: ".$dir."\n";
		print "new: ".$new.", old: ".$old.", folder:".$folder."\n";
		$fp = fopen("../sites/".$source."_skip.txt", "a");
		fwrite($fp, $dir."\n");
		fclose($fp);
	}
}

//http://php.net/manual/en/function.ftp-connect.php
function getFtpConnection($uri)
{
	// Split FTP URI into:
	// $match[0] = ftp://username:password@sld.domain.tld/path1/path2/
	// $match[1] = ftp://
	// $match[2] = username
	// $match[3] = password
	// $match[4] = sld.domain.tld
	// $match[5] = /path1/path2/
	preg_match("/ftp:\/\/(.*?):(.*?)@(.*?)(\/.*)/i", $uri, $match);

	// Set up a connection
	$conn = ftp_connect($match[1] . $match[4] . $match[5]);

	// Login
	if (ftp_login($conn, $match[2], $match[3]))
	{
		// Change the dir
		ftp_chdir($conn, $match[5]);

		// Return the resource
		return $conn;
	}

	// Or retun null
	return null;
}

?>