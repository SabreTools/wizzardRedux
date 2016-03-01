<?php

// Original code: The Wizard of DATz

print "<pre>";

$r_query = array_flip($r_query);
$start = $r_query[0];
$r_query = array_flip($r_query);

print "\nSearch for new uploads\n\n";

$query = get_data("http://robert.hurst-ri.us/downloads/");

preg_match_all("/href=\"(http:\/\/robert\.hurst-ri\.us\/files\/.*)\/\"/", $query, $dls);

$new = 0;
$old = 0;
foreach ($dls[1] as $dl)
{
	if (!$r_query[$dl])
	{
		$new++;
		print "found: <a href='".$dl."'>".$dl."</a>\n";
	}
	else
	{
		$old++;
	}
}

/*
// Original code, no longer applicable
for ($x = $start; $x < $start + 10; $x++)
{
	$query = get_data("http://robert.hurst-ri.us/downloads/?did=".$x);

	$gametitle = explode('<h3 class="download-info-heading">', $query);
	$gametitle = explode('<', $gametitle[1]);
	$gametitle = trim($gametitle[0]);

	if ($gametitle)
	{
		print $x."\t<a href=http://robert.hurst-ri.us/downloads/?did=".$x." target=_blank>".$gametitle."</a>\n";
		$last = $x;
	}
	else
	{
		print "stop by ".$x.", no data found";
		break;
	}
}

if ($last)
{
	$start = $last + 1;
}

print "\nnext startnr\t<a href=?action=onlinecheck&source=RH&start=".($start).">".$start."</a>\n\n";
*/

?>