<?php

// Original code: The Wizard of DATz

print "<pre>";

$pages = array(
	"http://symlink.dk/nostalgia/dtv/fixed/?page=all",
	"http://symlink.dk/nostalgia/dtv/humjoy/"
);

$old = 0;
$new = 0;

foreach ($pages as $page)
{
	print "load ".$page."\n";

	$content = implode('', file($page));
	$content = explode('<tr><td><a href="?action=info&amp;id=', $content);
	$content[0] = null;

	foreach ($content as $row)
	{
		if ($row)
		{
			$id = explode('"', $row);
			$id = $id[0];

			$text = explode('>', $row);

			$title = explode('<', $text[1]);
			$title = $title[0];

			$patched = explode('<', $text[4]);
			$patched = $patched[0];

			$version = explode('<', $text[6]);
			$version = $version[0];

			$title = $title." (patched by ".$patched.") (version ".$version.").zip";

			if (!$r_query[$title])
			{
				$found[] = array($id, $title);
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}
}
	
print "new ".$new.", old ".$old."\n";

foreach ($found as $row)
{
	print "<a href=\"http://symlink.dk/nostalgia/dtv/fixed/?action=getdtv&id=".$row[0]."\" target=_blank>".$row[1]."</a>\n";
}

?>