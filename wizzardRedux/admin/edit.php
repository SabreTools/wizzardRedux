<?php

/* ------------------------------------------------------------------------------------
Add, edit, and remove data from the database manually.

Requires:
	type	The type of data to work with [system, source, game]
	
NOTE: Is it necessary to have a switch on the type? Can't everything be on the same page?

Flow: Choose System / Source from auto-populated dropdowns -> Game dropdown appears,
	filtered
Persistent have Add System and Add Source buttons
Conditional Edit System, Remove System, Edit Source, Remove Source if a valid (non-0)
	source is selected
Persistent Add Game button
Conditional Edit Game, Remove Game buttons if a valid (non-0) source is selected
Search boxes?
 ------------------------------------------------------------------------------------ */

$path_to_root = (getcwd() == "/wod/" ? "" : "..");

if (!isset($_GET["type"]))
{
	echo "<b>You must supply a type as a URL parameter! (type=system|source|game)</b><br/>";
	echo "<a href='".$path_to_root."/index.php'>Return to home</a>";
	
	die();
}
$type = $_GET["type"];
if ($type != "system" && $type != "source" && $type != "game")
{
	echo "<b>The parameter value must be \"system\", \"source\", or \"game\"</b><br/>";
	echo "<a href='".$path_to_root."/index.php'>Return to home</a>";

	die();
}

echo "Valid type detected: ".$type."<br/>";

switch ($type)
{
	case "system": break;
	case "source": break;
	case "games": break;
}

?>