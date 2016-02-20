<?php
	print "<pre>";

	if($_GET["start"])
	{
		$start=$_GET["start"];
		$fp = fopen($_GET["source"]."/start.txt", "w");
		fwrite($fp,	$start);
		fclose($fp);
	}
	else
	{
		$start=implode ('', file ($_GET["source"]."/start.txt"));
	}

	print "\nSearch for new uploads\n\n";

	for ($x=$start;$x<$start+10;$x++)
	{
		$query=implode ('', file ("http://robert.hurst-ri.us/downloads/?did=".$x));

		$gametitle=explode ('<h3 class="download-info-heading">', $query);
		$gametitle=explode ('<', $gametitle[1]);
		$gametitle=trim($gametitle[0]);

		if($gametitle)
		{
			print $x."\t<a href=http://robert.hurst-ri.us/downloads/?did=".$x." target=_blank>".$gametitle."</a>\n";
			$last=$x;
		}
		else
		{
			print "stop by ".$x.", no data found";
			break;
		}
	}

	if($last) $start=$last+1;

	print "\nnext startnr\t<a href=?action=onlinecheck&source=RH&start=".($start).">".$start."</a>\n\n";

?>