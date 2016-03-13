<?php

// Original code: The Wizard of DATz

print "<pre>";

$max = 100000;

echo "<table>\n";
for ($page = 0; $page < $max; $page++)
{
	$dir = "http://www.apple-iigs.info/logiciels.php?arechercher=&begin=".(($page * 10) + 1);
	echo "<tr><td>".$dir."</td><td></td></tr>";
	$query = get_data($dir);
	
	preg_match_all("/detlogiciels\.php\?nom=(.+?)&origine/", $query, $query);
	$query = $query[1];

	$new = 0;
	$old = 0;
	
	$notFound = true;
	
	foreach ($query as $row)
	{
		$dir = "http://www.apple-iigs.info/detlogiciels.php?nom=".urlencode($row);

		echo "<tr><td>".$dir."</td>";

		$queryb = get_data($dir);
		preg_match_all("/<a href='\.\.\/(.+?)'/", str_replace("\r", "", $queryb), $queryb);
		$queryb = $queryb[1];

		foreach ($queryb as $DL)
		{
			$ext = explode('.', $DL);
			$ext = $ext[count($ext) - 1];
			
			if ($r_query[$DL] === NULL)
			{
				echo "new: ".$DL."<br/>\n";
				$found[] = array($row.".".$ext, $DL);
				$new++;
				$r_query[$DL] = true;
			}
			else
			{
				echo "old: ".$DL."<br/>\n";
				$old++;
			}
		}
		$notFound = false;
	}

	if ($notFound)
	{
		$page = $max;
	}

	echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";
	die();
}

$dirs = array(
	"http://www.apple-iigs.info/logicielspartitions.php",
	"http://www.apple-iigs.info/logicielsgoldengrail.php",
);

foreach ($dirs as $dir)
{
	echo "<tr><td>".$dir."</td>";
	
	$new = 0;
	$old = 0;
	
	$queryb = get_data($dir);
	
	preg_match_all("/<td align=left>(.+?)<\/td>\r?\n?\s*<td><a href=\"\.\.\/(.*)\">/", str_replace("\r", "", $queryb), $queryb);
	
	$newrows = array();
	for ($index = 0; $index < sizeof($queryb[0]); $index++)
	{
		$newrows[] = array($queryb[1][$index], $queryb[2][$index]);
	}
	
	foreach ($newrows as $row)
	{
		$title = $row[0];
		$DL = $row[1];
	
		$ext = explode('.', $DL);
		$ext = $ext[count($ext) - 1];

		if (!$r_query[$DL])
		{
			echo "new: ".$DL."<br/>\n";
			$found[] = array($title.".".$ext, $DL);
			$new++;
			$r_query[$DL] = true;
		}
		else
		{
			echo "old: ".$DL."<br/>\n";
			$old++;
		}
	
	}
	echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";
}

$dir = "http://www.apple-iigs.info/revueinderauge.php";
echo "<tr><td>".$dir."</td><td></td></tr>";
$query = get_data($dir);

preg_match_all("/<li><a href='(.+?)'>(.+?)<\/li>/", $query, $query);

$newrows = array();
for ($index = 1; $index < sizeof($query[0]); $index++)
{
	$newrows[] = array($query[1][$index], $query[2][$index]);
}

foreach ($newrows as $row)
{
	$page = $row[0];
	$title = $row[1];

	$dir = "http://www.apple-iigs.info/".$page;
	echo "<tr><td>".$dir."</td>";

	$new = 0;
	$old = 0;
	
	$queryb = get_data($dir);
	
	preg_match_all("/<tr class=.+?><td align=.*?>(.+?)<\/td><td><img src='.+?'><\/td><td align=.+?>.+?<\/td><td><a class=nolink href='\.\.\/(.+?)'>/", str_replace("\r", "", $queryb), $queryb);
	
	$newrowsb = array();
	for ($index = 0; $index < sizeof($queryb[0]); $index++)
	{
		$newrowsb[] = array($queryb[1][$index], $queryb[2][$index]);
	}
	
	foreach ($newrowsb as $row)
	{
		$title2 = $row[0];
		$DL = $row[1];
		
		$ext = explode('.', $DL);
		$ext = $ext[count($ext) - 1];
	
		if (!$r_query[$DL])
		{
			echo "new: ".$DL."<br/>\n";
			$found[] = array($title." (".$title2.").".$ext, $DL);
			$new++;
			$r_query[$DL] = true;
		}
		else
		{
			echo "old: ".$DL."<br/>\n";
			$old++;
		}
	}
	echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";
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

?>