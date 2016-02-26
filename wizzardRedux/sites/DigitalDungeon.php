<?php

// Original code: The Wizard of DATz

if ($_GET["type"] == 'rename')
{
	print "<pre><form method=\"post\" action=\"?action=onlinecheck&source=".$_GET["source"]."&type=rename\">
<textarea name=lines cols=\"50\" rows=\"10\">".stripslashes ($GLOBALS[HTTP_POST_VARS][lines])."</textarea>
<input type=submit></form><table><tr><td><pre>";

	if ($GLOBALS[HTTP_POST_VARS][lines])
	{
		$lines = explode("\r\n", "\r\n".stripslashes($GLOBALS[HTTP_POST_VARS][lines]));
		$lines = array_flip($lines);

		//print_r($lines);

		$r_query = array_flip($r_query);
		foreach($r_query as $row)
		{
			$row = explode("/", $row);

			$part1 = $row[count($row) - 2];
			$part2 = $row[count($row) - 1];
			$ext = explode(".", $part2);
			$ext = $ext[count($ext) - 1];
			$part2 = substr($part2, 0, -(strlen($ext) + 1));

			if ($lines[$part2.".".$ext])
			{
				print "rename \"".$part2.".".$ext."\" \"".str_replace('_', ' ', $part1." (".$part2.").".$ext)."\"\n";
			}
			if ($lines[$part2])
			{
				print "rename \"".$part2."\" \"".str_replace('_', ' ', $part1." (".$part2.")")."\"\n";
			}
        }
	}

	print "</td></tr></table>";

}
elseif ($_GET["type"] == 'search')
{
	print "<pre>";

	$newfiles = array(
		'ftp://ftp.scs-trc.net/pub/c64/ALL-FILES.TXT',
	);

	foreach ($newfiles as $newfile)
	{
		print "load ".$newfile."\n";
		$query = implode('', file($newfile));
 		$query = explode("\n", $query);

		$old = 0;
		$new = 0;

		foreach ($query as $row)
		{
    		if ($r_query[$row])
			{
				$old++;
			}
			else
			{
				$found[] = $row;
				$new++;
			}
		}

		print $found[0]."\nC64<table><tr><td><pre>";

		$found[0] = null;

		foreach ($found as $row)
		{
			if ($row && !substr_count(strtolower($row), '/dtv/'))
			{
				print $row."\n";
			}
		}

		print "</td><td><pre>";

		foreach ($found as $row)
		{
			if ($row && !substr_count(strtolower($row), '/dtv/'))
			{
				$row2 = explode("/", $row);

				print "<a href=\"ftp://ftp.scs-trc.net".str_replace('#', '%23', $row)."\">".str_replace('_', ' ', $row2[count($row2) - 1])."</a>\n";
			}
		}

		print "</td><td><pre>";

		foreach ($found as $row)
		{
			if ($row && !substr_count(strtolower($row), '/dtv/'))
			{
				$row2 = explode("/", $row);
				$title = explode(".", $row2[count($row2) - 1]);
				$ext = $title[count($title) - 1];
				$title[count($title) - 1] = null;
				$title = implode(".", $title);
	
				print "<a href=\"ftp://ftp.scs-trc.net".str_replace('#', '%23', $row)."\">".str_replace('_', ' ', $row2[count($row2)-2]." (".substr($title, 0, -1)).").".$ext."</a>\n";
			}
		}
	
		print "</td></tr></table>C64 DTV<table><tr><td><pre>";
	
		$found[0] = null;
	
		foreach ($found as $row)
		{
			if ($row && substr_count(strtolower($row), '/dtv/'))
			{
				print $row."\n";
			}
		}
	
		print "</td><td><pre>";
	
		foreach ($found as $row)
		{
			if ($row && substr_count(strtolower($row), '/dtv/'))
			{
				$row2 = explode("/", $row);
	
				print "<a href=\"ftp://ftp.scs-trc.net".str_replace('#', '%23', $row)."\">".str_replace('_', ' ', $row2[count($row2) - 1])."</a>\n";
			}
		}
	
		print "</td><td><pre>";
	
		foreach ($found as $row)
		{
			if ($row && substr_count(strtolower($row), '/dtv/'))
			{
				$row2 = explode("/", $row);
				$title = explode(".", $row2[count($row2) - 1]);
				$ext = $title[count($title) - 1];
				$title[count($title) - 1] = null;
				$title = implode(".", $title);
	
				print "<a href=\"ftp://ftp.scs-trc.net".str_replace('#', '%23', $row)."\">".str_replace('_', ' ', $row2[count($row2) - 2]." (".substr($title, 0, -1)).").".$ext."</a>\n";
			}
		}
	
		print "</td></tr></table>";
	
		print "found new:".$new.", old:".$old."\n\n";
	}

}
else
{
	print "<pre>";
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=search>search</a>\n";
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=rename>rename</a>\n";

}

?>