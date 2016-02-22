<?php

/* ------------------------------------------------------------------------------------
Add, edit, and remove data from the database manually.

Requires:
	system		System id to be used
	source		Source id to be used
	game		Game id to be used
	page		Page number for necessary pagination of some systems/sources
	
TODO: Add pagination to game outputs for sources/systems
	(http://stackoverflow.com/questions/25718856/php-best-way-to-display-x-results-per-page)
TODO: Add POST handling
 ------------------------------------------------------------------------------------ */

var_dump($_POST);

$postvars = array(
		"system",			// systems.id (-1 for new)
		"manufacturer",		// systems.manufacturer
		"systemname",		// systems.system
		"source",			// sources.id (-1 for new)
		"sourcename",		// sources.name
		"url",				// sources.url
		"game",				// games.id (-1 for new)
		"gamename",			// games.name
		"file",				// files.id (-1 for new)
		"filename",			// files.name
		"type",				// files.type
		"size",				// checksums.size
		"crc",				// checksums.crc
		"md5",				// checksums.md5
		"sha1",				// checksums.sha1
);

//Get the values for all parameters
$system = (isset($_GET["system"]) && $_GET["system"] != "-1" ? trim($_GET["system"]) : "");
$source = (isset($_GET["source"]) && $_GET["source"] != "-1" ? trim($_GET["source"]) : "");
$game = (isset($_GET["game"]) ? trim($_GET["game"]) : "");

// Set the special check values
$source_set = $source != "";
$system_set = $system != "";

// Assuming there are no relevent params, show the basic page
if ($system == "" && $source == "" && $game == "")
{
	show_default($link);
}
// First and foremost, capture game edit mode. It's exclusive above all others.
elseif ($game != "")
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
	
	$query = "SELECT systems.manufacturer AS manufacturer,
				systems.system AS system,
				systems.id AS systemid,
				sources.name AS source,
				sources.id AS sourceid,
				games.name AS game,
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
			WHERE games.id=".$game;
	$result = mysqli_query($link, $query);
	$game_info = mysqli_fetch_assoc($result);
	
	echo "<form action='index.php?page=edit' method='post'>
<input type='hidden' name='game' value='".$game."
<h2>Edit the Game Information Below</h2>
<table>
<tr><th width='100px'>System</th><td>
<select name='system' id='system'>";
	
	foreach ($systems as $system)
	{
		echo "<option value='".$system["id"]."'".
			($system["id"] == $game_info["systemid"] ? " selected='selected'" : "").
			">".$system["manufacturer"]." - ".$system["system"]."</option>\n";
	}
	echo <<<END
</select></td></tr>
<tr><th>Source</th><td><select name='source' id='source'>
END;
	foreach ($sources as $source)
	{
		echo "<option value='".$source["id"]."'".
			($source["id"] == $game_info["sourceid"] ? " selected='selected'" : "").
			">".$source["name"]."</option>\n";
	}	
	echo "</select></td></tr>\n".
		"<tr><th>Name</th><td><input type='text' name='gamename' value='".$game_info["game"]."'/></td></tr>\n".
		"</table><br/>\n".
		"<input type='submit'>\n</form><br/>\n";
	
	// DO STUFF TO SHOW AND EDIT ROM INFORMATION
	/*
	"<tr><th>Type</th><td><select name='type' id='type'>".
			"<option value='rom'".($game_info["type"] == "rom" ? " selected='selected'" : "").">rom</option>\n".
			"<option value='disk'".($game_info["type"] == "disk" ? " selected='selected'" : "").">disk</option>\n".
		"</td></tr>\n".
	*/
	
	echo "<a href='?page=edit".
			($source_set ? "&source='".$source : "").
			($system_set ? "&system='".$system : "").
			"'>Back to previous page</a><br/>\n";
}
else
{
	// Retrieve source info only so it's not duplicated
	if ($source_set)
	{
		$query = "SELECT name, url FROM sources WHERE id='".$source."'";
		$source_result = mysqli_query($link, $query);
		$source_info = mysqli_fetch_assoc($source_result);
	}
	// Retrieve system info only so it's not duplicated
	if ($system_set)
	{
		$query = "SELECT manufacturer, system FROM systems WHERE id='".$system."'";
		$system_result = mysqli_query($link, $query);
		$system_info = mysqli_fetch_assoc($system_result);
	}
	
	// Then get all games assocated
	$query = "SELECT games.name AS name, games.id AS id
			FROM systems
			JOIN games
				ON systems.id=games.system
			JOIN sources
				ON games.source=sources.id
			WHERE ".
				($source_set ? "sources.id=".$source : "").
				($source_set && $system_set ? " AND " : "").
				($system_set ? "systems.id=".$system : "");
	$games_result = mysqli_query($link, $query);

	echo "<form action='index.php?page=edit".
			($source_set ? "&source=".$source : "").
			($system_set ? "&system=".$system : "").
			"' method='post'>\n";	
	
	if ($source_set)
	{
		echo "<input type='hidden' name='source' value='".$source."' />\n".
			"<b>Source:</b> <input type='text' name='sourcename' value='".$source_info["name"]."' />\n".
			"<b>URL(s):</b> <input type='text' name='url' value='".$source_info["url"]."' /><br/>\n";
	}
	if ($system_set)
	{
		echo "<input type='hidden' name='system' value='".$system."' />\n".
			"<b>Manufacturer:</b> <input type='text' name='manufacturer' value='".$system_info["manufacturer"]."' />\n".
			"<b>Name:</b> <input type='text' name='system' value='".$system_info["system"]."' /><br/>\n";
	}
	
	echo "<input type='submit'>\n</form><br/>\n";
	
	echo "<h2>Games With This ".
			($source_set ? "Source" : "").
			($source_set && $system_set ? " And " : "").
			($system_set ? "System" : "")."</h2>\n";
	if (gettype($games_result) != "boolean")
	{
		while ($game = mysqli_fetch_assoc($games_result))
		{
			echo "<a href='?page=edit&game=".$game["id"]."'>".$game["name"]."</a><br/>\n";
		}
	}
	else
	{
		echo "No games could be found!";
	}
	echo "<br/>";

	// DO STUFF TO CHOOSE A FILE
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
<input type='hidden' name='page' value='edit' />
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
</form>
<h2>Or Add a New One</h2>
<form action='index.php?page=edit&source=-1&system=-1' method='post'>
			
<h3>Source Add</h3>
<b>Name:</b> <input type='text' name='sourcename' value='".$source_info["name"]."' />
<b>URL(s):</b> <input type='text' name='url' value='".$source_info["url"]."' /><br/>

<h3>System Add</h3>
<b>Manufacturer:</b> <input type='text' name='manufacturer' value='".$system_info["manufacturer"]."' />
<b>Name:</b> <input type='text' name='systemname' value='".$system_info["system"]."' /><br/><br/>

<input type='submit'>\n</form><br/><br/>\n";
}

?>