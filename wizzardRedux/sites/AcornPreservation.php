<?php

// Original code: The Wizard of DATz

$base_dl_url = "http://www.8bitchip.info/";
$pages = Array(
	"http://www.acornpreservation.org/main_tapes_games.html",
	"http://www.acornpreservation.org/main_discs_games.html",
);

foreach ($pages as $page)
{
	print "load ".$page."<br/>\n";

	$old = 0;
	$new = 0;
	$other = 0;

	$content = implode('', file ($page));
	$content = explode('HREF="', $content);
	$content[0] = null;

	foreach ($content as $row)
	{
		if ($row)
		{
			$url = explode('"', $row);
			$url = $url[0];
			$ext = explode('.', $url);

			if (strtolower($ext[count($ext)-1])=='zip')
			{
				if (!$r_query[$url])
				{
					$found[] = $url;
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
	}

	print "new ".$new.", old ".$old.", other ".$other."<br/>\n";
}

foreach($found as $row)
{
	print "<a href=\"".$base_dl_url.$row."\">".$row."</a><br/>\n";
}
?>