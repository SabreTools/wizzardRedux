<?php

// Original code: The Wizard of DATz
 
print "<pre>";

$dirs = array();

for ($x = 1; $x < 1000; $x++)
{
	$query = implode('', file('https://app.box.com/shared/mlxzoyjyr7/'.$x.'/59652717'));
	$query = explode('id=\"filename_', $query);
	array_splice($query, 0, 1);

	$new = 0;
	$old = 0;

	if (!$query)
	{
		break;
	}

	foreach ($query as $row)
	{
		$id = explode('\\', $row);
		$id = $id[0];

		$title = explode('"', $row);
		$title = $title[2];

		if (!$r_query[$id])
		{
			$found[] = array($title, $id, $x);
			$new++;
			$r_query[$id] = true;
		}
		else
		{
			$old++;
		}
	}
	print "load https://app.box.com/shared/mlxzoyjyr7/".$x."/59652717\n";
	print "new: ".$new.", old: ".$old."\n";
}

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach ($found as $url)
{
	print "<a target=_blank href=\"https://app.box.com/shared/mlxzoyjyr7/".$url[2]."/59652717/".$url[1]."/1\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>