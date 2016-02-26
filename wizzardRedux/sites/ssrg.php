<?php

// Original code: The Wizard of DATz

$r_query = array_flip($r_query);
$start = $r_query[0];

print "<pre>Search for new uploads\n\n";

for ($x = $start; $x < $start + 25; $x++)
{
	print "<span style=\"display:none\" >";
	$query = implode('', file("http://sonicresearch.org/forums/index.php?app=downloads&showfile=".$x));
	print "</span>";

	if ($query)
	{
		$gametitle = explode('<title>', $query);
		$gametitle = explode(' - SSRG Forums</title>', $gametitle[1]);
		$gametitle = $gametitle[0];
		print $x."\t<a href=http://sonicresearch.org/forums/index.php?app=downloads&module=display&section=download&do=confirm_download&id=".$x.">".$gametitle."</a>\n";

		$last = $x;
	}
	else
	{
		print $x."\terror\n";
	}
}

if ($last)
{
	$start = $last + 1;
}

print "\nnext startnr\t".$start;

?>