<?php

// Original code: The Wizard of DATz

print "<pre>";

for ($x = 1; $x <= 10; $x++)
{
	$new = 0;
	$old = 0;
	
	$url = "http://hhug.me/?tags=dumps&page=".$x;

	print "load: ".$url."\n";

	$query = implode('', file($url));
	$query = explode("<a href=\"uploads/dumps/", str_replace("\r\n", '', $query));
	$query[0] = null;
	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $url[0];
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
	print "found new ".$new.", old ".$old."\n";

	if (!$new && !$old)
	{
		break;
	}
}

foreach ($found as $row)
{
	print "<a href=\"http://hhug.me/uploads/dumps/".$row."\" target=_blank>".$row."</a>\n";
}

?>