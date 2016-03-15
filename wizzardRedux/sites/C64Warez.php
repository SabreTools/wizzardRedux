<?php

// Original code: The Wizard of DATz

// TODO: Find what the download URL is (currently old one being used)

$pages = get_data("http://remotecpu.com/downloads.html");
preg_match_all("/'?(\/downloads\/category\/.*?)'?,/", $pages, $pages);
$pages = $pages[1];

echo "<table>\n";
foreach ($pages as $page)
{
	echo "<tr><td>".$page."</td>";	
	$query = get_data("http://remotecpu.com/".$page);
	
	preg_match("/<b>Category: (.*?)<\/b>/", $query, $category);
	$category = $category[1];
	
	preg_match_all("/<td.*?><a href=\"(\/downloads\/download\/.*?)\">/", $query, $links);
	$links = $links[1];
	
	if (sizeof($links) == 0)
	{
		echo "<td>Found new: 0, old: 0</td></tr>\n";
		continue;
	}

	$new = 0;
	$old = 0;

	foreach ($links as $link)
	{
		$gamepage = get_data("http://remotecpu.com/".$link);
		
		preg_match("/<tr><td.*?>System<\/td><td.*?><strong>(.*?)<\/strong><\/tr>/", $gamepage, $system);
		$system = trim($system[1]);
		
		preg_match("/<tbody>.*?<tr>.*?<td.*?><span.*?><img.*?>(.*?)<\/span><\/td>/s", $gamepage, $name);
		$name = trim($name[1]);
		
		$title = "{".$system."}".$name." (".$category.")";
		
		$id = explode("/", $link);
		$id = $id[sizeof($id) - 1];
		$id = explode("-", $id);
		$id = $id[0];
		
		if (!$r_query[$id])
		{
			// This link is outdated, but without registration it's impossible to get the updated link
			$found[] = array($title, "http://c64warez.com/files/get_file/".$id);
			$new++;
			$r_query[$id] = true;
		}
		else
		{
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
	echo "<a href='".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>