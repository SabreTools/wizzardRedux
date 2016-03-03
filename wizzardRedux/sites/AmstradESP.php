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
	$query = str_replace("\r\n", '', get_data($dir));
	$query = explode('<p>  <a href="', $query);

	if (!$query[1])
	{
		$query = explode('<a class="teaserlink" href="', $query[0]);
	}

	$query[0] = null;

	$t_dir = explode("/", $dir);
	$t_dir[count($t_dir) - 1] = null;
	$t_dir = implode("/", $t_dir);

	foreach ($query as $row)
	{
		$new = 0;
		$old = 0;

		$url = explode('"', $row);
		$url = $t_dir.$url[0];

		//print "found: ".$url."\n";

		$text = explode('</a>', $row);
		$ext = explode('<', $text[1]);
		$text = explode('>', $text[0]);
		$text = trim($text[1]);
		$ext = trim($ext[0]);
		$text = strtr($text.' ('.str_replace(', ',') (',$ext).')', $normalize_chars);
		$query2 = get_data($url);
		$query2 = explode('<a href="', $query2);
		$query2[0] = null;

		$dl_dir = explode("/", $url);
		$dl_dir[count($dl_dir) - 1] = null;
		$dl_dir = implode("/", $dl_dir);

		foreach ($query2 as $dl)
		{
				$url2 = explode('"', $dl);
				$url2 = $dl_dir.$url2[0];
					
				$ext = explode(".", $url2);
				$ext = $ext[count($ext) - 1];

				$dltext = explode('</a>', $dl);
				$dltext = trim(strip_tags('<a href="'.$dltext[0]));

				if ($dltext && $dltext != 'Share')
				{
					if (!$r_query[$url2])
					{
						$found[] = array($url2,$text.' {'.$dltext.'}.'.$ext);
						$new++;
					}
					else
					{
						$old++;
					}
				}
		}

		echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";
	}
}

echo "</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='".$row[0]."'>".$row[1]."</a><br/>\n";
}

echo "<br/>\n";

?>