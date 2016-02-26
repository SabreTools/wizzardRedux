<?php

// Original code: The Wizard of DATz

print "<pre>";

$newfiles = array(
	'http://retrospec.sgn.net/users/tomcat/yu/ZX_list.php',
	'http://retrospec.sgn.net/users/tomcat/yu/C64_list.php',
	'http://retrospec.sgn.net/users/tomcat/yu/Amiga_list.php',
	'http://retrospec.sgn.net/users/tomcat/yu/CPC_list.php',
	'http://retrospec.sgn.net/users/tomcat/yu/Plus4_list.php',
	'http://retrospec.sgn.net/users/tomcat/yu/ST_list.php',
	'http://retrospec.sgn.net/users/tomcat/yu/Galaksija_list.php',
	'http://retrospec.sgn.net/users/tomcat/yu/Orao_list.php',
	'http://retrospec.sgn.net/users/tomcat/yu/Pecom_list.php',
);

foreach ($newfiles as $newfile)
{
	print "load ".$newfile."\n";
	$query = implode('', file($newfile));
 	$query = explode('<tr onmouseover', $query);
	$query[0] = null;

	$old = 0;
	$new = 0;

	foreach ($query as $row)
	{
		if ($row)
		{
			$row = explode('</tr>', $row);
			$row = explode('<td', $row[0]);

			$title = trim(strip_tags('<td'.$row[2]));
			if ($title)
			{
				$lastTitle = $title;
			}
			else
			{
				$title = $lastTitle;
			}

			$info = trim(strip_tags('<td'.$row[3]));

			$url = explode('<a href="', $row[5]);
			$url = explode('"', $url[1]);

			if ($url[0])
			{
				$url = $url[0];
	
				$ext = explode('.', $url);
				$ext = $ext[count($ext) - 1];
	
				$title = $title." (".$info.").".$ext;
	
		    	if ($r_query[$url])
				{
					$old++;
				}
				else
				{
					$found[] = array($url, $title);
					$new++;
				}
			}
		}
	}

	print "found new:".$new.", old:".$old."\n\n";
}

$newfiles = array(
	'http://retrospec.sgn.net/users/tomcat/yu/TRDosReCracks.php',
);

foreach ($newfiles as $newfile)
{
	print "load ".$newfile."\n";
	$query = implode('', file($newfile));
 	$query = explode('<TR>', $query);
	$query[0] = null;

	$old = 0;
	$new = 0;

	foreach($query as $row)
	{
		if ($row)
		{
			$row = explode('<td', $row);

			$title = trim(strip_tags('<td'.$row[2]));
			if ($title)
			{
				$lastTitle = $title;
			}
			else
			{
				$title = $lastTitle;
			}
			
			$info = array();

			for ($x = 5; $x <= 8; $x++)
			{
				$temp = trim(strip_tags('<td'.$row[$x]));
				if ($temp)
				{
					$info[] = $temp;
				}
			}

			$url = explode('<a href="', $row[4]);
			$url = explode('"', $url[1]);

			if ($url[0])
			{
				$url = $url[0];
	
				$ext = explode('.', $url);
				$ext = $ext[count($ext) - 1];
	
				$title = $title." (".implode(') (', $info).").".$ext;
	
		    	if ($r_query[$url])
				{
					$old++;
				}
				else
				{
					$found[] = array($url, $title);
					$new++;
				}
   			}
		}
	}

	print "found new:".$new.", old:".$old."\n\n";
}

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print $row[0]."\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=\"http://retrospec.sgn.net/users/tomcat/yu/".$row[0]."\">".$row[1]."</a>\n";
}

print "</td></tr></table>";

?>