<?php
	$r_query=implode ('', file ($_GET["source"]."/fixed.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

	print "<pre>";

	print "Last fixes\n\n";

	$query=implode ('', file ("http://intros.c64.org/frame.php"));
 	$query=explode ('<div class="menu_header">FIXED</div>', $query);
 	$query=explode ('<a href="main.php?module=showintro&iid=', $query[1]);

	$query[0]=null;

	foreach($query as $row)
	{
		if($row)
		{
			$id=explode('"',$row);
			$gametitle=explode('>',$row);
			$gametitle=explode('<',$gametitle[1]);
			$gametitle=explode (' ', $gametitle[0]);
			$gametitle[count($gametitle)-1]="(".$gametitle[count($gametitle)-1].")";
			$gametitle=implode (' ', $gametitle);

			if (!$r_query[$id[0]]) print $id[0]."\t<a href=http://intros.c64.org/inc_download.php?iid=".$id[0].">".$gametitle.".prg</a>\n";
		}
	}

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

	print "\nSearch for new uploads\n\n";

	for ($x=$start;$x<$start+50;$x++)
	{
		$query=implode ('', file ("http://intros.c64.org/main.php?module=showintro&iid=".$x));

		if($query!="Database error. Please contact us if this problem persists.") 
		{
			$gametitle=explode ('<span class="introname">', $query);
			$gametitle=explode ('</span>', $gametitle[1]);
			$gametitle=explode (' ', $gametitle[0]);
			$gametitle[count($gametitle)-1]="(".$gametitle[count($gametitle)-1].")";
			$gametitle=implode (' ', $gametitle);

			print $x."\t<a href=http://intros.c64.org/inc_download.php?iid=".$x.">".$gametitle.".prg</a>\n";

			$last=$x;
		}
		else
		{
			print $x."\t".$query."\n";
		}
	}

	if($last) $start=$last+1;

	print "\nnext startnr\t<a href=?action=onlinecheck&source=C64intros&start=".($start).">".$start."</a>";

?>