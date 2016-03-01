<?php

// Original code: The Wizard of DATz

$r_query = array_flip($r_query);

foreach($r_query as $row)
{
	$row = explode("\t", $row);
	$r_query[$row[0]] = true;
}

print "<pre>";

$urls = array(
	'http://www.konamito.com/juegos/?pageNum_juegos=0&opt=1',
	'http://www.konamito.com/juegos/?pageNum_juegos=1&opt=1',
	'http://www.konamito.com/juegos/?pageNum_juegos=0&opt=7',
	'http://www.konamito.com/juegos/?pageNum_juegos=1&opt=7',
);

foreach ($urls as $url)
{
	print "load: ".$url."\n";
	$query = get_data($url);
	$query = explode("/ficha/?id=", $query);

	$query[0] = null;

	foreach ($query as $row)
	{
		if ($row)
		{			
			$row = explode('"', $row);
			$row = explode("'", $row[0]);
			$found[$row[0]] = $row[0];

			print $row[0]." ";
		}
	}

	print "\n";
}

$new = 0;
$old = 0;

foreach ($found as $page)
{
	sleep(1);
	$query = get_data("http://www.konamito.com/ficha/?id=".$page);

	if (!$query)
	{
		break;
	}

	$query = explode("http://www.konamito.com/games/download.php?", utf8_decode($query));
	$title = explode("<title>MSXBlog de Konamito : ", $query[0]);
	$title = explode("<", $title[1]);
	$query[0] = null;

	foreach ($query as $row)
	{
		if ($row)
		{
			$name = explode('<', $row);
			$name = explode('>', $name[0]);
			$row = explode('"', $row);
			$row = explode("'", $row[0]);
			$row = explode("&#038;", $row[0]);
			if (count($row) == 2)
			{
				sort($row);
				$row = $row[1]."&".$row[0];
			
				if ($name[1] == 'Descargar')
				{
					$add = null;
				}
				else
				{
					$add = " (".str_replace(array("\n", "\r", "\t"), null, $name[1]).")";
				}

				if (!$r_query[$row])
				{
					print $row."\t".$title[0].$add."\n";
					$r_query[$row] = true;
					$new++;
				} else
				{
					$old++;
				}
			}
		}
	}
}

print "\nnew: ".$new.",old: ".$old."\n<a href=Konamito/xml.php>xml</a>";

?>