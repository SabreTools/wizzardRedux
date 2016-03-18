<?php

// Original code: The Wizard of DATz
	
$url = array(
	'demos' => 'http://www.c64.com/demos/demos_show.php?showid=',
	'games' => 'http://www.c64.com/games/no-frame.php?showid=',
);

$r_query = array_flip($r_query);
$demos_start = explode("=", $r_query[0]);
$demos_start = $demos_start[1];
$games_start = explode("=", $r_query[1]);
$games_start = $games_start[1];

echo "<table>\n";
$demos_end = parse_games("demos", $demos_start);
$games_end = parse_games("games", $games_start);
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";
	
function parse_games($type, $x)
{
	GLOBAL $found, $url;
	
	echo "<tr><td colspan=2>".$type."</td></tr>";
	
	while (true)
	{
		echo "<tr><td>".$url[$type].$x."</td>";
		
		$query = get_data($url[$type].$x);
		
		if (preg_match("/Download\s+now/s", $query) !== 1)
		{
			break;
		}
		
		if ($type == 'demos')
		{
			preg_match("/<td height=\"41\" align=\"center\" valign=\"middle\" bgcolor=\"#535353\">(.*?)<\/td>/s", $query, $query);
			$query = $query[1];
		}
		else
		{
			preg_match("/<td height=\"41\" colspan=\"2\">(.*?)<\/td>/s", $query, $query);
			$query = $query[1];
		}
	
		preg_match("/<span class=\"headline_1\">(.*?)<\/span>/s", $query, $gametitle);
		$gametitle = trim($gametitle[1]);
	
		preg_match_all("/<a.*?>(.*?)<\/a>/s", $query, $addinfo);
		$infos = array();
		foreach ($addinfo[1] as $info)
		{
			$info = str_replace(array('(', ')', '?'), array('', '', 'x'), trim(strip_tags($info)));
			if ($info !== "")
			{
				$infos[] = $info;
			}
		}
		
		if (sizeof($infos) > 0)
		{
			$gametitle = $gametitle." (".implode(") (", $infos).")";
		}
	
		$found[] = array("{".$type."-".$x."}".$gametitle.".zip", "http://www.c64.com/".$type."/download.php?id=".$x);
		$last = $x;
		
		echo "<td>Found new: 1, old: 0</tr>\n";
		
		$x++;
	}
	
	return $start;
}
?>