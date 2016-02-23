<?php

// Original code: The Wizard of DATz

$systems = array(
	array('2',	'Atari - 2600',		array('G')),
	array('5',	'Atari - 5200',		array('G')),
	array('8',	'Atari - 8 Bit',	array('G','U','D')),
	array('S',	'Atari - ST',		array('G','U','D')),
);

print "<pre>";

foreach ($systems as $system)
{
	foreach ($system[2] as $type)
	{
		$found = true;
		$page = 1;
		$query2 = array();
		$count = 0;

		print "load pages for ".$system[1]." (".$type.") ";

		while ($found)
		{
			$query = implode('', file ("http://www.atarimania.com/pgelstsoft.awp?system = ".$system[0]."&type = ".$type."&dump = 1&step = 200&page = ".$page));
			$query = explode('<tr><td height = "53" width = "373" valign = top><TABLE>', $query);

			if (count($query) == 1)
			{
				$found = false;
				break;
			}

			for($x = 1;$x<count($query);$x++)
			{
				$titel = explode('.html" CLASS = "LienNoirGras">', $query[$x]);
				$titel = explode('<', $titel[1]);
				$titel = trim($titel[0]);

				if (!$titel)
				{
					$titel = explode('.html" CLASS = "LienBlancGras">', $query[$x]);
					$titel = explode('<', $titel[1]);
					$titel = trim($titel[0]);
				}

				$titel2 = explode('" class = "LienNoir">', $query[$x]);
				$titel2 = explode('<', $titel2[1]);
				$titel2 = trim($titel2[0]);

				$date = explode('" class = "LienNoir">', $query[$x]);
				$date = explode('<', $date[3]);
				$date = trim($date[0]);

				$id = explode('.html" CLASS = "', $query[$x]);
				$id = $id[0];
				$url = explode('<A HREF = "', $id);
				$url = $url[count($url)-1];
				$id = explode('_', $id);
				$id = $id[count($id)-1];

				if ($titel2)
				{
					$titel = $titel . " (".str_replace(array('[', ']', '(', ')'), null, $titel2).")";
				}
				if ($date)
				{
					$titel = $titel . " (".$date.")";
				}

				$titel = str_replace(' / ', ', ', $titel);
				$titel = str_replace(':', '-', $titel);
				
				if (!$r_query[$id])
				{
					$query2[] = array($id, $titel, $url);
				}

				$count++;
			}

			$page++;
			Print ".";
		}


		print "\n".($page-1)." pages found, with ".$count." entrys, ".count($query2)." new entrys\n";
		print "<table><tr><td><pre>";

		foreach ($query2 as $row)
		{
			print "<a href = http://www.atarimania.com/".$row[2].".html target = _blank>".$row[0]."</a>\n";
		}

		print "</td><td><pre>";

		foreach ($query2 as $row)
		{
			print "<a href = http://www.atarimania.com/pgedump.awp?id = ".$row[0]." target = _blank>".$row[1].".zip</a>\n";
		}

		print "</td></tr></table>";

		print "\n";
	}
}

?>