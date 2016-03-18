<?php

// Original code: The Wizard of DATz

echo "<table>\n";
echo "<tr><td>http://intros.c64.org/frame.php - FIXED</td>";

$query = get_data("http://intros.c64.org/frame.php");
$query = explode('<div class="menu_header">FIXED</div>', $query);

preg_match_all("/<a href=\"main\.php\?module=showintro&iid=(.*?)\".*?>(.*?)<\/a>/s", $query[1], $query);
$newrows = array();
for ($index = 0; $index < sizeof($query[0]); $index++)
{
	$newrows[] = array($query[1][$index], $query[2][$index]);
}

$old = 0;
$new = 0;

foreach ($newrows as $row)
{
	$id = $row[0];
	$gametitle = $row[1];
	$gametitle = explode(' ', $gametitle);
	$gametitle[count($gametitle) - 1] = "(".$gametitle[count($gametitle) - 1].")";
	$gametitle = implode(' ', $gametitle);

	if (!$r_query[$id])
	{
		$new++;
		$found[] = array("{FIX-".$id."}".$gametitle.".prg", "http://intros.c64.org/inc_download.php?iid=".$id);
	}
	else
	{
		$old++;
	}
}
echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";

$r_query = array_flip($r_query);
$start = explode("=", $r_query[0]);
$start = $start[1];

print "\nSearch for new uploads\n\n";

$error = false;
$x = $start;
while(!$error)
{
	echo "<tr><td>http://intros.c64.org/main.php?module=showintro&iid=".$x."</td>";
	$query = get_data("http://intros.c64.org/main.php?module=showintro&iid=".$x);
	
	if ($query != "Database error. Please contact us if this problem persists." && strpos($query, "<a href=\"inc_download.php?iid=\"") === FALSE) 
	{
		preg_match("/<span class=\"introname\">(.*?)<\/span>/", $query, $gametitle);
		$gametitle = explode(' ', $gametitle[1]);
		$gametitle[count($gametitle) - 1] = "(".$gametitle[count($gametitle) - 1].")";
		$gametitle = implode(' ', $gametitle);
		
		$found[] = array("{".$x."}".$gametitle.".prg", "http://intros.c64.org/inc_download.php?iid=".$x);
		echo "<td>Found new: 1, old: 0</tr>\n";
	}
	else
	{
		$error = true;
	}
	$x++;
}
echo "</table>\n";

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