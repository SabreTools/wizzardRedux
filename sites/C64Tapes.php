<?php

// Original code: The Wizard of DATz
$page = "http://c64tapes.org/games_list.php?title=^.&sort=title";

echo "<table>\n";

$content = get_data($page);

preg_match_all("/<tr>(.*?TAP icon.*?)<\/tr>/", utf8_decode($content), $query);
$query = $query[1];
unset($query[0]);

$new = 0;
$old = 0;

foreach ($query as $row)
{
	preg_match_all("/<td.*?>(.*?)<\/td>/", $row, $row);
	$row = $row[1];
	
	$id = $row[0];
	$name = str_replace(array('[', ']'), array('(', ')'), $row[1]);
	$name = trim(strip_tags($name));
	$add = str_replace(array('<a class="year" href="year.php?id=1">[unkn]</a>',
									   ' (', ')', '</a>', '<a', '[', ']'),
						array(null, ', ', null, ')</a>', '(<a', null, null), $row[3]);
	$add = trim(strip_tags($add));

	$dl_page = get_data("http://c64tapes.org/title.php?id=".$id);
	
	preg_match("/<td>Filename \(TAP\): <\/td>(.*?)<\/td>/", utf8_decode($dl_page), $url1);
	$url1 = strip_tags($url1[1]);
	
	preg_match("/<td>Filename \(RAW\): <\/td>(.*?)<\/td>/", utf8_decode($dl_page), $url2);
	$url2 = strip_tags($url2[1]);

	if ($url1 != "")
	{
		$id = 'taps/'.$url1;
		if (!$r_query[$id])
		{
			echo "<tr><td colspan=2>".$name.' '.$add."</td></tr>";
			$found[] = array($name.' '.$add.".zip", "http://c64tapes.org/".$id);
			$new++;
		}
		else
		{
			$old++;
		}
	}

	if ($url2 != "")
	{
		$id = 'raw/'.$url2;
		if (!$r_query[$id])
		{
			echo "<tr><td colspan=2>".$name.' '.$add."</td></tr>";
			$found[] = array($name.' '.$add.".zip", "http://c64tapes.org/".$id);
			$new++;
		}
		else
		{
			$old++;
		}
	}
}

echo "<tr><td>".$page."</td><td>Found new: ".$new.", old: ".$old."</tr>\n</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>