<?php
/* ------------------------------------------------------------------------------------
View systems, sources, games, and parents from one convenient location.

TODO: Add search functionality
------------------------------------------------------------------------------------ */

// All possible $_GET variables that we can use (propogate this to other files?)
$getvars = array(
		"system",			// systems.id
		"source",			// sources.id
		"game",				// games.id
		"offset",			// offset for pagination
);

//Get the values for all parameters
foreach ($getvars as $var)
{
	$$var = (isset($_GET[$var]) && $_GET[$var] != "-1" ? trim($_GET[$var]) : "");
}

// Assuming there are no relevent params show the basic page
if ($system == "" && $source == "" && $game == "" && $file == "")
{
	show_default($link);
}
// Then capture game view mode, it also takes precidence over the others.
elseif ($game != "")
{
	// Get the total count in the case that it needs limiting
	$query = "SELECT COUNT(*) as count
			FROM systems
			JOIN games
				ON systems.id=games.system
			JOIN sources
				ON games.source=sources.id
			JOIN files
				ON games.id=files.setid
			JOIN checksums
				ON files.id=checksums.file
			WHERE games.id=".$game;
	$count = mysqli_query($link, $query);
	$count = mysqli_fetch_assoc($count);
	$count = $count["count"];
	settype($count, "integer");

	// Retrieve the game info
	$query = "SELECT systems.manufacturer AS manufacturer,
				systems.system AS system,
				systems.id AS systemid,
				sources.name AS source,
				sources.id AS sourceid,
				games.name AS game,
				files.id AS file,
				files.name AS name,
				files.type AS type,
				checksums.size AS size,
				checksums.crc AS crc,
				checksums.md5 AS md5,
				checksums.sha1 AS sha1
			FROM systems
			JOIN games
				ON systems.id=games.system
			JOIN sources
				ON games.source=sources.id
			JOIN files
				ON games.id=files.setid
			JOIN checksums
				ON files.id=checksums.file
			WHERE games.id=".$game.
			" LIMIT 50 OFFSET ".($offset == "" ? "0" : ($offset*5)."0");
	$result = mysqli_query($link, $query);
	if (gettype($result) == "boolean" || mysqli_num_rows($result) == 0)
	{
		echo "No game info could be retrieved! There might be an error.<br/>";
		exit;
	}	
	$roms = mysqli_fetch_all($result);
	$game_info = $roms[0];

	echo "<h2>View the Game Information Below</h2>

<table>
<tr>
	<th width='100px'>System</th>
	<td>".$game_info[0]." - ".$game_info[1]."</td>
</tr>
<tr>
	<th>Source</th>
	<td>".$game_info[3]."</td>
</tr>
<tr>
	<th>Name</th>
	<td>".$game_info[5]."</td>
</tr>
</table><br/>

<table>
<tr>
	<th>Name</th><th>Type</th><th>Size</th><th>CRC</th><th>MD5</th><th>SHA-1</th>
</tr>";

	foreach ($roms as $rom)
	{
		echo "<tr>\n";
		for ($i = 7; $i < 13; $i++)
		{
			echo "\t<td>".$rom[$i]."</td>\n";
		}
		echo "</tr>\n";
	}

	echo "</table><br/>";

	echo "<br/>";
	if ($offset != "" && $offset > 0)
	{
		echo "<a href='?page=view&system=".$system."&source=".$source."&game=".$game."&offset=".($offset-1)."'>Last 50</a>   ";
	}
	if ($count > (mysqli_num_rows($result) + ($offset == "" ? 0 : $offset*50)))
	{
		echo "<a href='?page=view&system=".$system."&source=".$source."&game=".$game."&offset=".($offset+1)."'>Next 50</a>";
	}
	echo "<br/>";

	echo "<a href='?page=view".
			($source != "" ? "&source='".$source : "").
			($system != "" ? "&system='".$system : "").
			"'>Back to previous page</a><br/>\n";
				
}
else
{
	// Retrieve source info only so it's not duplicated
	if ($source != "")
	{
		$query = "SELECT name, url FROM sources WHERE id='".$source."'";
		$source_result = mysqli_query($link, $query);
		$source_info = mysqli_fetch_assoc($source_result);
	}
	// Retrieve system info only so it's not duplicated
	if ($system != "")
	{
		$query = "SELECT manufacturer, system FROM systems WHERE id='".$system."'";
		$system_result = mysqli_query($link, $query);
		$system_info = mysqli_fetch_assoc($system_result);
	}

	// Get the total count in the case that it needs limiting
	$query = "SELECT COUNT(*) as count
			FROM systems
			JOIN games
				ON systems.id=games.system
			JOIN sources
				ON games.source=sources.id
			WHERE ".
			($source != "" ? "sources.id=".$source : "").
			($source != "" && $system != "" ? " AND " : "").
			($system != "" ? "systems.id=".$system : "");
			$count = mysqli_query($link, $query);
			$count = mysqli_fetch_assoc($count);
			$count = $count["count"];
			settype($count, "integer");

	// Then get all games assocated
	$query = "SELECT games.name AS name,
				games.id AS id,
				systems.manufacturer AS manufacturer,
				systems.system AS system,
				sources.name AS source
			FROM systems
			JOIN games
				ON systems.id=games.system
			JOIN sources
				ON games.source=sources.id
			WHERE ".
			($source != "" ? "sources.id=".$source : "").
			($source != "" && $system != "" ? " AND " : "").
			($system != "" ? "systems.id=".$system : "").
			" ORDER BY games.name ASC
			LIMIT 50 OFFSET ".($offset == "" ? "0" : ($offset*5)."0");
	$games_result = mysqli_query($link, $query);

	echo "<h2>Games With This ".
			($source != "" ? "Source" : "").
			($source != "" && $system != "" ? " And " : "").
			($system != "" ? "System" : "")."</h2>\n";
	if (gettype($games_result) != "boolean")
	{
		while ($game = mysqli_fetch_assoc($games_result))
		{
			echo "<a href='?page=view&game=".$game["id"]."'>".$game["name"];
			if ($source != "" && !$system != "")
			{
				echo " (".$game["manufacturer"]." - ".$game["system"].")";
			}
			if (!$source != "" && $system != "")
			{
				echo " (".$game["source"].")";
			}
			echo "</a><br/>\n";
		}
		echo "<br/>";
		if ($offset != "" && $offset > 0)
		{
			echo "<a href='?page=view&system=".$system."&source=".$source."&offset=".($offset-1)."'>Last 50</a>   ";
		}
		if ($count > (mysqli_num_rows($games_result) + ($offset == "" ? 0 : $offset*50)))
		{
			echo "<a href='?page=view&system=".$system."&source=".$source."&offset=".($offset+1)."'>Next 50</a>";
		}
		echo "<br/>";
	}
	else
	{
		echo "No games could be found!";
	}
	echo "<br/>";
}

// Requires the mysqli link
function show_default($link)
{
	// Retrieve the system listing
	$query = "SELECT id, manufacturer, system FROM systems
				ORDER BY manufacturer ASC,
					system ASC";
	$result = mysqli_query($link, $query);
	$systems = array();
	while ($row = mysqli_fetch_assoc($result))
	{
		array_push($systems, $row);
	}

	// Retrieve the sources listing
	$query = "SELECT id, name FROM sources
				ORDER BY name ASC";
	$result = mysqli_query($link, $query);
	$sources = array();
	while ($row = mysqli_fetch_assoc($result))
	{
		array_push($sources, $row);
	}

	// Output the input selection form
	echo <<<END
<form action='index.php' method='get'>
<input type='hidden' name='page' value='view' />
<h2>Select a System or Source</h2>
<select name='system' id='system'>
<option value='' selected='selected'>Choose a System</option>
END;
	foreach ($systems as $system)
	{
		echo "<option value='".$system["id"]."'>".$system["manufacturer"]." - ".$system["system"]."</option>\n";
	}
	echo "</select>
<select name='source' id='source'>
<option value='' selected='selected'>Choose a Source</option>";
	foreach ($sources as $source)
	{
		echo "<option value='".$source["id"]."'>".$source["name"]."</option>\n";
	}

	echo "</select><br/>
<input type='submit'>
</form><br/><br/>\n";
}

?>