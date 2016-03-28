<?php

/* ------------------------------------------------------------------------------------
 Deheader files in a folder
 Original code by Matt Nadareski (darksabre76)

 Requires:
 dir		Directory to deheader files from
 type		Type of the roms to be deheadered
 ------------------------------------------------------------------------------------ */

// Type mapped to header size (in decimal bytes)
$types = array(
		"a7800" => 128,
		"fds" => 16,
		"lynx" => 64,
		"nes" => 16,
		"snes" => 512,
);

$dir = (isset($_GET["dir"]) ? urldecode($_GET["dir"]) : "");
$type = (isset($_GET["type"]) ? $_GET["type"] : "");

echo "<h2>Remove File Headers</h2>";

// If the directory isn't set or it's not a directory, prompt the user to enter a directory and set which type it is
if (trim($dir) == "" || !is_dir($dir))
{
	echo <<<EOL
<form type="get">
	<input type="hidden" name="page" value="deheader">
	<b>Enter a directory name: </b> <input type="text" name="dir"><p/>
	<b>Select rom type: </b><br/>
EOL;
	
	foreach ($types as $typename => $val)
	{
		echo "\t<input type=\"radio\" name=\"type\" value=\"".$typename."\">".$typename."<br/>\n";
	}
	
	echo <<<EOL
	<br/>
	<input type="submit" value="Submit"><br/>
</form><p/>
EOL;
}

// If a directory is given but the type isn't defined or it's incorrect, give the options for file type
elseif (trim($type) == "" || !$types[$type] === "")
{
	echo <<<EOL
<form type="get">
	<input type="hidden" name="page" value="deheader">
	<input type="hidden" name="dir" value="$dir">
	<b>Select rom type: </b><br/>
EOL;
	
	foreach ($types as $typename => $val)
	{
		echo "\t<input type=\"radio\" name=\"type\" value=\"".$typename."\">".$typename."<br/>\n";
	}
	
	echo <<<EOL
	<br/>
	<input type="submit" value="Submit"><br/>
</form><p/>
EOL;
}

// Otherwise, process the folder
else
{
	$dir = $dir."/";	
	$roms = scandir($dir);
	echo "<table>\n<tr><th>Name</th><th>Has Header</th></tr>\n";
	foreach ($roms as $rom)
	{
		if ($rom == "." || $rom == ".." || is_dir($dir.$rom))
		{
			continue;
		}
		
		$hs = $types[$type];
		
		$handle = fopen($dir.$rom, "r");
		$header = fread($handle, $hs);
		$header = bin2hex($header);
		$header = strtoupper($header);
		
		switch ($type)
		{
			case "a7800":
				$a7800a = preg_match("/^.415441524937383030/", $header);
				$a7800b = preg_match("/^.{64}41435455414C20434152542044415441205354415254532048455245/", $header);
				
				$has_header = ($a7800a == 1 || $a7800b == 1);
				break;
			case "fds":
				$fdsa = preg_match("/^4644531A010000000000000000000000/", $header);
				$fdsb = preg_match("/^4644531A020000000000000000000000/", $header);
				$fdsc = preg_match("/^4644531A030000000000000000000000/", $header);
				$fdsd = preg_match("/^4644531A040000000000000000000000/", $header);
				
				$has_header = ($fdsa == 1 || $fdsb == 1 || $fdsc == 1 || $fdsd == 1);
				break;
			case "lynx":
				$lynxa = preg_match("/^4C594E58/", $header);
				$lynxb = preg_match("/^425339/", $header);
				
				$has_header = ($lynxa == 1 || $lynxb == 1);
				break;
			case "nes":
				$nes = preg_match("/^4E45531A/", $header);
				
				$has_header = ($nes == 1);
				break;
			case "snes":
				$fig = preg_match("/^.{16}0000000000000000/", $header);
				$smc = preg_match("/^.{16}AABB040000000000/", $header);
				$ufo = preg_match("/^.{16}535550455255464F/", $header);
				
				$has_header = ($fig == 1 || $smc == 1 || $ufo == 1);
				break;
			default:
				$has_header = false;
				break;
		}
		
		echo "<tr><td>".$rom."</td><td style='text-align: center'>".($has_header ? "true" : "false")."</td></tr>\n";
	
		if ($has_header)
		{
			$data = fread($handle, filesize($dir.$rom) - 512);
			$outhandle = fopen($dir.$rom.".new", "w");
			fwrite($outhandle, $data);
			fclose($outhandle);
		}
	
		fclose($handle);
	}
	echo "</table>\n";
}

?>