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
		sleep(2);

		print "load: ".$dir."\n";
		$query = get_data("http://rufiles.narod.ru".$dir);
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
}

print "\nnew urls:\n\n";

foreach ($found as $url)
{
	print "<a href=\"http://rufiles.narod.ru/".$url."\">".$url."</a>\n";
}

?>