<?php

// Original code: The Wizard of DATz

$pages = array(
	"http://www.acornpreservation.org/main_tapes_games.html",
	"http://www.acornpreservation.org/main_discs_games.html",
);

foreach ($pages as $page)
{
	print "load ".$page."<br/>\n";

	$old = 0;
	$new = 0;
	$other = 0;

	$content = get_data($page);
	$content = explode('HREF="', $content);
	unset($content[0]);

	foreach ($content as $row)
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

	print "new ".$new.", old ".$old.", other ".$other."<br/>\n";
}

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='http://www.acornpreservation.org/".$row."'>".$row."</a><br/>\n";
}
?>