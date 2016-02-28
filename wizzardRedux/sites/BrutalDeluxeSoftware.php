<?php

// Original code: The Wizard of DATz

print "<pre>";

$dir = "http://brutaldeluxe.fr/projects/cassettes/index.html";
print "load: ".$dir."\n";
$query = get_data($dir);
$query = preg_replace('/\s+/', " ", $query);
$query = explode('name="current">Apple Cassettes Tapes</a>', $query);
$query = explode('</ul>', $query[1]);
$query = explode('<li><a href= "../../', $query[0]);
array_splice($query, 0, 1);

foreach ($query as $row)
{
	$url = explode('"', $row);
	$url = $url[0];
	$title = explode('<', $row);
	$title = explode('>', $title[0]);
	$title = $title[1];

	$new = 0;
	$old = 0;

	$dir = "http://brutaldeluxe.fr/".$url;
	print "load: ".$dir."\n";
	$queryb = get_data($dir);
	$queryb = preg_replace('/\s+/', " ", $queryb);
	$queryb = explode('<tr>', $queryb);
	array_splice($queryb, 0, 1);

	$dir = explode('/', $dir);
	$dir[count($dir) - 1] = null;
	$dir = implode('/', $dir);

	foreach ($queryb as $row)
	{
		$row = explode('<td>', $row);
		$titleb = explode("<", $row[2]);
		$titlec = explode("<", $row[1]);
		$titleb = trim($titleb[0])." (".$title.") (".trim($titlec[0]).")";;

		$DLs = explode('<a href=', $row[4]);
		array_splice($DLs, 0, 1);

		foreach ($DLs as $DL)
		{
			$DL = explode('"', $DL);
			$DL = $dir.$DL[1];

			$ext = explode('.', $DL);
			$ext = $ext[count($ext) - 1];

			if (!$r_query[$DL])
			{
				$found[] = array($titleb.".".$ext, $DL);
				$new++;
				$r_query[$DL] = true;
			}
			else
			{
				$old++;
			}

			$notFound = false;
        }

    }

	print "new: ".$new.", old: ".$old."\n";
}

$dirs = array(
	"http://brutaldeluxe.fr/products/france/",
	"http://brutaldeluxe.fr/crack/",
);

foreach ($dirs as $dir)
{
	listDir($dir);
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
	print "<a href=\"".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

function listDir($dir)
{
	GLOBAL $found, $r_query;

	print "load: ".$dir."\n";

	$query = get_data($dir);
	$query = explode('>Parent Directory<', $query);
	if ($query[1])
	{
		$query = $query[1];
	}
	else
	{
		$query=$query[0];
	}
	$query = str_replace(' HREF="', ' href="', $query);
	$query = explode(' href="', $query);
	$query[0] = null;

	$new = 0;
	$old = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $dir.$url[0];

			$title = explode('/', $url);
			$subdir = $title[count($title) - 2];
			$title = $title[count($title) - 1];

			$ext = explode('.', $title);
			$ext = $ext[count($ext) - 1];

			$title = substr($title, 0, -(strlen($ext) + 1));

			$cutleng = strlen($subdir."_");

			if (substr($title, 0, $cutleng) == $subdir."_")
			{
				$title = substr($title, $cutleng);
			}

			if (substr($url, -1) == '/')
			{
				listDir($url);
			}
			else
			{
				if (!$r_query[$url])
				{
					$found[] = array($title." (".$subdir.").".$ext, $url);
					$new++;
				}
				else
				{
					$old++;
				}
			}
		}
	}

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old."\n";
}

?>