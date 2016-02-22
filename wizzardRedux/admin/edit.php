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
TODO: Add code to accept POST handling for updates
TODO: Clean up multiple echos
 ------------------------------------------------------------------------------------ */

//Get the values for all parameters
$system = (isset($_GET["system"]) != "" ? trim($_GET["system"]) : "");
$source = (isset($_GET["source"]) != "" ? trim($_GET["source"]) : "");
$game = (isset($_GET["game"]) != "" ? trim($_GET["game"]) : "");

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
	
	echo "<form action='index.php' method='get'>\n";
	echo "<input type='hidden' name='page' value='edit' />\n";
	echo "<h2>Edit the Game Information Below</h2>\n";
	echo "<table>\n";
	echo "<tr><th width='100px'>System</th><td>";
	echo "<select name='system' id='system'>\n";
	foreach ($systems as $system)
	{
		echo "<option value='".$system["id"]."'".
			($system["id"] == $game_info["systemid"] ? " selected='selected'" : "").
			">".$system["manufacturer"]." - ".$system["system"]."</option>\n";
	}
	echo "</select></td></tr>\n";
	echo "<tr><th>Source</th><td>";
	echo "<select name='source' id='source'>\n";
	foreach ($sources as $source)
	{
		echo "<option value='".$source["id"]."'".
			($source["id"] == $game_info["sourceid"] ? " selected='selected'" : "").
			">".$source["name"]."</option>\n";
	}
	echo "</select></td></tr>\n";
	echo "<tr><th>Name</th><td><input type='text' name='name' value='".$game_info["game"]."'/></td></tr>\n";
	//Parent
	echo "<tr><th>Type</th><td><input type='text' name='type' value='".$game_info["type"]."'/></td></tr>\n";
	
	echo "</table><br/>\n";
	echo "<input type='submit'>\n</form><br/>\n";
	
	// DO STUFF TO SHOW AND EDIT ROM INFORMATION
	// HAVE GO BACK TO SOURCE/SYSTEM LINK IF $source OR $system IS SET
	
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
	$query = "SELECT games.name AS name
			FROM systems
			JOIN games
				ON systems.id=games.system
			JOIN sources
				ON games.source=sources.id
			WHERE".
				($source_set ? "source.id='".$source."'" : "").
				($source_set && $system_set ? " AND " : "").
				($system_set ? "systems.id='".$system."'" : "");
	$games_result = mysqli_query($link, $query);

	echo "<form action='index.php' method='get'>\n";
	echo "<input type='hidden' name='page' value='edit' />\n";
	
	if ($source_set)
	{
		echo "<input type='hidden' name='source' value='".$source."' />\n";
		echo "<input type='text' name='name' value='".$source_info["name"]."' />\n";
		echo "<input type='text' name='url' value='".$source_info["url"]."' /><br/>\n";
	}
	if ($system_set)
	{
		echo "<input type='hidden' name='system' value='".$system."' />\n";
		echo "<input type='text' name='manufacturer' value='".$system_info["manufacturer"]."' />\n";
		echo "<input type='text' name='system' value='".$system_info["system"]."' /><br/>\n";
	}
	
	echo "<input type='submit'>\n</form><br/><br/>\n";

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
	echo "<form action='index.php' method='get'>\n";
	echo "<input type='hidden' name='page' value='edit' />";
	echo "<h2>Select a System or Source</h2>";
	echo "<select name='system' id='system'>\n";
	echo "<option value='' selected='selected'>Choose a System</option>\n";
	foreach ($systems as $system)
	{
		echo "<option value='".$system["id"]."'>".$system["manufacturer"]." - ".$system["system"]."</option>\n";
	}
	echo "</select>   \n";
	echo "<select name='source' id='source'>\n";
	echo "<option value='' selected='selected'>Choose a Source</option>\n";
	foreach ($sources as $source)
	{
		echo "<option value='".$source["id"]."'>".$source["name"]."</option>\n";
	}
	echo "</select><br/>\n";
	echo "<input type='submit'>\n</form>\n";
	
	echo "<h2>Or Add a New One</h2>";
	echo "<form action='index.php' method='get'>\n";
	echo "<input type='hidden' name='page' value='edit' />";
	echo "<input type='hidden' name='source' value='-1' />\n";
	echo "<h3>Source Add</h3>\n";
	echo "<b>Name:</b> <input type='text' name='name' value='".$source_info["name"]."' />\n";
	echo "<b>URL(s):</b> <input type='text' name='url' value='".$source_info["url"]."' /><br/>\n";
	echo "<input type='hidden' name='system' value='-1' />\n";
	echo "<h3>System Add</h3>\n";
	echo "<b>Manufacturer:</b> <input type='text' name='manufacturer' value='".$system_info["manufacturer"]."' />\n";
	echo "<b>Name:</b> <input type='text' name='system' value='".$system_info["system"]."' /><br/><br/>\n";
	
	echo "<input type='submit'>\n</form><br/><br/>\n";
}

?>