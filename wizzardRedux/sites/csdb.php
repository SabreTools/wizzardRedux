<?php

// Original code: The Wizard of DATz

// TODO: Find what start should be...

print "<pre>";

if($_GET["start"])
{
	$start = $_GET["start"];
}
else
{
	$start = 1;
}

print "\nSearch for new uploads\n\n";

while (true)
//for ($x = 0; $x < 100; $x++)
{
	$query = get_data("http://csdb.dk/navigate.php?type=release&action=prev&id=".$start);

	$temp = explode('/navigate.php?type=release&action=next&id=', $query);
	$temp = explode('"', $temp[1]);
	$temp = $temp[0];
	
	if ($temp)
	{
		$start = $temp;
		print "load: ".$start.", ";
	}
	else
	{
		print "<font color=red>load error!</font>\n";
		break;
	}

	$type = explode('<b>Type :</b><br>', $query);
	$type = explode('<br>', $type[1]);
	$type = trim(strip_tags($type[0]));

	$author = explode('<b>Released by :</b><br>', $query);
	$author = explode('<br>', $author[1]);
	$author = strip_tags($author[0]);

	$info = array();

	$temp = explode('<font size=6>', $query);
	$temp = explode('</font>', $temp[1]);
	$temp = strip_tags($temp[0]);

	if ($temp)
	{
		$info[] = $temp;
	}

	$temp = explode('<b>AKA :</b><br>', $query);
	$temp = explode('<br>', $temp[1]);
	$temp = strip_tags($temp[0]);

	if ($temp)
	{
		$info[] = $temp;
	}

	$temp = explode('<b>Release Date :</b><br>', $query);
	$temp = explode('<br>', $temp[1]);
	$temp = strip_tags($temp[0]);

	if ($temp)
	{
		$info[]=$temp;
	}

	$temp = explode('<b>Released At :</b><br>', $query);
	$temp = explode('</a>', $temp[1]);
	$temp = strip_tags($temp[0]);
	
	if ($temp)
	{
		$info[] = $temp;
	}

	$gametitle = "{".$type."}".$author." (".implode(") (",$info).")";
	$gametitle = str_replace("\n", "", $gametitle);
	$gametitle = str_replace(" [web]", "", $gametitle);

	$DLs = explode('<a href="download.php?id=', $query);
	array_splice($DLs, 0, 1);

	$new = 0;
	$old = 0;

	foreach ($DLs as $DL)
	{
        $DL = explode('">', $DL);
        $ID = $DL[0];
        $DL = explode("<", $DL[1]);
        $DL = $DL[0];

        if (!$r_query[$DL])
		{
			$found[] = array($start, $DL, $gametitle." [CSDB#".$ID."#".$type."]");
			$r_query[$DL] = true;
			$new++;
		}
		else
		{
			$old++;
		}
	}

	print "new: ".$new.", old: ".$old."\n";

	sleep(1);
}

print "<table>";
	
foreach ($found as $url)
{
	print "<tr>
	<td>".$url[0]."</td>
	<td><nobr>".$url[1]."</td>
	<td><a href=\"".str_replace('#','%23',$url[1])."\"><nobr>".$url[2]."</a></td>
</tr>";
}

print "</table>";

print "\nnext startnr\t<a href=?action=onlinecheck&source=csdb&start=".($start).">".$start."</a>\n\n";

?>