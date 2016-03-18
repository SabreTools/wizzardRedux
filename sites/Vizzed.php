<?php

// Original code: The Wizard of DATz

$systems = array(
	array('all',	'ALL'),

	array('appleii',	'Apple - II'),
	array('a2600',		'Atari - 2600'),
	array('a5200',		'Atari - 5200'),
	array('a7800',		'Atari - 7800'),
	array('aj',			'Atari - Jaguar'),
	array('al',			'Atari - Lynx'),
	array('WS',			'Bandai - Wonderswan'),
	array('wsc',		'Bandai - Wonderswan Color'),
	array('cv',			'Coleco - ColecoVision'),
	array('c64',		'Commodore - 64'),
	array('intv',		'Matel - Intellivision'),
	array('msx',		'Microsoft - MSX'),
	array('msx2',		'Microsoft - MSX 2'),
	array('pcfx',		'NEC - PC-FX'),
	array('sgfx',		'NEC - SuperGrafx'),
	array('tg',			'NEC - Turbo Grafx'),
	array('tgcd',		'NEC - Turbo Grafx CD'),
	array('fds',		'Nintendo - Famicom Disk System'),
	array('gb',			'Nintendo - Game Boy'),
	array('gba',		'Nintendo - Game Boy Advance'),
	array('gbc',		'Nintendo - Game Boy Color'),
	array('nes',		'Nintendo - NES'),
	array('n64',		'Nintendo - Nintendo 64'),
	array('pm',			'Nintendo - Pokemon Mini'),
	array('snes',		'Nintendo - Super Nintendo'),
	array('vb',			'Nintendo - Virtual Boy'),
	array('cdi',		'Philips - CD-i'),
	array('mo2',		'Philips - Odyssey 2'),
	array('ngcd',		'SNK - Neo Geo CD'),
	array('ngp',		'SNK - Neo Geo Pocket'),
	array('ngpc',		'SNK - Neo Geo Pocket Color'),
	array('32x',		'Sega - 32X'),
	array('scd32x',		'Sega - 32X CD'),
	array('dc',			'Sega - DreamCast'),
	array('gg',			'Sega - Game Gear'),
	array('gen',		'Sega - Genesis'),
	array('sms',		'Sega - Master System'),
	array('scd',		'Sega - Mega CD'),
	array('sp',			'Sega - Pico'),
	array('ss',			'Sega - Saturn'),
	array('sc3',		'Sega - SC-3000'),
	array('sg1',		'Sega - SG-1000'),
	array('psx',		'Sony - Playstation'),
	array('sps',		'Sony - PocketStation'),
);

print "<pre>";

if (!$_GET["type"])
{
	print "load <a href=?page=onlinecheck&source=".$_GET["source"]."&type=full>full</a>\n";
	print "load <a href=?page=onlinecheck&source=".$_GET["source"]."&type=system>system</a>\n";
	print "load <a href=?page=onlinecheck&source=".$_GET["source"]."&type=full2>full proxy</a>\n";
	print "load <a href=?page=onlinecheck&source=".$_GET["source"]."&type=system2>system proxy</a>\n";
}
elseif ($_GET["type"] == "full2")
{
	$r_query = array_flip($r_query);
	$start = $r_query[0];
	unset($r_query[0]);
	$r_query = array_flip($r_query);

	print "Search for new uploads\n\n<table>";

	for ($x = $start; $x < $start + 25; $x++)
	{
		if ($r_query[$x.""])
		{
			print "<tr><td></td><td>reject ".$x.": allready loaded</td></tr>\n";
			$last = $x;
        }
        else
        {
			$query = file_get_contents("http://two.webproxy.at/surf/browse.php?u=".urlencode("http://www.vizzed.com/playonlinegames/play.php?id=".$x)."&b=4", false,
						stream_context_create(array(
								'http' => array(
								'method' => "GET",
								'header' => "Referer: http://two.webproxy.at/surf/browse.php?u=http%3A%2F%2Fwww.vizzed.com\r\n"
			))));

			if (strrpos($query, "Game doesn't exist."))
			{
				print "<tr><td></td><td>reject ".$x.": Game doesn't exist</td></tr>\n";
			}
			elseif (strrpos($query, "This game is not playable"))
			{
				print "<tr><td></td><td>reject ".$x.": This game is not playable</td></tr>\n";
			}
			elseif(strrpos($query,"You cannot play Playstation games"))
			{
				print "<tr><td>".$x."</td><td><a href=http://two.webproxy.at/surf/browse.php?u=".urlencode("http://www.vizzed.com/playonlinegames/play.php?id=".$x)."&b=4>Playstation game ".$x."</a></td></tr>\n";
				$last = $x;
			}
			else
			{
				$gametitle = explode('<title>', $query);
				$gametitle = explode('</title>', $gametitle[1]);
				$gametitle = trim($gametitle[0]);
			
				$dom = "http://www.vizzed.net/";

				$url = explode('"http://www.vizzed.net/', $query);
				$url = explode('"', $url[1]);

				if (!$url[0])
				{
					$dom ="http://www.vizzed.co/";
					$url = explode('"http://www.vizzed.co/', $query);
					$url = explode('"', $url[1]);
				}

				if (!$url[0])
				{
					$dom = "http://www.get-your-rom.com/";
					$url = explode('"http://www.get-your-rom.com/', $query);
					$url = explode('"', $url[1]);
				}

				$url = str_replace('#', '%23', $url[0]);

				$ext = explode('.', $url);
				$ext = $ext[count($ext) - 1];

				print "<tr><td>".$x."</td><td><a href=\"".$dom.$url."\">".$gametitle.".".$ext."</a></td></tr>\n";
				if (!$url)
				{
					print "error".$x ;
					break;
				}

				$last = $x;
			}
		}
	}

	if ($last)
	{
		$start = $last + 1;
	}

	print "</table>\nnext startnr\t<a href=?page=onlinecheck&source=".$_GET["source"]."&type=full2&start=".($start).">".$start."</a>";
	if ($last != $x)
	{
		print "\nignore last startnr\t<a href=?page=onlinecheck&source=".$_GET["source"]."&type=full2&start=".($x).">".($x)."</a>";
	}
}
elseif ($_GET["type"] == "full")
{
	$r_query = array_flip($r_query);
	$start = $r_query[0];
	unset($r_query[0]);
	$r_query = array_flip($r_query);

	print "Search for new uploads\n\n<table>";

	for ($x = $start; $x < $start + 25; $x++)
	{
		if ($r_query[$x.""])
		{
			print "<tr><td></td><td>reject ".$x.": allready loaded</td></tr>\n";
			$last = $x;
        }
        else
        {
			$query = trim(implode ('', file("http://www.vizzed.com/playonlinegames/play.php?id=".$x)));

			if ($query == "Game doesn't exist.")
			{
				print "<tr><td></td><td>reject ".$x.": Game doesn't exist</td></tr>\n";
			}
			elseif ($query == "This game is not playable")
			{
				print "<tr><td></td><td>reject ".$x.": This game is not playable</td></tr>\n";
			}
			elseif ($query == "You cannot play Playstation games.  Either become a staff member or buy the Playstation item from the Item Shop.")
			{
				print "<tr><td>".$x."</td><td><a href=http://www.vizzed.com/playonlinegames/play.php?id=".$x.">Playstation game ".$x."</a></td></tr>\n";
				$last = $x;
			}
			else
			{
				$gametitle = explode('<title>', $query);
				$gametitle = explode('</title>', $gametitle[1]);
				$gametitle = trim($gametitle[0]);

				$dom = "http://www.vizzed.net/";

				$url = explode('"http://www.vizzed.net/', $query);
				$url = explode('"', $url[1]);

				if (!$url[0])
				{
					$dom ="http://www.vizzed.co/";
					$url = explode('"http://www.vizzed.co/', $query);
					$url = explode('"', $url[1]);
				}

				if (!$url[0])
				{
					$dom = "http://www.get-your-rom.com/";
					$url = explode('"http://www.get-your-rom.com/', $query);
					$url = explode('"', $url[1]);
				}

				$url =str_replace('#', '%23', $url[0]);

				$ext = explode('.', $url);
				$ext = $ext[count($ext) - 1];
				
				print "<tr><td>".$x."</td><td><a href=\"".$dom.$url."\">".$gametitle.".".$ext."</a></td></tr>\n";
				if (!$url)
				{
					print "error".$x ;
					break;
				}

				$last = $x;
			}
		}
	}

	if ($last)
	{
		$start = $last + 1;
	}

	print "</table>\nnext startnr\t<a href=?page=onlinecheck&source=".$_GET["source"]."&type=full&start=".($start).">".$start."</a>";
	if ($last != $x)
	{
		print "\nignore last startnr\t<a href=?page=onlinecheck&source=".$_GET["source"]."&type=full&start=".($x).">".($x)."</a>";
	}
}
elseif ($_GET["type"] == "system")
{
	if ($_GET["system"])
	{
		print "load: ".$_GET["system"]."\n\n<table>";
		
		if ($_GET["page"])
		{
			$page = $_GET["page"];
		}
		else
		{
			$page = 1;
		}
		
		$counterArray = array();

		// http://www.vizzed.com/p/?page=1&sort=1&system=dc
		$query = get_data ("http://www.vizzed.com/p/?page=".$page."&sort=id&system=".$_GET["system"]."&search=".$_GET["search"]);

		$counter = explode('<b>Page ', $query);
		$counter = explode('</b>', $counter[1]);
		$counterArray[] = $counter[0];

		$query = explode('</form>', $query);
		$query = explode('<script', $query[3]);

		$query = explode("href='../play/", $query[0]);
		array_splice($query, 0, 1);

		if (!$query)
		{
			return;
		}

		$found = false;
		$foundArray = array();

		foreach ($query as $row)
		{
			$row = explode("'", $row);
			$row = explode("-", $row[0]);
			$row = $row[count($row)-2];

			if ($foundArray[$row] != 1)
			{
				$foundArray[$row] = 1;

				if ($r_query[$row] && $_GET["ig"] == "")
				{
					print "<tr><td></td><td>reject ".$row.": allready loaded</td></tr>\n";
		        }
		        else
		        {
					$query_s = trim(implode ('', file("http://www.vizzed.com/playonlinegames/play.php?id=".$row)));

					if ($query_s == "Game doesn't exist.")
					{
						print "<tr><td></td><td>reject ".$row.": Game doesn't exist</td></tr>\n";
					}
					elseif($query_s == "This game is not playable")
					{
						print "<tr><td></td><td>reject ".$row.": This game is not playable</td></tr>\n";
					}
					elseif  ($query_s == "You cannot play Playstation games.  Either become a staff member or buy the Playstation item from the Item Shop."){
						print "<tr><td>".$x."</td><td><a href=http://www.vizzed.com/playonlinegames/play.php?id=".$x.">Playstation game ".$x."</a></td></tr>\n";
						$last = $x;
					}
					else
					{
						$gametitle = explode('<title>', $query_s);
						$gametitle = explode('</title>', $gametitle[1]);
						$gametitle = trim($gametitle[0]);
		
					
						$dom = "http://www.vizzed.net/";
		
						$url = explode('"http://www.vizzed.net/', $query_s);
						$url = explode('"', $url[1]);
		
						if (!$url[0])
						{
							$dom = "http://www.vizzed.co/";
							$url = explode('"http://www.vizzed.co/', $query_s);
							$url = explode('"', $url[1]);
						}
		
						if (!$url[0])
						{
							$dom = "http://www.get-your-rom.com/";
							$url = explode('"http://www.get-your-rom.com/', $query_s);
							$url = explode('"', $url[1]);
						}
		
						$url = str_replace('#', '%23', $url[0]);
		
						$ext = explode('.', $url);
						$ext = $ext[count($ext) - 1];
						
						print "<tr><td>".$row."</td><td><a href=\"".$dom.$url."\">".$gametitle.".".$ext."</a></td></tr>\n";
						$found = true;
					}
				}
			}
		}

		print "</table><br>";
		
		print_r ($counterArray);
	
		if ($query)
		{
			print "\nnext page <a href=?page=onlinecheck&source=".$_GET["source"]."&type=system&ig=".$_GET["ig"]."&system=".$_GET["system"]."&page=".($page+1).">".($page+1)."</a>";
	
			if (false)
			{
				print "<script>
				window.open('?page=onlinecheck&source=".$_GET["source"]."&type=system&ig=".$_GET["ig"]."&system=".$_GET["system"]."&page=".($page+1)."','_blank');
				</script>";
			}
		}
	}
	else
	{
		foreach ($systems as $system)
		{
	   		print "<a href=?page=onlinecheck&source=".$_GET["source"]."&type=system&system=".$system[0].">".$system[1]."</a>\n";
		}
		
		print"<form method=\"get\" action=\"?\">
		<input name=action value=onlinecheck type=hidden>
		<input name=source value=".$_GET["source"]." type=hidden>
		<input name=type value=system type=hidden>
<input name=search><select name=system>";

		foreach ($systems as $system)
		{
	   		print "<option value=".$system[0].">".$system[1]."</option>";
		}

		print "</select><input type=submit></form>";
	}
}
elseif ($_GET["type"] == "system2")
{
	if ($_GET["system"])
	{
		print "load: ".$_GET["system"]."\n\n<table>";
		
		if ($_GET["page"])
		{
			$page = $_GET["page"];
		}
		else
		{
			$page = 1;
		}
		
		$counterArray = array();

		$query = get_data("http://two.webproxy.at/surf/browse.php?u=".urlencode("http://www.vizzed.com/playonlinegames/index.php?page=".$page."&sort=id&system=".$_GET["system"]."&search=".$_GET["search"])."&b=4");

		$counter = explode('<b>Page ', $query);
		$counter = explode('</b>', $counter[1]);
		$counterArray[] = $counter[0];

		$query = explode('<b>Search Results </b>', $query);
		$query = explode('::', $query[1]);
		$query = explode("href='/surf/browse.php?u=http%3A%2F%2Fwww.vizzed.com%2Fplay%2F", $query[0]);
		array_splice($query, 0, 1);
		
		if (!$query)
		{
			return;
		}

		$found = false;

		foreach ($query as $row)
		{
			$row = explode("'", $row);
			$row = explode("-", $row[0]);
			$row = $row[count($row) - 2];

			if ($r_query[$row] && $_GET["ig"] == "")
			{
				print "<tr><td></td><td>reject ".$row.": allready loaded</td></tr>\n";
	        }
	        else
	        {
				if ($_GET["system"] == "psx")
				{
                    $query_s = "You cannot play Playstation games.  Either become a staff member or buy the Playstation item from the Item Shop.";
				}
				else
				{
					$query_s = get_data("http://two.webproxy.at/surf/browse.php?u=".urlencode("http://www.vizzed.com/playonlinegames/play.php?id=".$row)."&b=4");
				}

				if (strrpos($query_s, "Game doesn't exist."))
				{
					print "<tr><td></td><td>reject ".$row.": Game doesn't exist</td></tr>\n";
				}
				elseif (strrpos($query_s,"This game is not playable"))
				{
					print "<tr><td></td><td>reject ".$row.": This game is not playable</td></tr>\n";
				}
				elseif (strrpos($query_s,"You cannot play Playstation games"))
				{
					print "<tr><td>".$row."</td><td><a href=http://two.webproxy.at/surf/browse.php?u=".urlencode("http://www.vizzed.com/playonlinegames/play.php?id=".$row)."&b=4>Playstation game ".$row."</a></td></tr>\n";
					$found = true;
				}
				else
				{
					$gametitle = explode('<title>', $query_s);
					$gametitle = explode('</title>', $gametitle[1]);
					$gametitle = trim($gametitle[0]);
				
					$dom = "http://www.vizzed.net/";
	
					$url = explode('"http://www.vizzed.net/', $query_s);
					$url = explode('"', $url[1]);
	
					if (!$url[0])
					{
						$dom = "http://www.vizzed.co/";
						$url = explode('"http://www.vizzed.co/', $query_s);
						$url = explode('"', $url[1]);
					}
	
					$url = str_replace('#', '%23', $url[0]);
	
					$ext = explode('.', $url);
					$ext = $ext[count($ext) - 1];

					print "<tr><td>".$row."</td><td><a href=\"".$dom.$url."\">".$gametitle.".".$ext."</a></td></tr>\n";
					$found = true;
					
					if (!$url)
					{
						print "error".$x ;
						break;
					}
				}
			}
		}

		print "</table><br>";
		
		print_r($counterArray);

		if ($query)
		{
			print "\nnext page <a href=?page=onlinecheck&source=".$_GET["source"]."&type=system2&ig=".$_GET["ig"]."&system=".$_GET["system"]."&page=".($page+1).">".($page+1)."</a>";
		}
	}
	else
	{
		foreach ($systems as $system)
		{
	   		print "<a href=?page=onlinecheck&source=".$_GET["source"]."&type=system2&system=".$system[0].">".$system[1]."</a>\n";
		}

		print"<form method=\"get\" action=\"?\">
		<input name=action value=onlinecheck type=hidden>
		<input name=source value=".$_GET["source"]." type=hidden>
		<input name=type value=system2 type=hidden>
<input name=search><select name=system>";

		foreach ($systems as $system)
		{
	   		print "<option value=".$system[0].">".$system[1]."</option>";
		}

		print"</select><input type=submit></form>";
	}
}

print "\n\n <a href=?page=onlinecheck&source=".$_GET["source"].">back to top</a>\n";

?>