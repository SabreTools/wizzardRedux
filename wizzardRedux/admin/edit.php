<?php

/* ------------------------------------------------------------------------------------
Add, edit, and remove data from the database manually.
	
TODO: Add pagination to game outputs for sources/systems
	(http://stackoverflow.com/questions/25718856/php-best-way-to-display-x-results-per-page)
 ------------------------------------------------------------------------------------ */

// All possible $_GET variables that we can use (propogate this to other files?)
$getvars = array(
		"system",			// systems.id
		"source",			// sources.id
		"game",				// games.id
		"file",				// files.id
		"offset",			// offset for pagination
		"remove",			// enable deletion mode, see $rmopts for more details
);

// All possible $_POST variables that we can use
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

// All possible values for $_GET["remove"]
$rmopts = array(
		"system",			// set all former games with system to have system 0, requires system
		"source",			// set all former games with source to have source 0, requires source
		"game",				// delete all files and checksums associated, requires game
		"file",				// delete all checksums associated, requires file
);

//Get the values for all parameters
foreach ($getvars as $var)
{
	$$var = (isset($_GET[$var]) && $_GET[$var] != "-1" ? trim($_GET[$var]) : "");
}

//Get thve values for all POST vars ($_GET overrides)
foreach ($postvars as $var)
{
	if (!isset($$var) || $$var == "")
	{
		$$var = (isset($_POST[$var]) ? $_POST[$var] : "");
	}
}

// Set the special check values
$source_set = $source != "";
$system_set = $system != "";

// Handle the remove cases. They have to be mutually exclusive
if (in_array($remove, $rmopts))
{
	// If a system is being removed (can't remove default)
	if ($remove == "system" && $system != "" && $system != "0")
	{
		$query = "DELETE FROM system where id=".$system;
		$result = mysqli_query($link, $query);
		
		if (gettype($result) == "boolean" && $result)
		{
			echo "System deleted successfully! Altering all related files.<br/>";
			
			$query = "UPDATE games SET system=0 WHERE system=".$system;
			$result = mysqli_query($link, $query);
			
			if (gettype($result) == "boolean" && $result)
			{
				echo "Files altered successfully! No further action required.<br/>";
			}
			else
			{
				echo "Something has gone wrong, please manually alter all files with system '".$system."' to have value 0<br/>";
			}
		}
		else
		{
			echo "System could not be deleted. Try again later.<br/>";
		}
	}
	// If a source is being removed (can't remove default)
	elseif ($remove == "source" && $source != "" && $source != "0")
	{
		$query = "DELETE FROM source where id=".$system;
		$result = mysqli_query($link, $query);
		
		if (gettype($result) == "boolean" && $result)
		{
			echo "Source deleted successfully! Altering all related files.<br/>";
				
			$query = "UPDATE games SET source=0 WHERE source=".$source;
			$result = mysqli_query($link, $query);
				
			if (gettype($result) == "boolean" && $result)
			{
				echo "Files altered successfully! No further action required.<br/>";
			}
			else
			{
				echo "Something has gone wrong, please manually alter all files with source '".$source."' to have value 0<br/>";
			}
		}
		else
		{
			echo "System could not be deleted. Try again later.<br/>";
		}
	}
	// If a game is being removed
	elseif ($remove == "game" && $game != "")
	{
		$query = "DELETE FROM games
				JOIN files
					ON games.id=files.setid
				JOIN checksums
					ON files.id=checksums.file
				WHERE games.id=".$game;
		$result = mysqli_query($link, $query);
		
		if (gettype($result) == "boolean" && $result)
		{
			echo "Game and dependent files deleted successfully!<br/>";
		}
		else
		{
			echo "Could not delete game or dependent files. Please try again<br/>";;
		}
	}
	// If a file is being removed
	elseif ($remove == "file" && $file != "")
	{
		$query = "DELETE FROM files
				JOIN checksums
					ON files.id=checksums.file
				WHERE files.id=".$file;
		$result = mysqli_query($link, $query);
		
		if (gettype($result) == "boolean" && $result)
		{
			echo "File deleted successfully!<br/>";
		}
		else
		{
			echo "Could not delete file. Please try again<br/>";;
		}
	}
}
// Handle the POST cases. They do not have to be mutually exclusive
else
{
	// If a system is being edited or added via POST
	if ($system != "" && $manufacturer != "" && $systemname != "")
	{
		// If the system is new and being added
		if ($system == "-1")
		{
			// Always check if this exact combination is already there. This might have been in error
			$query = "SELECT * FROM systems WHERE manufacturer='".$manufacturer." AND system='".$systemname."'";
			$result = mysqli_query($link, $query);
			
			// If we find this system, tell the user and don't move forward
			if (gettype($result) != "boolean" && mysqli_num_rows($result) > 0)
			{
				echo "This system has been found. No further action is required<br/>";
			}
			// If the system is not found, add it
			else
			{
				$query = "INSERT INTO systems (manufacturer, system) VALUES ('".$manufacturer."', '".$systemname."')";
				$result = mysqli_query($link, $query);
				
				if (gettype($result) == "boolean" && $result)
				{
					echo "The system '".$manufacturer." - ".$systemname."' has been added successfully!<br/>";
				}
				else
				{
					echo "The system '".$manufacturer." - ".$systemname."' could not be added, try again later<br/>";
				}
			}
		}
		// If the system is being edited
		else
		{
			// Check if the system exists first
			$query = "SELECT * FROM systems WHERE id=".$system;
			$result = mysqli_query($link, $query);
			
			// If we don't find the system, don't add it. This might have been in error
			if (gettype($result) == "boolean" && !$result)
			{
				echo "The system with id '".$system."' could not be found, try again later<br/>";
			}
			// If we find the system, update with the new information, if not a duplicate of another
			else
			{
				$query = "SELECT * FROM systems WHERE manufacturer='".$manufacturer." AND system='".$systemname."'";
				$result = mysqli_query($link, $query);
				
				// If the system is found or unchanged, don't readd it
				if (gettype($result) != "boolean" && mysqli_num_rows($result) > 0)
				{
					echo "This system has been found. No further action is required<br/>";
				}
				// If the system really has changed or is not a duplicate, add it
				else
				{
					$query = "UPDATE systems SET manufacturer='".$manufacturer."', system='".$systemname."' WHERE id=".$system;
					$result = mysqli_query($link, $query);
					
					if (gettype($result) == "boolean" && $result)
					{
						echo "The system '".$manufacturer." - ".$systemname."' has been updated successfully!<br/>";
					}
					else
					{
						echo "The system '".$manufacturer." - ".$systemname."' could not be updated, try again later<br/>";
					}
				}
			}
		}
	}
	// If a source is being edited or added via POST
	if ($source != "" && $sourcename != "" && $url != "")
	{
		// If the source is new and being added
		if ($source == "-1")
		{
			// Always check if this exact combination is already there. This might have been in error
			$query = "SELECT * FROM sources WHERE name='".$sourcename."'";
			$result = mysqli_query($link, $query);
			
			// If we find this source, tell the user and don't move forward
			if (gettype($result) != "boolean" && mysqli_num_rows($result) > 0)
			{
				echo "This source has been found. No further action is required<br/>";
			}
			// If the source is not found, add it
			else
			{
				$query = "INSERT INTO sources (name, url) VALUES ('".$sourcename."', '".$url."')";
				$result = mysqli_query($link, $query);
				
				if (gettype($result) == "boolean" && $result)
				{
					echo "The source '".$sourcename."' has been added successfully!<br/>";
				}
				else
				{
					echo "The source '".$sourcename."' could not be added, try again later<br/>";
				}
			}
		}
		// If the source is being edited
		else
		{
			// Check if the source exists first
			$query = "SELECT * FROM sources WHERE id=".$system;
			$result = mysqli_query($link, $query);
			
			// If we don't find the source, don't add it. This might have been in error
			if (gettype($result) == "boolean" && !$result)
			{
				echo "The source with id '".$source."' could not be found, try again later<br/>";
			}
			// If we find the source, update with the new information, if not a duplicate of another
			else
			{
				$query = "SELECT * FROM sources WHERE name='".$sourcename."'";
				$result = mysqli_query($link, $query);
					
				// If the source is found or unchanged, don't readd it
				if (gettype($result) != "boolean" && mysqli_num_rows($result) > 0)
				{
					echo "This source has been found. No further action is required<br/>";
				}
				// If the source really has changed or is not a duplicate, add it
				else
				{
					$query = "UPDATE sources SET name='".$sourcename."', url='".$url."' WHERE id=".$source;
					$result = mysqli_query($link, $query);
					
					if (gettype($result) == "boolean" && $result)
					{
						echo "The source '".$sourcename."' has been updated successfully!<br/>";
					}
					else
					{
						echo "The source '".$sourcename."' could not be updated, try again later<br/>";
					}
				}
			}
		}
	}
	// If a game is being edited or added via POST
	if ($game != "" && $gamename != "" && $system != "" && $source != "")
	{
		// If the game is new and being added
		if ($game == "-1")
		{
			// Always check if this exact combination is already there. This might have been in error
			$query = "SELECT * FROM games WHERE name='".$gamename."' AND system=".$system." AND source=".$source;
			$result = mysqli_query($link, $query);
				
			// If we find this game, tell the user and don't move forward
			if (gettype($result) != "boolean" && mysqli_num_rows($result) > 0)
			{
				echo "This game has been found. No further action is required<br/>";
			}
			// If the game is not found, add it
			else
			{
				$query = "INSERT INTO games (name, system, source) VALUES ('".$gamename."', ".$system.", ".$source.")";
				$result = mysqli_query($link, $query);
			
				if (gettype($result) == "boolean" && $result)
				{
					echo "The game '".$gamename."' has been added successfully!<br/>";
				}
				else
				{
					echo "The game '".$gamename."' could not be added, try again later<br/>";
				}
			}
		}
		// If the game is being edited
		else
		{
			// Check if the game exists first
			$query = "SELECT * FROM games WHERE id=".$game;
			$result = mysqli_query($link, $query);
				
			// If we don't find the game, don't add it. This might have been in error
			if (gettype($result) == "boolean" && !$result)
			{
				echo "The game with id '".$game."' could not be found, try again later<br/>";
			}
			// If we find the game, update with the new information, if not a duplicate of another
			else
			{
				$query = "SELECT * FROM games WHERE name='".$gamename."' AND system=".$system." AND source=".$source;
				$result = mysqli_query($link, $query);
					
				// If the game is found elsewhere or unchanged, don't readd it
				if (gettype($result) != "boolean" && mysqli_num_rows($result) > 0)
				{
					echo "This source has been found. No further action is required<br/>";
				}
				// If the game really has changed or is not a duplicate, add it
				else
				{
					$query = "UPDATE game SET name='".$gamename."', system=".$system.", source=".$source." WHERE id=".$game;
					$result = mysqli_query($link, $query);
						
					if (gettype($result) == "boolean" && $result)
					{
						echo "The game '".$gamename."' has been updated successfully!<br/>";
					}
					else
					{
						echo "The game '".$gamename."' could not be updated, try again later<br/>";
					}
				}
			}
		}
	}
	// If a file is being edited or added via POST
	if ($file != "" && $filename != "" && $game != "" && $type != "" &&
			(($type == "rom" && $size != "" && ($crc != "" || $md5 != "" || $sha1 != "")) ||
					($type == "disk" && ($md5 != "" || $sha1 != ""))))
	{
		// If the file is new and being added
		if ($file == "-1")
		{
			// Always check if this exact combination is already there. This might have been in error
			$query = "SELECT * FROM files
					JOIN checksums ON files.id=checksums.file
					WHERE files.name='".$filename.
						"' AND files.setid=".$game.
						" AND files.type='".$type.
						"' AND checksums.size=".$size.
						"' AND checksums.crc='".$crc.
						"' AND checksums.md5='".$md5.
						"' AND checksums.sha1='".$sha1;
			$result = mysqli_query($link, $query);
			
			// If we find this file, tell the user and don't move forward
			if (gettype($result) != "boolean" && mysqli_num_rows($result) > 0)
			{
				echo "This file has been found. No further action is required<br/>";
			}
			// If the file is not found, add it
			else
			{
				$query = "INSERT INTO files (name, game, type)
						VALUES ('".$filename."', ".$game.", ".$type.")";
				$result = mysqli_query($link, $query);
					
				if (gettype($result) == "boolean" && $result)
				{
					echo "The file '".$filename."' has been added successfully! Adding checksums.<br/>";
					
					$file = mysqli_insert_id($link);
					$query = "INSERT INTO checksums (game, size, crc, md5, sha1)
							VALUES (".$game.", ".$size.", '".$crc."', '".$md5."', '".$sha1."')";
					$result = mysqli_query($link, $query);
					
					if (gettype($result) == "boolean" && $result)
					{
						echo "The checksums have been added!<br/>";
					}
					else
					{
						echo "The checksums could not be added, please try again later<br/>";
					}
				}
				else
				{
					echo "The file '".$filename."' could not be added, try again later<br/>";
				}
			}
		}
		// If the file is being edited
		else
		{
		// Check if the file exists first
			$query = "SELECT * FROM files WHERE id=".$file;
			$result = mysqli_query($link, $query);
				
			// If we don't find the file, don't add it. This might have been in error
			if (gettype($result) == "boolean" && !$result)
			{
				echo "The game with id '".$file."' could not be found, try again later<br/>";
			}
			// If we find the file, update with the new information, if not a duplicate of another
			else
			{
				$query = "SELECT * FROM files
						JOIN checksums ON files.id=checksums.file
						WHERE files.name='".$filename.
						"' AND files.setid=".$game.
						" AND files.type='".$type.
						"' AND checksums.size=".$size.
						"' AND checksums.crc='".$crc.
						"' AND checksums.md5='".$md5.
						"' AND checksums.sha1='".$sha1;
				$result = mysqli_query($link, $query);
					
				// If the file is found elsewhere or unchanged, don't readd it
				if (gettype($result) != "boolean" && mysqli_num_rows($result) > 0)
				{
					echo "This file has been found. No further action is required<br/>";
				}
				// If the source really has changed or is not a duplicate, add it
				else
				{
					$query = "UPDATE file
							JOIN checksums ON files.id=checksums.file
							SET files.name='".$filename.
							"' AND files.setid=".$game.
							" AND files.type='".$type.
							"' AND checksums.size=".$size.
							"' AND checksums.crc='".$crc.
							"' AND checksums.md5='".$md5.
							"' AND checksums.sha1='".$sha1;
					$result = mysqli_query($link, $query);
						
					if (gettype($result) == "boolean" && $result)
					{
						echo "The file '".$filename."' has been updated successfully!<br/>";
					}
					else
					{
						echo "The file '".$filename."' could not be updated, try again later<br/>";
					}
				}
			}
		}
	}
}

// Assuming there are no relevent params (or processing POST is done), show the basic page
if ($system == "" && $source == "" && $game == "" && $file == "")
{
	show_default($link);
}
// First and foremost, capture file edit mode. It's exclusive above all others.
elseif ($file != "")
{
	// Retrieve the file info
	$query = "SELECT files.id AS file,
				files.name AS name,
				files.type AS type,
				checksums.size AS size,
				checksums.crc AS crc,
				checksums.md5 AS md5,
				checksums.sha1 AS sha1
			FROM files
			JOIN checksums
				ON files.id=checksums.file
			WHERE files.id=".$file;
	$result = mysqli_query($link, $query);
	$rom = mysqli_fetch_assoc($result);
	
	// Now output the editable information (add form around this)
	echo "<form action='index.php?page=edit' method='post'>
<input type='hidden' name='file' value='".$file."' />
<table>
<tr>
	<th>Name</th>
	<td><input type='text' name='filename' value='".$rom["name"]."' /></td>
</tr>
<tr>
	<th>Type</th>
	<td><select name='type' id='type'>
		<option value='rom'".($game_info["type"] == "rom" ? " selected='selected'" : "").">rom</option>
		<option value='disk'".($game_info["type"] == "disk" ? " selected='selected'" : "").">disk</option>
	</td>
</tr>
<tr>
	<th>Size</th>
	<td><input type='text' name='size' value='".$rom["size"]."' /></td>
</tr>
<tr>
	<th>CRC</th>
	<td><input type='text' name='crc' value='".$rom["crc"]."' /></td>
</tr>
<tr>
	<th>MD5</th>
	<td><input type='text' name='md5' value='".$rom["md5"]."' /></td>
</tr>
<tr>
	<th>SHA-1</th>
	<td><input type='text' name='sha1' value='".$rom["sha1"]."' /></td>
</tr>
</table>
			
<input type='submit'>
</form><br/>";
		
}
// Then capture game edit mode, it also takes precidence over the others.
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
			WHERE games.id=".$game;
	$result = mysqli_query($link, $query);
	$roms = mysqli_fetch_all($result);
	$game_info = $roms[1];
	
	echo "<form action='index.php?page=edit' method='post'>
<input type='hidden' name='game' value='".$game."'/>
<h2>Edit the Game Information Below</h2>

<table>
<tr>
	<th width='100px'>System</th>
	<td><select name='system' id='system'>\n";
	
	foreach ($systems as $system)
	{
		echo "\t\t<option value='".$system["id"]."'".
			($system["id"] == $game_info["systemid"] ? " selected='selected'" : "").
			">".$system["manufacturer"]." - ".$system["system"]."</option>\n";
	}
	
	echo "\t</select></td>
</tr>
<tr>
	<th>Source</th>
	<td><select name='source' id='source'>\n";
	
	foreach ($sources as $source)
	{
		echo "\t\t<option value='".$source["id"]."'".
			($source["id"] == $game_info["sourceid"] ? " selected='selected'" : "").
			">".$source["name"]."</option>\n";
	}
	
	echo "\t</select></td>
</tr>
<tr>
	<th>Name</th>
	<td><input type='text' name='gamename' value='".$game_info["game"]."'/></td>
</tr>
</table><br/>

<table>
<tr>
	<th></th><th>Name</th><th>Type</th><th>Size</th><th>CRC</th><th>MD5</th><th>SHA-1</th>
</tr>";
	
	foreach ($roms as $rom)
	{	
		echo "<tr>
	<td><input type='radio' name='file' value='".$rom[6]."'/></td>";
		for ($i = 7; $i < 13; $i++)
		{
			echo "<td>".$rom[$i]."</td>";
		}
		echo "</tr>\n";
	}
	
	echo "</table>".
			"<input type='submit'>\n</form><br/>".			
			"<a href='?page=edit".
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
	
	// Get the total count in the case that it needs limiting
	$query = "SELECT COUNT(*) as count
			FROM systems
			JOIN games
				ON systems.id=games.system
			JOIN sources
				ON games.source=sources.id
			WHERE ".
				($source_set ? "sources.id=".$source : "").
				($source_set && $system_set ? " AND " : "").
				($system_set ? "systems.id=".$system : "");
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
				($source_set ? "sources.id=".$source : "").
				($source_set && $system_set ? " AND " : "").
				($system_set ? "systems.id=".$system : "").
			" ORDER BY games.name ASC
			LIMIT 50 OFFSET ".($offset == "" ? "0" : ($offset*5)."0");
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
			echo "<a href='?page=edit&game=".$game["id"]."'>".$game["name"];
			if ($source_set && !$system_set)
			{
				echo " (".$game["manufacturer"]." - ".$game["system"].")";
			}
			if (!$source_set && $system_set)
			{
				echo " (".$game["source"].")";
			}
			echo "</a><br/>\n";
		}
		echo "<br/>";
		if ($offset != "" && $offset > 0)
		{
			echo "<a href='?page=edit&system=".$system."&source=".$source."&offset=".($offset-1)."'>Last 50</a>   ";
		}
		if ($count > (mysqli_num_rows($games_result) + ($offset == "" ? 0 : $offset*50)))
		{
			echo "<a href='?page=edit&system=".$system."&source=".$source."&offset=".($offset+1)."'>Next 50</a>";
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