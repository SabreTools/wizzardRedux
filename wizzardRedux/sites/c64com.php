<?php

// Original code: The Wizard of DATz
	
print "<pre>";
print "load <a href=?action=onlinecheck&source=".$source."&type=demos>demos</a>\n";
print "load <a href=?action=onlinecheck&source=".$source."&type=games>games</a>\n";
	
$url = array(
	'demos' => 'http://www.c64.com/demos/demos_show.php?showid=',
	'games' => 'http://www.c64.com/games/no-frame.php?showid=',
);

$r_query = array_flip($r_query);
$demos_start = explode("=", $r_query[0]);
$demos_start = $demos_start[1];
$games_start = explode("=", $r_query[1]);
$games_start = $games_start[1];

$demos_end = parse_games("demos", $demos_start);
$games_end = parse_games("games", $games_start);

/*
// Handy AFTER we automatically download
$handle = fopen("../sites/".$source.".txt", "w");
fwrite($handle, "demos=".$demos_end);
fwrite($handle, "games=".$games_end);
fclose($handle);
*/
	
function parse_games($type, $start)
{
	print "loading ".$type."\n";
	
	for ($x = $start; $x < $start + 50; $x++)
	{
		$query = get_data($url[$type].$x);
		if ($type == 'demos')
		{
			$query = explode('<td height="41" align="center" valign="middle" bgcolor="#535353">', $query);
			$query = explode('</td>', $query[1]);
			$query = $query[0];
		}
		else
		{
			$query = explode('<td height="41" colspan="2">', $query);
			$query = explode('</td>', $query[1]);
			$query = $query[0];
		}
	
		$gametitle = explode('<span class="headline_1">', $query);
		$gametitle = explode('</span>', $gametitle[1]);
		$gametitle = trim($gametitle[0]);
	
		$addinfo = explode('<a ', $query);
		$addinfo[0] = null;
			
		$infos = array();
			
		foreach ($addinfo as $info)
		{
			if ($info)
			{
				$info = explode('</a>', $info);
				$info = str_replace(array('(', ')', '?'), array('', '', 'x'), trim(strip_tags('<a '.$info[0])));
				if ($info)
				{
					$infos[] = $info;
				}
			}
		}
	
		if ($infos)
		{
			$gametitle = $gametitle." (".implode(") (", $infos).")";
		}
	
		if ($gametitle)
		{
			print $x."\t<a href=http://www.c64.com/".$type."/download.php?id=".$x.">".$gametitle.".zip</a>\n";
			$last = $x;
		}
		else
		{
			print $x."\tnot found\n";
		}
	}
	
	if ($last)
	{
		$start = $last + 1;
	
		print "\nnext startnr\t<a href=?action=onlinecheck&source=".$source."&type=".$type."&start=".($start).">".$start."</a>";
	}
	
	return $start;
}
?>