<?php

$r_query = array_flip($r_query);
	
foreach ($r_query as $row)
{
	$row = explode("\t", $row);
	$r_query[$row[1]] = true;
	if ($row[0])
	{
		$start = $row[0];
	}
}

print "<pre>Search for new uploads, start by ".$start."\n\n";

$new = 0;
$old = 0;

$lastone = false;
$x = $start;
while (!$lastone)
{
	$query = trim(get_data("http://www.velus.be/cpc-".$x.".html"));

	$info = array();

	$temp = explode('>Nom : ', $query);
	$temp = explode('<', $temp[1]);
	$info[] = $temp[0];

	$temp = explode('>Copyright  : ', $query);
	$temp = explode('<', $temp[1]);
	if ($temp[0])
	{
		$info[] = "(".$temp[0].")";
	}

	$temp = explode('>Date de création : ', $query);
	$temp = explode('<', $temp[1]);
	if ($temp[0])
	{
		$info[] = "(".$temp[0].")";
	}

	$temp = explode('href="download/', $query);
	$temp = explode('"', $temp[1]);
	$dl = $temp[0];

	$gametitle = implode(" ", $info);

	if ($dl == "")
	{
		$lastone = true;
	}
	if (!$r_query[$dl])
	{
		print $x."\t".$dl."\t".$gametitle."\n";
		$r_query[$dl] = true;
		$new++;
		$found[] = array($gametitle, $dl);
	}
	else
	{
		$old++;
	}
	$x++;
}

print "found:<br/><br/>";
foreach ($found as $item)
{
	print "<a href='http://www.velus.be/download".$item[1]."'>".$item[0]."</a><br/>";
}
?>