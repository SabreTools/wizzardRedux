<?php

// Original code: The Wizard of DATz

$base_URL="http://www.stadium64.com/";
//$base_URL="http://s64.emuunlim.org/";

print "<pre>check folders:\n\n";

$dir = "/gameinfos/gameinfos.htm";
print "load: ".$dir."\n";

$query = get_data($base_URL.$dir);
$query = explode("\n<A HREF=\"", str_replace('<a href="', '<A HREF="', $query));
$query[0] = null;

foreach ($query as $row)
{
	if ($row)
	{
		$new = 0;
		$old = 0;
		$row = explode('"', $row);
		$title = explode("\n", $row[1]);
		$title = trim(strip_tags('<a href="#"'.$title[0]));
		$row = $row[0];
		print "load: ".$row."\n";

		$query2 = get_data($base_URL."/gameinfos/".$row);
		$query2 = explode("\n<A HREF=\"", str_replace('<a href="', '<A HREF="', $query2));
		$query2[0] = null;

		$dir = explode('/', "gameinfos/".$row);
		$dir[count($dir) - 1] = null;
		$dir = implode('/', $dir);
	
		foreach ($query2 as $row2)
		{
			if ($row2)
			{
				$row2 = explode('"', $row2);
				$title2 = explode('<', $row2[1]);
				$title2 = explode('>', $title2[0]);
				$title2 = $title2[1];
				$row2 = $row2[0];
				if (substr($row2, -4) == '.zip')
				{
					$tempdir = explode('/', $dir.$row2);

					for ($x = 0; $x < count($tempdir); $x++)
					{
						if ($tempdir[$x] == '..')
						{
							array_splice($tempdir, $x - 1, 2);
							$x = $x - 2;
						}
					}

					$tempdir = implode('/', $tempdir);

					if (!$r_query[$tempdir])
					{
						$found[] = array($tempdir, $title." (".$title2.").zip");
						$r_query[$tempdir] = true;
						$new++;
					}
					else
					{
						$old++;
					}
				}
			}
		}
		print "new: ".$new.", old: ".$old."\n";
	}
}

$dirs = array(
	'games/american/american.htm',
	'games/athletics/athletics.htm',
	'games/baseball/baseball.htm',
	'games/basketball/basketball.htm',
	'games/bowling/bowling.htm',
	'games/boxing/boxing.htm',
	'games/cricket/cricket.htm',
	'games/cyclesports/cyclesports.htm',
	'games/darts/darts.htm',
	'games/fighting/fighting.htm',
	'games/fighting/fighting1.htm',
	'games/football/football.htm',
	'games/football/football1.htm',
	'games/football/football2.htm',
	'games/football/football3.htm',
	'games/football/football4.htm',
	'games/football/football5.htm',
	'games/formula1/formula1.htm',
	'games/formula1/formula2.htm',
	'games/golf/golf.htm',
	'games/golf/minigolf.htm',
	'games/icehockey/icehockey.htm',
	'games/mixedsports/horsesports.htm',
	'games/mixedsports/mixedsports.htm',
	'games/mixedsports/mixedsports1.htm',
	'games/mixedsports/shooting.htm',
	'games/mixedsports/squash.htm',
	'games/motorcycling/motorcycling.htm',
	'games/motorcycling/motorcycling1.htm',
	'games/multievents/multievents.htm',
	'games/multievents/multievents1.htm',
	'games/racing/racing.htm',
	'games/racing/racing1.htm',
	'games/racing/racing2.htm',
	'games/rugby/rugby.htm',
	'games/skateboard/skateboard.htm',
	'games/snooker/snooker.htm',
	'games/tabletennis/tabletennis.htm',
	'games/tennis/tennis.htm',
	'games/volleyball/volleyball.htm',
	'games/watersports/watersports.htm',
	'games/weirdsports/weirdsports.htm',
	'games/weirdsports/weirdsports1.htm',
	'games/wintersports/wintersports.htm',
	'games/wrestling/wrestling.htm',
	'originals/cartridges.htm',
	'originals/disks.htm',
	'originals/tapesab.htm',
	'originals/tapesce.htm',
	'originals/tapesfh.htm',
	'originals/tapesio.htm',
	'originals/tapespr.htm',
	'originals/tapess.htm',
	'originals/tapesty.htm',
);

foreach ($dirs as $dir)
{
	if ($dir)
	{
		$query = get_data($base_URL.$dir);
		$query = explode('<table border=0 cellspacing=0 cellpadding=0><center>',
				str_replace('""', '"',
						str_replace(array("\r\n", "<a href=", 'Number Of Players:', ': <font color="ffff40">Unknown'),
								array(null, "<a href=\"", null, null),
								$query)
						)
				);
		$query[0] = null;
	
		$dir = explode('/', $dir);
		$dir[count($dir) - 1] = null;
		$dir = implode('/', $dir);
	
		$new = 0;
		$old = 0;
	
		foreach ($query as $row)
		{
			if ($row)
			{
				$url = explode('.zip', $row);
				if ($url[1])
				{
					$title = explode('<', $url[1]);
					$title = explode('>', $title[0]);
					$title = $title[1];
					$url = explode('"', $url[0]);
					$url = $dir.$url[count($url) - 1].'.zip';
					$infos = explode(': <font color="ffff40">', $row);
					$infos[0] = null;
					$add = array();
	
					foreach ($infos as $info)
					{
						if ($info)
						{
							$info = explode('</font>', $info);
							$add[] = str_replace(array('(', ')', '/'), array('', '', ', '), trim(strip_tags($info[0])));
						}
					}
	
					if ($add)
					{
						$title = $title." (".implode(") (", $add).")";
					}
	
					if (!$r_query[$url])
					{
						$found[] = array($url, $title.".zip");
						$r_query[$url] = true;
						$new++;
					}
					else
					{
						$old++;
					}
				}
			}
		}
	
		print "new: ".$new.", old: ".$old."\n";
	}
}

print "\nnew urls:\n\n";

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print $row[0]."\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=\"".$base_URL.$row[0]."\" target=_blank>".$row[1]."</a>\n";
}

print "</td></tr></table>";


?>