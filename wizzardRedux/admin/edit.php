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
TODO: Create either helper page or use POST to set values changed
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
	// DO STUFF TO SHOW AND EDIT GAME INFORMATION
	// HAVE GO BACK TO SOURCE/SYSTEM LINK IF $source OR $system IS SET
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
	
	// Output the two selection boxes
	echo "<form action='index.php' method='get'>\n";
	echo "<input type='hidden' name='page' value='edit' />";
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
	echo "<input type='submit'>\n</form><br/><br/>\n";
}

?>