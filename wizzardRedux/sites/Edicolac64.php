<?php

// Original code: The Wizard of DATz

print "<pre>";

$baseURL = 'http://www.edicolac64.com/public/';

$urls = array(
	'cassette_e_riviste_c64.php',
	'dischi_e_riviste_c64.php',
	'cassette_e_riviste_c16.php',
	'cassette_e_riviste_vic20.php',
);

foreach ($urls as $url)
{
	print "load ".$baseURL.$url."\n";

	$query = get_data($baseURL.$url);
	$query = explode('<tr>', str_replace('HREF=', 'href=', $query));
	$query[0] = null;

	foreach ($query as $id)
	{
		if ($id)
		{
			$id = explode('href=', $id);
			$id = explode('>', $id[1]);
			$id = str_replace("'", "", $id[0]);

			$new = 0;
			$old = 0;
			$error = 0;

			if ($id)
			{
				print "found ".$baseURL.$id."\n";

				$query2 = get_data($baseURL.$id);
				$query2 = explode('<tr>', $query2);
				$query2[0] = null;
				foreach ($query2 as $row)
				{
					if ($row)
					{
						$dlink = explode('href=', $row);
						$dlink = explode('>', $dlink[1]);
						$dlink = $dlink[0];
						if ($dlink)
						{
							$text = explode('</td>', $row);
							$text = trim(str_replace("\r\n", '', strip_tags($text[0])));

							if (!$r_query[$dlink])
							{
								$page = get_data($baseURL.$dlink);
								$dl = explode('href=download', $page);
								$dls = array();
								
								if (!$dl[1])
								{
									$helper = explode('id_gioco=', $page);

									if ($helper[1])
									{
										$helper_id = explode("'", $helper[1]);
										$helper_id = $helper_id[0];
										$helper_url = explode("href='", $helper[0]);
										$helper_url = $baseURL.$helper_url[count($helper_url) - 1].'id_gioco='.$helper_id;
										print "\t".$helper_url."\n";

										$page = get_data($helper_url);
										$dl = explode('href=download', $page);
										$dl = explode('=', $dl[1]);
										$dl = explode('>', $dl[1]);
										$dls[] = $dl[0];
									}
								}
								else
								{
									for ($x = 1; $x < count($dl); $x++)
									{
										$dl[$x] = explode('=', $dl[$x]);
										$dl[$x] = explode('>', $dl[$x][1]);
										$dls[] = $dl[$x][0];
									}
								}

								if ($dls)
								{
									foreach($dls as $dl)
									{
										$found[] = array($dlink, $text, $dl);
									}
									$new++;
								}
								else
								{
									$error++;
									print "\terror: ".$baseURL.$dlink."\n";
								}
							}
							else
							{
								$old++;
							}
						}
					}
				}

				print "\tnew: ".$new.", old: ".$old.", error: ".$error."\n";
			}
		}
	}
}

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print $row[2]."\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=".$baseURL.$row[0]." target=_blank>".$row[0]."</a>\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=http://www.edicolac64.com/download_center_lite/index.php?".$row[2].".rar target=_blank>".$row[1].".rar</a>\n";
}

print "</td></tr></table>";

$new = 0;
$old = 0;

$r_query = implode('', file("../sites/".$source."_2.txt"));
$r_query = explode("\r\n", $r_query);
$r_query = array_flip($r_query);

$found = array();

$url2 = "http://www.edicolac64.com/public/giochi_made_in_italy_c64.php";

print "load ".$url2."\n";

$query = get_data($url2);
$query = explode('<TABLE', $query);
$query = explode('</TABLE>', $query[1]);
$query = explode('<TR>', $query[0]);
$query[0] = null;

foreach ($query as $row)
{
	if ($row)
	{
		$url = explode('HREF="', $row);
		$url = explode('"', $url[1]);
		$url = explode('=', $url[0]);
		$url = $url[1];

		$text = trim(strip_tags($row));

		if (!$r_query[$text])
		{
			$found[] = array($url, $text);
			$new++;
		}
		else
		{
			$old++;
		}
	}
}

print "\tnew: ".$new.", old: ".$old."\n";

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print $row[1]."\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=http://www.edicolac64.com/download_center_lite/index.php?".$row[0].".rar target=_blank>".$row[0].".rar</a>\n";
}

print "</td></tr></table>";


$new = 0;
$old = 0;

$found = array();

$url2 = "http://www.edicolac64.com/public/materiale_amatoriale.php";

print "load ".$url2."\n";

$query = get_data($url2);
$query = explode('<table ', $query);
$query = explode('</TABLE>', $query[1]);
$query = explode('<tr>', $query[0]);
$query[0] = null;

foreach ($query as $row)
{
	if ($row)
	{
		$row = explode('href=', $row);
		$url = explode('>', $row[1]);
		$url = explode('=', $url[0]);
		$url = $url[1];

		$text = explode('</A>', $row[1]);
		$text = strip_tags('<a href='.$text[0]);

		if (!$r_query[$text])
		{
			$found[] = array($url, $text);
			$new++;
		}
		else
		{
			$old++;
		}
	}
}

print "\tnew: ".$new.", old: ".$old."\n";

print "<table><tr><td><pre>";

foreach ($found as $row)
{
	print $row[1]."\n";
}

print "</td><td><pre>";

foreach ($found as $row)
{
	print "<a href=http://www.edicolac64.com/download_center_lite/index.php?".$row[0].".rar target=_blank>".$row[1].".rar</a>\n";
}

print "</td></tr></table>";
?>