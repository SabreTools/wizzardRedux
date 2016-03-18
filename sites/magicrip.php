<?php

// Original code: The Wizard of DATz

$dirs = array(
	'/hacks_base.html',
	'/index.html',
	'/new.html',
	'/new_base.html',
	'/new_other.html',
	'/other.html',
	'/trans.html',
	'/trans_base.html',
	'/trans_gba.html',
	'/trans_gba_other.html',
	'/trans_other.html',
);

print "<pre>check folders:\n\n";

foreach ($dirs as $dir)
{
	if ($dir)
	{
		print "load: ".$dir."\n";
		$query = get_data("http://magicrip.narod.ru".$dir);
		$query = preg_replace('/[\v]+[0-9]+[\v]+/', "", $query);
	
		$query = explode(' href="http://magicrip.narod.ru/', $query);
		$query[0] = null;
	
		$new = 0;
		$old = 0;
	
		foreach ($query as $row)
		{
			if ($row)
			{
				$url = explode('"', $row);
				$url = $url[0];
	
				$ext = explode('.', $url);
	
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
	
		print "close: ".$dir."\n";
		print "new: ".$new.", old: ".$old."\n";
	}
}

print "\nnew urls:\n\n";

foreach ($found as $url)
{
	print "<a href=\"http://magicrip.narod.ru/".$url."\">".$url."</a>\n";
}

?>