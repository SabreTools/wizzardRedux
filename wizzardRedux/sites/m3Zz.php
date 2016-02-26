<?php

print "<pre>";

loadDir('srost/');

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"https://content.wuala.com/contents/".$url[1]."?dl=1&key=ROMCollections\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

function loadDir($dir)
{
	GLOBAL $r_query, $found;
	$new = 0;
	$old = 0;
	$folder = 0;

	print "open ".$dir."\n";
	$query = implode('', file("https://api2.wuala.com/previewSorted/".$dir."?il=1&ff=0&key=ROMCollections"));

	$publicFolders = explode('publicFolders', $query);
	$publicFolders = explode('breadcrumb', $publicFolders[1]);
	$publicFolders = explode('url="https://www.wuala.com/', $publicFolders[0]);
	array_splice($publicFolders, 0, 1);

	foreach ($publicFolders as $publicFolder)
	{
		$publicFolder = explode('"', $publicFolder);
		$publicFolder = $publicFolder[0];
		loadDir($publicFolder);
		$folder++;
	}

	$publicFiles = explode('publicFiles', $query);
	$publicFiles = explode('publicFolders', $publicFiles[1]);
	$publicFiles = explode('<item ', $publicFiles[0]);
	array_splice($publicFiles, 0, 1);

	foreach ($publicFiles as $publicFile)
	{
		$url = explode('url="https://www.wuala.com/', $publicFile);
		$url = explode('"', $url[1]);
		$url = $url[0];

		$name = explode('name="', $publicFile);
		$name = explode('"', $name[1]);
		$name = $name[0];

		if (!$r_query[$url])
		{
			$found[] = array($name, $url);
			$new++;
		}
		else
		{
			$old++;
		}
	}

	print "close ".$dir."\n";
	print "new: ".$new.", old: ".$old.", folder: ".$folder."\n";
}

?>