<?php

// Original code: The Wizard of DATz

print "<pre>";

$dirs = array("http://trd.speccy.cz/gs.php");

$query = implode('', file("http://trd.speccy.cz/skin/g_top.htm"));
$query = explode('href="', $query);
array_splice($query, 0, 1);
foreach ($query as $url)
{
	$url = explode('"', $url);
	$dirs[] = "http://trd.speccy.cz".$url[0];
}

$query = implode('', file("http://trd.speccy.cz/demos/top.htm"));
$query = explode('href="', $query);
array_splice($query, 0, 1);
foreach ($query as $url)
{
	$url = explode('"', $url);
	if ($url[2] == "demozdown")
	{
		$dirs[] = "http://trd.speccy.cz/demos/".$url[0];
	}
	else
	{
		$query2 = implode('', file("http://trd.speccy.cz/demos/".$url[0]));
		$query2 = explode('href="', $query2);
		array_splice($query2, 0, 1);
		foreach ($query2 as $url2)
		{
			$url2 = explode('"', $url2);
			$dirs[] = "http://trd.speccy.cz/demos/".$url2[0];
		}
	}
}

$query = implode('', file("http://trd.speccy.cz/games.php?l=down"));
$query = explode('games.php?', $query);
array_splice($query, 0, 1);
foreach ($query as $url)
{
	$url = explode('"', $url);
	$dirs[] = "http://trd.speccy.cz/games.php?".$url[0];
}

foreach ($dirs as $dir)
{
	print "load: ".$dir."\n";
	$query = implode('', file($dir));
	$query = ru2lat($query);
	$query = str_replace("&nbsp;", " ", $query);
	$query = str_replace("\n", " ", $query);
	$query = str_replace("\r", " ", $query);
	$query = explode('<tr bgcolor="', $query);
	array_splice($query, 0, 1);

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		$row = explode('<td', $row);
		$url = explode('href="', $row[1]);
		$url = explode('"', $url[1]);
		$url = $url[0];

		if (!$url)
		{
			array_splice($row, 0, 1);
			$url = explode('href="', $row[1]);
			$url = explode('"', $url[1]);
			$url = $url[0];
		}

		if ($url)
		{
			$ext = explode('.', $url);
			$ext = $ext[count($ext) - 1];
	
			$title = trim(strip_tags('<td'.$row[1]));
			$info = array();
	
			for ($x = 2; $x < 4; $x++)
			{
				$temp = trim(strip_tags('<td'.$row[$x]));
				if ($temp && $temp != "author" && $temp != "n/a")
				{
					$info[] = $temp;
				}
			}
	
			if ($info)
			{
				$title = $title." (".implode(") (", $info).")";
			}
	
			if (!$r_query[$url])
			{
				$found[$url] = array($title.".".$ext, $url);
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}

	print "new: ".$new.", old: ".$old."\n";
}

$dirs = array(
	"http://trd.speccy.cz/gs.php",
	"http://trd.speccy.cz/system.php",
);

foreach ($dirs as $dir)
{
	print "load: ".$dir."\n";
	$query = implode('', file($dir));
	$query = ru2lat($query);
	$query = explode("\n", $query);
	array_splice($query, 0, 1);

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		if (substr($row, 0, 3 )== "<a ")
		{
			$url = explode('href="', $row);
			$url = explode('"', $url[1]);
			$url = $url[0];

			$ext = explode('.', $url);
			$ext = $ext[count($ext) - 1];

			$title = explode('>', $row);
			$add = explode('<', $title[2]);
			$title = explode('<', $title[1]);
			$add = trim($add[0]);
			$title = trim($title[0]);
	
			if (!$r_query[$url])
			{
				$found[$url] = array($title." (".$add.").".$ext, $url);
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}

	print "new: ".$new.", old: ".$old."\n";
}

$dirs = array(
	"http://trd.speccy.cz/sbor.php",
	"http://trd.speccy.cz/book.php",
);

foreach ($dirs as $dir)
{
	print "load: ".$dir."\n";
	$query = implode('', file($dir));
	$query = ru2lat($query);
	$query = explode("<li>", $query);
	array_splice($query, 0, 1);

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		$url = explode('href="', $row);
		$url = explode('"', $url[1]);
		$url = $url[0];

		if ($url)
		{
			$ext = explode('.', $url);
			$ext = $ext[count($ext) - 1];
	
			$title = explode('>', $row);
			$add = explode('<', $title[2]);
			$title = explode('<', $title[1]);
			$add = trim($add[0]);
			$title = trim($title[0]);
	
			if (!$r_query[$url])
			{
				$found[$url] = Array($title." (".$add.").".$ext, $url);
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}

	print "new: ".$new.", old: ".$old."\n";
}

$dir = "http://trd.speccy.cz/press.php";
print "load: ".$dir."\n";
$queryd = implode('', file($dir));
$queryd = explode("/press.php?l=", $queryd);
array_splice($queryd, 0, 1);

foreach ($queryd as $rowd)
{
	$rowd = explode('"', $rowd);
	$rowd = $rowd[0];

	$dir = "http://trd.speccy.cz/press.php?l=".$rowd;
	print "load: ".$dir."\n";

	$query = implode('', file($dir));
	$query = ru2lat($query);
	$query = str_replace("&nbsp;", " ", $query);
	$query = str_replace("\n"," ",$query);
	$query = str_replace("\r", " ", $query);
	$query = explode('<tr bgcolor="', $query);
	array_splice($query, 0, 1);

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		$row = explode('<td', $row);
		$title = trim(strip_tags('<td'.$row[1]));

		$DLs = explode('<a href="', $row[2]);
		array_splice($DLs, 0, 1);

		foreach ($DLs as $DL)
		{
			$url = explode('"', $DL);
			$url = $url[0];
			$ext = explode('.', $url);
			$ext = $ext[count($ext) - 1];

			$issue = explode('>', $DL);
			$issue = explode('<', $issue[1]);
			$issue = $issue[0];

			if (!$r_query[$url])
			{
				$found[$url] = array($title." Issue ".$issue.".".$ext, $url);
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}

	print "new: ".$new.", old: ".$old."\n";
}


print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"http://trd.speccy.cz/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>