<?php

// Original code: The Wizard of DATz

// TODO: Change printout of wget statement to cURL download link

print "<pre>";

$sections = array(
	"number",
	"A",
	"B",
	"C",
	"D",
	"E",
	"F",
	"G",
	"H",
	"I",
	"J",
	"K",
	"L",
	"M",
	"N",
	"O",
	"P",
	"Q",
	"R",
	"S",
	"T",
	"U",
	"V",
	"W",
	"X",
	"Y",
	"Z",
);

$query = get_data("http://vimm.net/?p=vault");
$query = explode('?system=', $query);
array_splice($query, 0, 1);

foreach ($query as $system)
{
	$system = explode('"', $system);
	$system = $system[0];
	print "found ".$system."\n";
	foreach ($sections as $section)
	{
		$new = 0;
		$old = 0;
		$other = 0;
		print "load ".$section."\n";
		$ids = get_data("http://vimm.net/vault/?p=list&action=settings&section=".$section."&system=".$system."&v_us=1&v_hacked=1&v_prototype=1&v_foreign=1&v_translated=1&v_unlicensed=1");
		$ids = explode('"?p=details&amp;id=', $ids);
		array_splice($ids, 0, 1);
		foreach ($ids as $id)
		{
			$id = explode('"', $id);
			$id = $id[0];
			
			if (!$r_query[$id])
			{
				print "load ".$id."\n";
				$page = get_data("http://vimm.net/vault/?p=details&id=".$id);
				$page = explode('name="download"', $page);

				if ($page[1])
				{
					$found[] = array($id, $system, count($page) - 1);
					$new++;
				}
				else
				{
					$other++;
				}
			}
			else
			{
				$old++;
			}
		}
		print "new: ".$new.", old: ".$old.", other: ".$other."\n";
	}
}

print "<table><tr><td><pre>";
foreach ($found as $row)
{
	print $row[0]."\n";
}
print "</td></tr></table>";
print "<table><tr><td><pre>";
foreach ($found as $row)
{
	for ($y = 1; $y <= $row[2]; $y++)
	{
		print "wget.exe --post-data \"id=".$row[0]."&fileNumber=".$y."\" --user-agent \"Mozilla/5.0 (Windows NT 5.2; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0\"  --referer \"http://vimm.net/vault/?p=details&id=".$x."\" --output-document \"{".$row[1]."}".$row[0].".".$y.".7z\" http://download.vimm.net/download.php\n";
	}
}
print "</td></tr></table>";

?>