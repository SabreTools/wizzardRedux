<?php

// Original code: The Wizard of DATz

// Use this to replace drive calls
$server = "grandis.nu";
$resource = ftp_connect($server) or die("Couldn't connect to ".$server);
if (!ftp_login($resource, "ftp", "any"))
{
	die("Could not log in to ".$server);
}

$dirs = array(
	'Atari',
//	'Commodore_Amiga',
//	'Commodore_C128',
//	'Commodore_C16,_C116_&_Plus-4',
//	'Commodore_C64',
//	'Commodore_C65',
//	'Commodore_MAX_Machine_&_VIC10',
//	'Commodore_PET',
//	'Commodore_VIC20', 
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

ftp_close($resource);

if ($search_ok)
{
	/*
	$fp = fopen("../sites/".$source."_skip.txt", "w");
	fwrite($fp, "");
	fclose($fp);
	*/
}

print "\nnew urls:\n\n";

foreach($found as $url)
{
	print "<a href=\"ftp://ftp:amiga@grandis.nu/".str_replace('#', '%23', $url[0])."\">".$url[1]."</a>\n";
}

function listDir($dir)
{
	sleep(1);

	GLOBAL $r_query, $found, $skip_query, $search_ok, $resource;

	print "open: ".$dir."\n";

	$other = 0;
	$new = 0;
	$old = 0;
	$folder = 0;

	if(!ftp_chdir($resource, $dir))
	{
		$search_ok = false;
	}
	else
	{
		$pwd = ftp_pwd($resource);
		$files = ftp_nlist($resource, $pwd);
		
		foreach ($files as $file)
		{
			// If the file already exists, skip it
			if ($r_query[$file])
			{
				//print "found old: ".$file."\n";
				$old++;
			}
			// Otherwise, either add it to the list or go into the new directory
			else
			{
				// If the item is a directory, move into it and parse
				if (is_ftp_dir($file))
				{
					$folder++;
					if (!$skip_query[$file])
					{
						listDir($file);
					}
					else
					{
						print "skip: ".$file."\n";
					}
				}
				// Otherwise, it's a file that should be checked
				else
				{
					print "found: ".$file."\n";
					$found[] = $file;
					$new++;
					die();
				}
			}
		}
	}

	if ($search_ok)
	{
		print "close: ".$dir."\n";
		print "new: ".$new.", old: ".$old.", folder:".$folder."\n";
		/*
		$fp = fopen("../sites/".$source."_skip.txt", "a");
		fwrite($fp, $dir."\n");
		fclose($fp);
		*/
	}
}

function is_ftp_dir($dirname)
{
	GLOBAL $resource;
	
	$pwd = ftp_pwd($resource);
	if (@ftp_chdir($resource, $dirname))
	{
		ftp_chdir($resource, $pwd);
		return true;
	}
	else
	{
		return false;
	}
}

?>