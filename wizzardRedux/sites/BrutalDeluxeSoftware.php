<?php

// Original code: The Wizard of DATz

echo "<table>\n";

$dir = "http://brutaldeluxe.fr/projects/cassettes/index.html";
echo "<tr><td colspan=2>".$dir."</td></tr>";
$query = get_data($dir);

preg_match_all("/<a href=\s*\"\.\.\/\.\.\/(projects\/.*?)\".*?>(.*?)<\/a><\/li>/s", $query, $query);

$newrows = array();
for ($index = 1; $index < sizeof($query[0]); $index++)
{
	$newrows[] = array($query[1][$index], $query[2][$index]);
}

foreach ($newrows as $row)
{
	$url = $row[0];
	$title = $row[1];

	$new = 0;
	$old = 0;

	$dir = "http://brutaldeluxe.fr/".$url;
	echo "<tr><td>".$dir."</td>";
	$queryb = get_data($dir);
	
	preg_match_all("/<tr>(.*?)<\/tr>/s", $queryb, $queryb);
	unset($queryb[1][0]);
	$dir = dirname($dir)."/";
	
	foreach ($queryb[1] as $row)
	{
		preg_match_all("/<td>(.*?)<\/td>/s", $row, $items);
		$items = $items[1];
		
		preg_match("/(.*?)<br \/>/s", $items[1], $titleb);
		
		$titleb = trim($titleb[1]);
		$titlec = trim($items[0]);
		
		$titleb = $titleb." (".$title.") (".$titlec.")";
		
		preg_match_all("/<a href=\"(.*?)\">/", $items[3], $DLs);
		$DLs = $DLs[1];
		
		foreach ($DLs as $DL)
		{
			$DL = $dir.$DL;
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
    echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";
}

$dirs = array(
	"http://brutaldeluxe.fr/products/france/",
	"http://brutaldeluxe.fr/crack/",
);

foreach ($dirs as $dir)
{
	listDir($dir);
}
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='http://www.apple-iigs.info/".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

function listDir($dir)
{
	GLOBAL $found, $r_query;
	
	$query = get_data($dir);
	
	preg_match_all("/<a href=\"(.*?)\">(.*?)<\/a>/is", $query, $query);
	$newrows = array();
	for ($index = 5; $index < sizeof($query[0]); $index++)
	{
		$newrows[] = array($query[1][$index], $query[2][$index]);
	}

	$new = 0;
	$old = 0;

	foreach ($newrows as $row)
	{
		$url = $dir.$row[0];
		
		if (substr($url, -1) == '/')
		{
			listDir($url);
			continue;
		}
		
		preg_match("/^.*\/(.*?)\/(.*?)\.(.*?)$/", $url, $title);
		
		$subdir = $title[1];
		$ext = $title[3];
		$title = $title[2];
		$cutleng = strlen($subdir."_");

		if (substr($title, 0, $cutleng) == $subdir."_")
		{
			$title = substr($title, $cutleng);
		}

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

	echo "<tr><td>".$dir."</td><td>Found new: ".$new.", old: ".$old."</tr>\n";
}

?>