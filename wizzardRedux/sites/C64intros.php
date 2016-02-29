<?php

// Original code: The Wizard of DATz

print "<pre>";

print "Last fixes\n\n";

$query = get_data("http://intros.c64.org/frame.php");
$query = explode('<div class="menu_header">FIXED</div>', $query);
$query = explode('<a href="main.php?module=showintro&iid=', $query[1]);

$query[0] = null;

foreach ($query as $row)
{
	if ($row)
	{
		$id = explode('"', $row);
		$gametitle = explode('>', $row);
		$gametitle = explode('<', $gametitle[1]);
		$gametitle = explode(' ', $gametitle[0]);
		$gametitle[count($gametitle) - 1] = "(".$gametitle[count($gametitle) - 1].")";
		$gametitle = implode(' ', $gametitle);

		if (!$r_query[$id[0]])
		{
			print $id[0]."\t<a href=http://intros.c64.org/inc_download.php?iid=".$id[0].">".$gametitle.".prg</a>\n";
		}
	}
}

$r_query = array_flip($r_query);
$start = explode("=", $r_query[0]);
$start = $start[1];

print "\nSearch for new uploads\n\n";

$error = false;
$x = $start;
while(!$error)
{
	$query = get_data("http://intros.c64.org/main.php?module=showintro&iid=".$x);

	if ($query != "Database error. Please contact us if this problem persists." || strpos($query, "<a href=\"inc_download.php?iid=\"")) 
	{
		$gametitle = explode('<span class="introname">', $query);
		$gametitle = explode('</span>', $gametitle[1]);
		$gametitle = explode(' ', $gametitle[0]);
		$gametitle[count($gametitle) - 1] = "(".$gametitle[count($gametitle) - 1].")";
		$gametitle = implode(' ', $gametitle);

		print $x."\t<a href=http://intros.c64.org/inc_download.php?iid=".$x.">".$gametitle.".prg</a>\n";

		$last = $x;
	}
	elseif ($x == 9744)
	{
		$error = true;
	}
	else
	{
		print $x."\t".$query."\n";
		$error = true;
	}
	$x++;
}

if ($last)
{
	$start = $last + 1;
}

print "\nnext startnr\t".$start."<br/>\n";

?>