<?php

// Original code: The Wizard of DATz

$dirs = array(
	'http://amstrad.es/juegosamstrad/todos-los-juegos/index.html',
	'http://amstrad.es/juegosamstrad/demos/index.html',
	'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index.html',
	'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_1.html',
	'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_2.html',
	'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_3.html',
	'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_4.html',
	//'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_5.html',
	//'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_6.html',
	//'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_7.html',
	//'http://amstrad.es/juegosamstrad/zonadeaventura/aventuras/index_8.html',
	'http://amstrad.es/programas/amsdos/utilidades/index.html',
	'http://amstrad.es/programas/amsdos/educativos/index.html',
	'http://gx4000.amstrad.es/juegos/index.html',
	'http://gx4000.amstrad.es/juegos/index_1.html',
	'http://gx4000.amstrad.es/juegos/index_2.html',
	'http://gx4000.amstrad.es/juegos/index_3.html',
	'http://gx4000.amstrad.es/juegos/index_4.html',
	'http://gx4000.amstrad.es/juegos/index_5.html',
	'http://gx4000.amstrad.es/juegos/index_6.html',
	'http://gx4000.amstrad.es/juegos/index_7.html',
	'http://gx4000.amstrad.es/juegos/index_8.html',
	'http://amstrad.es/publicaciones/publicaciones/cintas/index.html',
);

echo "<table>\n";
foreach ($dirs as $dir)
{
	echo "<tr><td>".$dir."</td>";
	
	$query = get_data($dir);
	
	preg_match_all("/<p>\s*<a href=\"(.*?)\">(.*?)<\/a>(.*?)<br>/s", $query, $links_a);
	preg_match_all("/<a class=\"teaserlink\" href=\"(.*?)\">(.*?)<\/a>(.*?)<br>/s", $query, $links_b);
	
	$links1 = array_merge($links_a[1], $links_b[1]);
	$links2 = array_merge($links_a[2], $links_b[2]);
	$links3 = array_merge($links_a[3], $links_b[3]);
	
	$links = array();
	for ($index = 0; $index < sizeof($links1); $index++)
	{
		$links[] = array(trim($links1[$index]), trim($links2[$index]), trim($links3[$index]));
	}

	$t_dir = explode("/", $dir);
	$t_dir[count($t_dir) - 1] = null;
	$t_dir = implode("/", $t_dir);
	
	$new = 0;
	$old = 0;

	foreach ($links as $row)
	{		
		$url = $t_dir.$row[0];
		$text = strtr($row[1].' ('.str_replace(', ',') (',$row[2]).')', $normalize_chars);
		
		$query2 = get_data($url);
		
		preg_match_all("/<a href=\"(.*?)\">(.*?)<\/a>/", $query2, $links);
		$newlinks = array();
		for ($index = 0; $index < sizeof($links[0]); $index++)
		{
			$newlinks[] = array($links[1][$index], strip_tags($links[2][$index]));
		}

		$dl_dir = explode("/", $url);
		$dl_dir[count($dl_dir) - 1] = null;
		$dl_dir = implode("/", $dl_dir);

		foreach ($newlinks as $dl)
		{
			// If the link is a JS call, ignore it
			if (preg_match("/onclick=\"javascript/", $dl[0]) == 1)
			{
				continue;
			}
			
			$url2 = str_replace("\" target=\"_blank", "", $dl_dir.$dl[0]);
			$ext = pathinfo($url2, PATHINFO_EXTENSION);
			$dltext = trim(strip_tags($dl[1]));
			
			if ($dltext !== "" && $dltext != 'Share')
			{
				if (!$r_query[$url2])
				{
					$found[] = array($text.' {'.$dltext.'}.'.$ext, $url2);
					$new++;
				}
				else
				{
					$old++;
				}
			}
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
	echo "<a href='".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>