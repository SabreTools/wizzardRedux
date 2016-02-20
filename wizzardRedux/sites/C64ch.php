<?php
	if($_GET["start"])
	{
		$start=$_GET["start"];
		$fp = fopen($_GET["source"]."/ids.txt", "w");
		fwrite($fp,	$start);
		fclose($fp);
	}
	else
	{
		$start=implode ('', file ($_GET["source"]."/ids.txt"));
	}

	print "<pre>Search for new uploads\n\n";

	for ($x=$start;$x<$start+50;$x++)
	{
		$query=trim(implode ('', file ("http://c64.ch/demos/realdetail.php?id=".$x)));

		$OK=explode ('C64.CH - The C64 Demo Portal - News', $query);

		$info=Array();

		$gametitle=explode ('<td style="background:url(/img/m/t2b.gif);" colspan="3" width="432"><span class="mt">', $query);
		$gameauthor=explode ('</span>', $gametitle[1]);
		$gametitle=explode (' by <a', $gametitle[1]);
		$gametitle=trim(str_replace(array(' (',')'),array(', ',''),$gametitle[0]));

		$info[]=$gametitle;

		$gameauthor=explode ('group">', $gameauthor[0]);
		$gameauthor=explode ('</a>', $gameauthor[1]);
		$gameauthor=trim($gameauthor[0]);

		$year=explode ('/demos/list.php?year=', $query);
		$year=explode ('</a>', $year[1]);
		$year=explode ('>', $year[0]);
		$year=trim($year[1]);

		if($year)$info[]=$year;

		$party=explode ('/demos/list.php?source=party&partyid=', $query);
		$party=explode ('</a>', $party[1]);
		$party=explode ('>', $party[0]);
		$party=trim(str_replace(array(' (',')'),array(', ',''),$party[1]));

		if($party)$info[]=$party;

		$gametitle=$gameauthor." (".implode(") (",$info).").zip";

		if($OK[1])
		{
			print $x."\tnot found\n";
		}
		else
		{
			print $x."\t<a href=http://c64.ch/demos/download.php?id=".$x.">".$gametitle."</a>\n";
			$last=$x;
		}
	}

	if($last) $start=$last+1;

	print "\nnext startnr\t<a href=?action=onlinecheck&source=".$_GET["source"]."&start=".($start).">".$start."</a>";

?>