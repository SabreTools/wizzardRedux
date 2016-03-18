<?php

// Original code: The Wizard of DATz

// TODO: Figure out what the 'compare' block actually does

$type = (isset($_GET["type"]) ? $_GET["type"] : "");

if ($type == 'search')
{	
	$newfiles = array(
		'http://atarionline.pl/',
	);
	
	echo "<table>\n";
	foreach ($newfiles as $newfile)
	{
		echo "<tr><td>".$newfile."</td>";
		$query = get_data($newfile);
		
		preg_match_all("/<a href=\"\/archiwa\/(.*?)\?.*?\"/", $query, $query);
	
		$old = 0;
		$new = 0;
	
		foreach ($query[1] as $row)
		{
			$row = trim($row);

		  	if ($r_query[$row] !== "")
			{
				$old++;
			}
			else
			{
				$found[] = $row;
				$new++;
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
		echo "<a href='http://atarionline.pl/archiwa/".$row."'>".$row."</a><br/>\n";
	}
	
	echo "<br/>\n";	
}
elseif ($type == 'compare')
{
	if (sizeof($_FILES) > 0)
	{
		$file_old = implode('', file($_FILES["file_old"]["tmp_name"]));
		$file_old = explode("\r\n", str_replace('\\', '+', str_replace('|', ' ', $file_old)));
		array_splice($file_old, 0, 3);
		$patharray = array();
		$file_old_b = array();
		foreach ($file_old as $file)
		{
			$split = explode("+---", $file);
			if ($split[1])
			{
				$pathLevel = strlen($split[0]) / 4;
				$patharray[$pathLevel] = $split[1];
				$path = "";
				for ($y = 0; $y <= $pathLevel; $y++)
				{
					$path = $path.$patharray[$y]."\\";
				}
			}
			else
			{
				$file = trim($file);
				if ($file)
				{
					$file_old_b[$path.$file] = 1;
				}
			}
		}

		$file_new = implode('', file($_FILES["file_new"]["tmp_name"]));
		$file_new = explode("\r\n", str_replace('\\', '+', str_replace('|', ' ', $file_new)));
		$dir = str_replace('+', '\\', $file_new[2]);
		array_splice($file_new, 0, 3);
		$patharray = array();

		foreach ($file_new as $file)
		{
			$split = explode("+---", $file);
			if ($split[1])
			{
				$pathLevel = strlen($split[0]) / 4;
				$patharray[$pathLevel] = $split[1];
				$path = "";
				for ($y = 0; $y <= $pathLevel; $y++)
				{
					$path = $path.$patharray[$y]."\\";
				}
			}
			else
			{
				$file = trim($file);
				if ($file)
				{
					if (!$file_old_b[$path.$file])
					{
						print "copy \"".$dir."\\".$path.$file."\" \"".str_replace('\\','^',$path.$file)."\"<br>";
					}
				}
			}
		}
	}
	else
	{
		print'<pre><form method = "post" enctype = "multipart/form-data" action = "?action = onlinecheck&source = AtariOnline&type = compare">
		old file	<input type = "file" name = "file_old" size = "40">
		new file	<input type = "file" name = "file_new" size = "40">
				<input type = "submit" value = "Send">
		</form>';
	}
}
else
{
	print "<pre>";
	print "load <a href='?page=onlinecheck&source=AtariOnline&type=search'>search</a>\n";
	print "load <a href='?page=onlinecheck&source=AtariOnline&type=compare'>compare</a>\n";
}

?>