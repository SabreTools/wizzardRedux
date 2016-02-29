<?php

// Original code: The Wizard of DATz
$r_query = array_flip($r_query);
$z_query = array();

foreach ($r_query as $row)
{
	$row = explode("\t", $row);
	$z_query[preg_replace('/\W/', null, strtr($row[1], $normalize_chars))] = true;
}

print "<pre>";
$start = 0;

for ($x = $start; $x < $start + 100; $x++)
{
	$new = 0;
	$old = 0;

	$query = get_data("http://computeremuzone.com/?id=games&cat=&val=&order=nom&pag=".$x);
	$query = explode('<table width="100%" border=0 cellpadding="0" cellspacing="0" cols=8 >', $query);
	$query = explode('</table>', $query[1]);
	$query = explode('<tr', $query[0]);

	if ($query[2])
	{
		$query[0] = null;
		$query[1] = null;
		foreach ($query as $row)
		{
        	if ($row)
        	{
				$gametitle = explode('<td class="Juegos">', $row);
				$gametitle[1] = explode('</a>', $gametitle[1]);
				$gametitle[1] = trim(strip_tags($gametitle[1][0]));
				$gametitle[2] = explode('</td>', $gametitle[2]);
				$gametitle[2] = trim(strip_tags($gametitle[2][0]));
				$gametitle[3] = explode('</td>', $gametitle[3]);
				$gametitle[3] = trim(strip_tags($gametitle[3][0]));

				$gametitle = $gametitle[1]." (".$gametitle[2]." ".$gametitle[3].")";

				$dls = explode('contador.php?', $row);
				$dls[0] = null;
			
				foreach ($dls as $dl)
				{
					if ($dl)
					{
						$dl_url = explode('"', $dl);
						$dl_url = $dl_url[0];
						$dl_ext = explode('&n_ar=',$dl_url);
						$dl_ext = explode('&dd=', $dl_ext[0]);
						$dl_ext = explode('.', $dl_ext[0]);
						$dl_ext = $dl_ext[count($dl_ext) - 1];
			
						$dl_type = explode('<', $dl);
						$dl_type = explode('>', $dl_type[0]);
						$dl_type = $dl_type[1];

						if ($z_query[preg_replace('/\W/', null, strtr($dl_url, $GLOBALS['normalize_chars']))])
						{
							$old++;
						}
						else
						{
							$new++;
							$found[] = array($dl_type, $dl_url, $gametitle.'.'.$dl_ext);
						}
					}
				}
			}
		}
	}
	else
	{
		break;
	}
		
	print "load http://computeremuzone.com/?id=games&cat=&val=&order=nom&pag=".$x."\tnew: ".$new.", old: ".$old."\n";
}

sort($found);

foreach ($found as $Download)
{
	print $Download[0]."\t".$Download[1]."\t".$Download[2]."\n";
}

print "\n<a href=computeremuzone/xml.php>xml</a>";

?>