<?php

// Original code: The Wizard of DATz

print "<pre>";

$dirs = array();

$max = 100000;

for ($pagetype = 0; $pagetype < 2; $pagetype++)
{
	for ($page = 1; $page < $max; $page++)
	{
		$dir = "http://zxaaa.untergrund.net/view_demos.php?t=".$pagetype."&np=".$page;
		print "load: ".$dir."\n";
		$query = implode('', file($dir));
		$query = explode('  <tr',$query);
		array_splice($query, 0, 1);
	
		$new = 0;
		$old = 0;
	
		$notFound = true;
	
		foreach ($query as $row)
		{
			$row = explode('<td', $row);
			$title = trim(strip_tags('<td'.$row[1]));
	
			$info = array();
	
			for ($x = 4; $x < 9; $x++)
			{
				$temp = trim(strip_tags('<td'.$row[$x]));
				if ($temp)
				{
					$info[] = $temp;
				}
			}
	
			if ($info)
			{
				$title = $title." (".implode(") (", $info).")";
			}
	
			$DLs = explode('get.php?f=', $row[1]);
			array_splice($DLs, 0, 1);
	
			foreach ($DLs as $DL)
			{
				$DL = explode('"', $DL);
				$DL = $DL[0];
	
				$ext = explode('.', $DL);
				$ext = $ext[count($ext) - 1];
	
				if (!$r_query[$DL])
				{
					$found[] = array($title.".".$ext, $DL);
					$new++;
					$r_query[$DL] = true;
				}
				else
				{
					$old++;
				}
	
				$notFound = false;
	        }
		}
		
		if ($notFound)
		{
			$page = $max;
		}

		print "new: ".$new.", old: ".$old."\n";
	}
}

$dirs = array(
	'http://zxaaa.untergrund.net/DEMA.html',
	'http://zxaaa.untergrund.net/INTRA.html',
);

$dirsb = array(
	array('intbis1998.html','BIS From Rush'),
	array('intbis1999.html','BIS From Rush'),
	array('intbis2000.html','BIS From Rush'),
	array('intbis2001.html','BIS From Rush'),
	array('intbis2002.html','BIS From Rush'),
	array('intbis2003.html','BIS From Rush'),
	array('intbis2004.html','BIS From Rush'),
	array('intbis2005.html','BIS From Rush'),
	array('intbis2006.html','BIS From Rush'),
	array('intbis2007.html','BIS From Rush'),
	array('sgteam2007.html','SG-Team'),
	array('sgteam2008.html','SG-Team'),
	array('sgteam2009.html','SG-Team'),
	array('sgteam2010.html','SG-Team'),
	array('sgteam2011.html','SG-Team'),
	array('sgteam2012.html','SG-Team'),
	array('STARYO.html','Diski Staryo',true),
	array('DISKAAA.html','Disk Colection\'s AAA',true),
	array('DISKAAA2.html','Disk Colection\'s AAA',true),
	array('DISKAAA3.html','Disk Colection\'s AAA',true),
	array('DISKAAA4.html','Disk Colection\'s AAA',true),
	array('DISALON.html','Wlodek',true),
	array('IRONDISK.html','Iron',true),
	array('ZXCHIP.html','ZX CHIP',true),
	array('ZXCHIP2.html','ZX CHIP',true),
	array('FICUS.html','Ficus Demo Collections',true),
	array('FICUS2.html','Ficus Demo Collections',true),
	array('FICUSG.html','Flash Inc Games Collections',true),
	array('FICUSG2.html','Flash Inc Games Collections',true),
	array('flashrelize.html','Flash Inc Relize',true),
	array('MAGICSOFTSYS.html','',true),
);

foreach ($dirs as $dir)
{
	print "load: ".$dir."\n";

	$query = implode('', file($dir));
	$query = explode('<td><a href="', $query);
	array_splice($query, 0, 1);

	foreach ($query as $row)
	{
		$row = explode('"', $row);
		if ($row[6])
		{
			$dirsb[] = array($row[0], $row[6]);
		}
	}
}

foreach ($dirsb as $dir)
{
	$cdir = "http://zxaaa.untergrund.net/".$dir[0];
	print "load: ".$cdir."\n";

	$new = 0;
	$old = 0;

	$query = implode('', file($cdir));
	$query = str_replace("alt='", 'alt="', $query);
	$query = str_replace("'>", '">', $query);
	$query = explode('<a href="', $query);
	array_splice($query, 0, 1);

	foreach ($query as $row)
	{
		$DL = explode('"', $row);
		$DL = $DL[0];

		$title = explode('alt="', $row);
		$title = explode('"', $title[1]);
		$title = $title[0];

		if ($title)
		{
			$ext = explode('.', $DL);
			$ext = $ext[count($ext) - 1];

			if ($dir[2])
			{
				$titel = $dir[1]." ".$title.".".$ext;
			}
			else
			{
				$titel = $title." (".$dir[1].").".$ext;
			}
	
			if (!$r_query[$DL])
			{
				$found[] = array($titel, $DL);
				$new++;
				$r_query[$DL] = true;
			}
			else
			{
				$old++;
			}
		}
	}

	print "new: ".$new.", old: ".$old."\n";
}

$dirsc = array();

$dir = "http://zxaaa.untergrund.net/GAME.html";
print "load: ".$dir."\n";
$query = implode('', file($dir));
$query = explode("<a href=\"javascript:jumpto('", $query);
array_splice($query, 0, 1);

foreach ($query as $row)
{
	$row = explode("'", $row);
	$dirsc[] = array($row[0], 'ZX Chip Volgodonsk Games Collections');
}

foreach ($dirsc as $dir)
{
	$cdir = "http://zxaaa.untergrund.net/".$dir[0];
	print "load: ".$cdir."\n";

	$new = 0;
	$old = 0;

	$query = implode('', file($cdir));
	$query = explode('<a href="', $query);
	array_splice($query, 0, 1);

	foreach ($query as $row)
	{
		$DL = explode('"', $row);
		$DL = $DL[0];

		$title = explode('>[', $row);
		$title = explode(']<', $title[1]);
		$title = $title[0];

		if ($title)
		{
			$ext = explode('.', $DL);
			$ext = $ext[count($ext) - 1];

			$titel = $dir[1]." ".$title.".".$ext;

			if (!$r_query[$DL])
			{
				$found[] = array($titel, $DL);
				$new++;
				$r_query[$DL] = true;
			}
			else
			{
				$old++;
			}
  		}
	}

	print "new: ".$new.", old: ".$old."\n";
}


print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach ($found as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"http://zxaaa.untergrund.net/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>