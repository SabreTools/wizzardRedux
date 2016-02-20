<?php
	print "<pre>";
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=demos>demos</a>\n";
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=games>games</a>\n";

	$url=Array(
		'demos' => 'http://www.c64.com/demos/demos_show.php?showid=',
		'games' => 'http://www.c64.com/games/no-frame.php?showid=',
	);

	if($_GET["type"])
	{
		print "loading ".$_GET["type"]."\n";

		if($_GET["start"])
		{
			$start=$_GET["start"];
			$fp = fopen($_GET["source"]."/".$_GET["type"].".txt", "w");
			fwrite($fp,	$start);
			fclose($fp);
		}
		else
		{
			$start=implode ('', file ($_GET["source"]."/".$_GET["type"].".txt"));
		}

		for ($x=$start;$x<$start+50;$x++)
		{
			$query=implode ('', file ($url[$_GET["type"]].$x));
			if($_GET["type"]=='demos'){
				$query=explode('<td height="41" align="center" valign="middle" bgcolor="#535353">',$query);
				$query=explode('</td>',$query[1]);
				$query=$query[0];
			}else{
				$query=explode('<td height="41" colspan="2">',$query);
				$query=explode('</td>',$query[1]);
				$query=$query[0];
			}

			$gametitle=explode ('<span class="headline_1">', $query);
			$gametitle=explode ('</span>', $gametitle[1]);
			$gametitle=trim($gametitle[0]);

			$addinfo=explode ('<a ', $query);
			$addinfo[0]=null;

			$infos=Array();

			foreach($addinfo as $info){
				if($info){
					$info=explode ('</a>', $info);
					$info=str_replace(array('(',')','?'),Array('','','x'),trim(strip_tags('<a '.$info[0])));
					if($info) $infos[]=$info;
                }
			}

			if($infos) $gametitle=$gametitle." (".implode(") (",$infos).")";

			if($gametitle){
				print $x."\t<a href=http://www.c64.com/".$_GET["type"]."/download.php?id=".$x.">".$gametitle.".zip</a>\n";
				$last=$x;
			}else{
				print $x."\tnot found\n";
			}
		}

		if($last) $start=$last+1;
	
		print "\nnext startnr\t<a href=?action=onlinecheck&source=".$_GET["source"]."&type=".$_GET["type"]."&start=".($start).">".$start."</a>";

	}
?>