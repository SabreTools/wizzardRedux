<?php

// Original code: The Wizard of DATz

$dirs = array(
	'http://retroprograms.com/PC-88/',
	'http://retroprograms.com/PC98/',
	'http://retroprograms.com/misc/',
	'http://retroprograms.com/X68K/',
	'http://retroprograms.com/floppies/',
	'http://retroprograms.com/NCRDMV/',
	'http://retroprograms.com/IBMPC/',
	'http://retroprograms.com/Apple/',
	'http://retroprograms.com/Others/',
);

print "<pre>check folders:\n\n";

foreach ($dirs as $dir)
{
	listDir($dir);
}

listDir2("http://slt.retroprograms.com/");

print "\nnew urls:\n\n";

foreach ($found as $url)
{
	print "<a href=\"".$url."\">".$url."</a>\n";
}

function listDir($dir)
{
	GLOBAL $found, $r_query;

	print "load: ".$dir."\n";

	$query = get_data($dir);
	$query = explode('>Parent Directory<', $query);
	if ($query[1])
	{
		$query = $query[1];
	}
	else
	{
		$query = $query[0];
	}
	$query = str_replace(' HREF="', ' href="', $query);
	$query = explode(' href="', $query);
	$query[0] = null;

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $dir.$url[0];

			if (substr($url, -1) == '/')
			{
				listDir($url);
			}
			else
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
		}
	}

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old."\n";
}

function listDir2($dir)
{
	GLOBAL $found, $r_query;

	print "load: ".$dir."\n";

	$query = get_data($dir);
	$query = str_replace(' HREF="', ' href="', $query);
	$query = explode(' href="', $query);
	array_splice($query, 0, 1);

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		$url = explode('"', $row);
		$url = $url[0];

		$ext = explode('.', $url);
		$ext = $ext[count($ext) - 1];

		if ($ext != "html")
		{
			if (!$r_query[$url])
			{
				$found[] = $url;
				$r_query[$url] = true;
				$new++;
			}
			else
			{
				$old++;
			}
		}
		else
		{
			$domain = explode('/', $url);
			$domain = $domain[2];
			if ($domain == "slt.retroprograms.com")
			{
				listDir2($url);
			}
		}
	}

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old."\n";
}

?>