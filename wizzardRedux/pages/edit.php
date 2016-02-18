<!-- 
Add, edit, and remove data from the database manually.

Requires:
	type	The type of data to work with [system, source, game]
-->

<?php

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

?>