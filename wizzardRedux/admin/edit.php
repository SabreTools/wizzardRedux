<?php

/* ------------------------------------------------------------------------------------
Add, edit, and remove data from the database manually.

Requires:
	system		System id to be used
	source		Source id to be used
	game		Game id to be used
	page		Page number for necessary pagination of some systems/sources
	
Notes:
	If !system && !source && !game => show page to select system and source from populated dropdowns
	If ANY && ANY && game => Show game edit page (get all information about it including system, source, linked files/checksums)
	If !system && source && !game => show a page with both the source information editable and all files linked to that source
	If system && !source && !game => show a page with both the system information editable and all files linked to that system
	If system && source && !game => show a page with both the system and source informations editable and all files linked with both
	
TODO: Extract duplicated code into functions
TODO: Add pagination to game outputs for sources/systems
	(http://stackoverflow.com/questions/25718856/php-best-way-to-display-x-results-per-page)
 ------------------------------------------------------------------------------------ */

//Get the values for all parameters
$system = (isset($_GET["system"]) != "" ? trim($_GET["system"]) : "");
$source = (isset($_GET["source"]) != "" ? trim($_GET["source"]) : "");
$game = (isset($_GET["game"]) != "" ? trim($_GET["game"]) : "");

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
// If only the source is set, show only the source info and games assocated
elseif ($system == "" && $source != "")
{
	// First retrieve source info only so it's not duplicated
	$query = "SELECT name, url FROM sources WHERE id='".$source."'";
	$source_result = mysqli_query($link, $query);
	// Then get all games assocated
	$query = "SELECT games.name AS name
			FROM sources
			JOIN games
				ON sources.id=games.source
			WHERE source.id='".$source."'";
	$games_result = mysqli_query($link, $query);
	
	$source_info = mysqli_fetch_assoc($source_result);
	
	echo "<form action='index.php' method='get'>\n";
	echo "<input type='hidden' name='page' value='edit' />\n";
	echo "<input type='hidden' name='source' value='".$source."' />\n";
	echo "<input type='text' name='name' value='".$source_info["name"]."' />\n";
	echo "<input type='text' name='url' value='".$source_info["url"]."' /><br/>\n";
	echo "<input type='submit'>\n</form><br/><br/>\n";
	
	// DO STUFF TO SHOW AND EDIT SOURCE INFORMATION OR CHOOSE A FILE
}
// If only the system is set, show only the system info and games associated
elseif ($system != "" && $source == "")
{
	// First retrieve system info only so it's not duplicated
	$query = "SELECT manufacturer, system FROM systems WHERE id='".$system."'";
	$system_result = mysqli_query($link, $query);
	// Then get all games assocated
	$query = "SELECT games.name AS name
			FROM systems
			JOIN games
				ON systems.id=games.system
			WHERE systems.id='".$system."'";
	$games_result = mysqli_query($link, $query);
	
	$system_info = mysqli_fetch_assoc($system_result);
	
	echo "<form action='index.php' method='get'>\n";
	echo "<input type='hidden' name='page' value='edit' />\n";
	echo "<input type='hidden' name='system' value='".$system."' />\n";
	echo "<input type='text' name='manufacturer' value='".$system_info["manufacturer"]."' />\n";
	echo "<input type='text' name='system' value='".$system_info["system"]."' /><br/>\n";
	echo "<input type='submit'>\n</form><br/><br/>\n";
	
	// DO STUFF TO SHOW AND EDIT SYSTEM INFORMATION OR CHOOSE A FILE
}
// If both are set, show both editable and all games assocated
elseif ($system != "" && $source != "")
{
	// First retrieve source info only so it's not duplicated
	$query = "SELECT name, url FROM sources WHERE id='".$source."'";
	$source_result = mysqli_query($link, $query);
	// Next retrieve system info only so it's not duplicated
	$query = "SELECT manufacturer, system FROM systems WHERE id='".$system."'";
	$system_result = mysqli_query($link, $query);
	// Then get all the games associated
	$query = "SELECT games.name AS name
			FROM systems
			JOIN games
				ON systems.id=games.system
			JOIN sources
				ON games.source=sources.id
			WHERE source.id='".$source."'
				AND systems.id='".$system."'";
	$games_result = mysqli_query($link, $query);
	
	$source_info = mysqli_fetch_assoc($source_result);
	$system_info = mysqli_fetch_assoc($system_result);
	
	echo "<form action='index.php' method='get'>\n";
	echo "<input type='hidden' name='page' value='edit' />\n";
	echo "<input type='hidden' name='source' value='".$source."' />\n";
	echo "<input type='hidden' name='system' value='".$system."' />\n";
	echo "<input type='text' name='name' value='".$source_info["name"]."' />\n";
	echo "<input type='text' name='url' value='".$source_info["url"]."' /><br/>\n";
	echo "<input type='text' name='manufacturer' value='".$system_info["manufacturer"]."' />\n";
	echo "<input type='text' name='system' value='".$system_info["system"]."' /><br/>\n";
	echo "<input type='submit'>\n</form><br/><br/>\n";
	
	// DO STUFF TO SHOW AND EDIT SYSTEM AND SOURCE INFORMATION OR CHOOSE A FILE
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