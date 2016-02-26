<?php
print "<pre>";

if ($_GET["sub"] == 'list')
{
	$r_query = array_flip($r_query);

	$searchs = Array(	
		'Published:',
		'Musician:',
		'Programmer:',
		'Language:',
		'Cracker/Cruncher:',
		'Extra Features:',
		'PAL/NTSC:',
	);

	foreach ($r_query as $side_id)
	{
		$query = implode('', file("http://www.gamebase64.com/game.php?id=".$side_id));

		$title = explode('<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td width=50%><font size="4"><b>', $query);
		$title = explode('</b>', $title[1]);
		$title = $title[0];

		if ($title != '<br>Ooops, something wrent wrong!!')
		{		
			$info = array();
			$last = '';

			foreach ($searchs as $search)
			{
				$temp = explode($search.'</font>', $query);
				$temp = explode('</font>', $temp[1]);
				$temp = trim(strip_tags($temp[0]));
				$temp = str_replace(array('(', ')', '[', ']', '?', ', Unknown', ' / '), array('', '', '', '', 'x', '', ', '), $temp);
				if ($temp && $temp != 'Unknown' && $temp != 'None' && $last != $temp)
				{
					$info[] = $temp;
				}
				$last = $temp;
			}

			print $side_id."\t".$title." (".implode(") (", $info).")\n";
		}
	}
}
elseif($_GET["sub"] == 'ids')
{
	$r_query = array_flip($r_query);
	$start = $r_query[-1];

	print "Search for new uploads\n";

	$searchs = array(
		'Published:',
		'Musician:',
		'Programmer:',
		'Language:',
		'Cracker/Cruncher:',
		'Extra Features:',
		'PAL/NTSC:',
	);

	for ($x = $start; $x < $start + 50; $x++)
	{
		$query=implode('', file("http://www.gamebase64.com/game.php?id=".$x));

		$title = explode('<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td width=50%><font size="4"><b>', $query);
		$title = explode('</b>', $title[1]);
		$title = $title[0];

		if ($title != '<br>Ooops, something wrent wrong!!')
		{		
			$info = array();
			$last = '';

			foreach ($searchs as $search)
			{
				$temp = explode($search.'</font>', $query);
				$temp = explode('</font>', $temp[1]);
				$temp = trim(strip_tags($temp[0]));
				$temp = str_replace(array('(', ')', '[', ']', '?', ', Unknown', ' / '), array('', '', '', '', 'x', '', ', '), $temp);
				if ($temp && $temp != 'Unknown' && $temp != 'None' && $last != $temp)
				{
					$info[] = $temp;
				}
				$last = $temp;
			}
	
			$url = explode('GB64-Filename:</font><br><font size="2"><b>', $query);
			$url = explode('</b>', $url[1]);
			$url = $url[0];

			if ($url != 'None')
			{
				$found[] = array($url, $title." (".implode(") (", $info).").zip");
			}

			$last = $x;
		}
		else
		{
			print $x."\tOoops, something wrent wrong!!\n";
		}
	}

	if ($last)
	{
		$start=$last+1;
	}

	print "<table><tr><td><pre>";

	foreach ($found as $row)
	{
		print $row[0]."\n";
	}

	print "</td><td><pre>";

	foreach ($found as $row)
	{
		print "<a href=\"http://gamebase64.hardabasht.com/games/".$row[0]."\" target=_blank>".$row[1]."</a>\n";
	}
	print "</td></tr></table>";

	print "\nnext startnr\t<a href=?action=onlinecheck&source=Gamebase64&sub=ids&start=".($start).">".$start."</a>\n";
}
elseif ($_GET["sub"] == 'dir')
{
	$r_query = implode ('', file("../sites/".$source."_urls.txt"));
	$r_query = explode ("\r\n", "\r\n".$r_query);

	$dirs = array();
	$files = array();

	foreach ($r_query as $row)
	{
		$dir = explode("/", $row);
		if ($dir[1])
		{
			$dirs[$dir[0]] = $dir[0];
			$files[$dir[1]] = true;
		}
    }
    
	$main="http://gamebase64.hardabasht.com/games/";

	print "\nload:".$main."\n";

	sort($dirs);

	$searchs = array(
		'Published:',
		'Musician:',
		'Programmer:',
		'Language:',
		'Cracker/Cruncher:',
		'Extra Features:',
		'PAL/NTSC:',
	);

	foreach ($dirs as $row)
	{
		$new = 0;
		$old = 0;

		print "load:".$main.$row."\n";

		$query2 = implode('', file($main.$row));
		$query2 = explode('<li><a href="', $query2);
		$query2[0] = null;
		$query2[1] = null;

		foreach ($query2 as $url)
		{
			if ($url)
			{
				$url = explode('"', $url);
 				$url = $url[0];

				if (!$files[$url])
				{
					$id = explode('_', $url);
	 				$id = $id[1];
	
					$query = implode('', file("http://www.gamebase64.com/game.php?id=".$id));
			
					$title = explode('<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td width=50%><font size="4"><b>', $query);
					$title = explode('</b>', $title[1]);
					$title = $title[0];
	
					$info = array();
					$last = '';
		
					foreach ($searchs as $search)
					{
						$temp = explode($search.'</font>', $query);
						$temp = explode('</font>', $temp[1]);
						$temp = trim(strip_tags($temp[0]));
						$temp = str_replace(array('(', ')', '[', ']', '?', ', Unknown', ' / '), array('', '', '', '', 'x', '', ', '), $temp);
						if ($temp && $temp != 'Unknown' && $temp != 'None' && $last != $temp)
						{
							$info[] = $temp;
						}
						$last = $temp;
					}

					$found[] = array($row."/".$url, $title." (".implode(") (", $info).").zip");
					$new++;
				}
				else
				{	
					$old++;
				}
			}
		}

		print "found new ".$new.", old ".$old."\n";
	}

	print "<table><tr><td><pre>";

	foreach ($found as $row)
	{
		print "<a href=\"http://gamebase64.hardabasht.com/games/".$row[0]."\" target=_blank>".$row[0]."</a>\n";
	}		
	
	print "</td><td><pre>";
	
	foreach ($found as $row)
	{
		print "<a href=\"http://gamebase64.hardabasht.com/games/".$row[0]."\" target=_blank>".$row[1]."</a>\n";
	}

	print "</td></tr></table>";
}
else
{
	print 	"<a href=?action=onlinecheck&source=Gamebase64&sub=ids>ids</a>\n".
			"<a href=?action=onlinecheck&source=Gamebase64&sub=dir>dir</a>\n".
			"<a href=?action=onlinecheck&source=Gamebase64&sub=list>list</a> (to get the title by IDs)\n";
}
?>