<?php

// Original code: The Wizard of DATz

$x = 0;

echo "<table>\n";
while (true)
{
	$new = 0;
	$old = 0;

	echo "<tr><td>http://computeremuzone.com/?id=games&cat=&val=&order=nom&pag=".$x."</td>";
	
	$query = get_data("http://computeremuzone.com/?id=games&cat=&val=&order=nom&pag=".$x);
	
	preg_match_all("/<tr >\s*<td class=\"Juegos\">.*?<\/tr>/s", $query, $query);
	$query = $query[0];
	
	// If no games are found, it's the end of the list
	if (sizeof($query) == 0)
	{
		break;
	}

	foreach ($query as $row)
	{
		$regex_gen = "<td class=\"Juegos\">\s*<a href=\".*?\">(.*?)<\/a>.*?<\/td>";
		$regex_links = "<td class=\"J_desc E10\">\s*(.*?)\s*<\/td>";
		
		$regex_game = "/".
			$regex_gen.".*?".		// Game
			$regex_gen.".*?".		// Author
			$regex_gen.".*?".		// Year
			$regex_links.".*?".		// Downloads
		"/s";
		
		preg_match($regex_game, $row, $gameinfo);
		unset($gameinfo[0]);

		$gametitle = $gameinfo[1]." (".$gameinfo[2]." ".$gameinfo[3].")";
		
		preg_match_all("/<a href=\"\/contador\.php\?(f_ad=(.*?)&n_ar=(.*?)&dd=(.*?)&f=(.*?)&sis=(.*?))\">(.*?)<\/a>/", $gameinfo[4], $dls);
		
		$newdls = array();
		for ($index = 0; $index < sizeof($dls[0]); $index++)
		{
			$newdls[] = array(
					$dls[1][$index],
					$dls[2][$index],
					$dls[3][$index],
					$dls[4][$index],
					$dls[5][$index],
					$dls[6][$index],
					$dls[7][$index],
			);
		}
		
		foreach ($newdls as $dl)
		{
			$dl_url = $dl[0];
			$dl_ext = pathinfo($dl[1], PATHINFO_EXTENSION);
			$dl_type = $dl[6];

			if ($r_query[$dl_type."\t".$dl_url."\t".$gametitle.".".$dl_ext] !== NULL)
			{
				$old++;
			}
			else
			{
				$new++;
				$found[] = array("{".$dl_type."}".$gametitle.'.'.$dl_ext, "http://computeremuzone.com/contrador.php?".$dl_url);
			}
		}
	}
		
	echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";
	$x++;
}
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>\n";
}

foreach ($found as $row)
{
	echo "<a href='".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>