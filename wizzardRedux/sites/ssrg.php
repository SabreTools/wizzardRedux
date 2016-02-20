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

	for ($x=$start;$x<$start+25;$x++)
	{
		print "<span style=\"display:none\" >";
		$query=implode ('', file ("http://sonicresearch.org/forums/index.php?app=downloads&showfile=".$x));
		print "</span>";

		if($query)
		{
			$gametitle=explode ('<title>', $query);
			$gametitle=explode (' - SSRG Forums</title>', $gametitle[1]);
			$gametitle=$gametitle[0];
			print $x."\t<a href=http://sonicresearch.org/forums/index.php?app=downloads&module=display&section=download&do=confirm_download&id=".$x.">".$gametitle."</a>\n";

			$last=$x;
		}
		else
		{
			print $x."\terror\n";
		}
	}

	if($last) $start=$last+1;

	print "\nnext startnr\t<a href=?action=onlinecheck&source=".$_GET["source"]."&start=".($start).">".$start."</a>";

?>