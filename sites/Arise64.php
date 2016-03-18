<?php

// Original code: The Wizard of DATz

$base_dl_url = "http://arise64.pl/demos/";

$dirs1 = array(
      '_ntsc/',
      '0/',
      'a/',
      'b/',
      'c/',
      'd/',
      'e/',
      'f/',
      'g/',
      'h/',
      'i/',
      'j/',
      'k/',
      'l/',
      'm/',
      'n/',
      'o/',
      'p/',
      'q/',
      'r/',
      's/',
      't/',
      'u/',
      'v/',
      'w/',
      'x/',
      'y/',
      'z/',
);

$dirs2 = array(
      '_magazines/',
);

$dirs3 = array(
      '_unknown/',
);

$dirs4 = array(
      '_parties/',
);

echo "<table>\n";
foreach ($dirs1 as $dir)
{
	if ($dir)
	{
		listDir($dir, 1);
	}
}

foreach ($dirs2 as $dir)
{
	if ($dir)
	{
		listDir($dir, 2);
	}
}

foreach ($dirs3 as $dir)
{
	if ($dir)
	{
		listDir($dir, 3);
	}
}

foreach ($dirs4 as $dir)
{
	if ($dir)
	{
		listDir($dir, 4);
	}
}
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='".$base_dl_url.$row[0]."'>".$row[1]."</a><br/>\n";
}

echo "<br/>\n";

function listDir ($dir, $mode)
{
	GLOBAL $found, $r_query, $base_dl_url;

	$query = get_data($base_dl_url.$dir);
	
	preg_match_all("/<a href=\"(.+?)\">(.+?)<\/a>/", $query, $query);
	$newrows = array();
	for ($index = 5; $index < sizeof($query[0]); $index++)
	{
		$newrows[] = array($query[1][$index], $query[2][$index]);
	}

	$new = 0;
	$old = 0;
	$folder = 0;

	foreach ($newrows as $row)
	{
		$url = $row[0];

		if (substr($url, -1) == '/')
		{
			listDir(str_replace('&amp;', '&', $dir.$url), $mode);
			$folder++;
		}
		else
		{
			if (!$r_query[str_replace('&amp;', '&', $dir.$url)])
			{
				if ($mode == 1)
				{
					$author = explode('/', $dir);
					$author = $author[count($author) - 2];
					$text = $author." (".substr($url, 7, -4).") (".substr($url, 0, 4).")".substr($url, -4);
				}

				if ($mode == 2)
				{
					$author = explode('/', $dir);
					$language = $author[1];
					$author = $author[count($author) - 2];
					$infos = explode('%20'.$author.'%20', $url);
					$text = $author." (".substr($infos[1], 0, -4).") (".$infos[0].") (".$language.")".substr($url, -4);
				}

				if ($mode == 3)
				{
					$text = "Unknown (".substr($url, 7, -4).") (".substr($url, 0, 4).")".substr($url, -4);
				}

				if ($mode == 4)
				{
					$infos = explode('%20by%20', $url);
					$text = substr($infos[1], 0, -4)." (".substr($infos[0], 7).")";

					$info = explode('/', $dir);

					for ($x = 1; $x < count($info) - 1; $x++)
					{
						$text = $text." (".str_replace('%20-%20', ') (', $info[$x]).")";
					}

					$text = $text.substr($url, -4);
				}

				$found[] = array($dir.$url, urldecode($text));
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}
	echo "<tr><td>".$dir."</td><td>Found new: ".$new.", old: ".$old."</tr>\n";
}

?>