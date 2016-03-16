<?php

// Original code: The Wizard of DATz

$dirs = array(
	'http://cah4e3.shedevr.org.ru/dumping_2016.php',
	'http://cah4e3.shedevr.org.ru/dumping_2015.php',
	'http://cah4e3.shedevr.org.ru/dumping_2014.php',
	'http://cah4e3.shedevr.org.ru/dumping_2013.php',
	'http://cah4e3.shedevr.org.ru/dumping_2012.php',
	'http://cah4e3.shedevr.org.ru/dumping_2011.php',
	'http://cah4e3.shedevr.org.ru/dumping_2010.php',
	'http://cah4e3.shedevr.org.ru/dumping_2009.php',
	'http://cah4e3.shedevr.org.ru/dumping_2008.php',
	'http://cah4e3.shedevr.org.ru/dumping_2007.php',
	'http://cah4e3.shedevr.org.ru/dumping_2006.php',
	'http://cah4e3.shedevr.org.ru/dumping_2005.php',
	'http://cah4e3.shedevr.org.ru/dumping_2004.php',
	'http://cah4e3.shedevr.org.ru/dumping_2003.php',
	'http://cah4e3.shedevr.org.ru/dumping_other.php',
	'http://cah4e3.shedevr.org.ru/dumping_sachen.php',
	'http://cah4e3.shedevr.org.ru/decr.php',
);

echo "<table>\n";
foreach ($dirs as $dir)
{
	echo "<tr><td>".$dir."</td>";
	$query = get_data($dir);
	
	preg_match_all("/<a.*?href=\"(.*?)\">(.*?)<\/a>/", $query, $query);
	
	$newrows = array();
	for ($index = 0; $index < sizeof($query[0]); $index++)
	{
		$newrows[] = array($query[1][$index], $query[2][$index]);
	}

	$new = 0;
	$old = 0;
	$other = 0;

	foreach ($newrows as $row)
	{
		$url = $row[0];
		$ext = pathinfo($dl, PATHINFO_EXTENSION);
		$title = $row[1];
		
		if ($ext == 'rar')
		{
			if (!$r_query[$url])
			{
				$found[] = array($title, "http://cah4e3.shedevr.org.ru/".$url);
				$new++;
			}
			else
			{
				$old++;
			}
		}
		else
		{
			$other++;
		}
	}
	echo "<td>Found new: ".$new.", old: ".$old.", other: ".$other."</tr>\n";
}
echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>