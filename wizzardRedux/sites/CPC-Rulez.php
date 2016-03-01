<?php

// Original code: The Wizard of DATz

print "<pre>";

/*$r2_query=implode ('', file ($_GET["source"]."/pages.txt"));
$r2_query=explode ("\r\n","\r\n".$r2_query);
$r2_query=array_flip($r2_query);*/

$r2_query = array();

$Dirs = array(
	'GamesDL_arc',
	'GamesDL_avt',
	'GamesDL_div',
	'GamesDL_free',
	'GamesDL_GX4000',
	'GamesDL_hack',
	'GamesDL_tape',
	'GamesDL_uncrack',
	'Scene_Demos',
	'Scene_Demos0x',
	'Scene_Demos8x',
	'Scene_Demos9x',
	'Scene_Demosplus',
	'Scene_Discmags',
//	'Scene_MDL',
	'Scene_MusicDiscs',
	'Scene_Slideshows',
);

$Lists = array(
	'',
	'Yg',
	'Yw',
	'ZA',
	'ZQ',
	'Zg',
	'Zw',
	'aA',
	'aQ',
	'ag',
	'aw',
	'bA',
	'bQ',
	'bg',
	'bw',
	'cA',
	'cQ',
	'cg',
	'cw',
	'dA',
	'dQ',
	'dg',
	'dw',
	'eA',
	'eQ',
	'eg',
	'ew',
);

foreach ($Dirs as $Dir)
{
	foreach ($Lists as $List)
	{
		$page = 0;
		if ($page < 10) {
			$curDir = "http://cpcrulez.fr/".$Dir."/index.php?list=".$List; //."&p=".$page;
			print "load ".$curDir."\n";
			$query = get_data($curDir);
		
			$new = 0;
			$old = 0;
		
			$DLs = explode('<a href="/', $query);
		
			foreach ($DLs as $DL)
			{
				$DL = explode('/index.php?download=', $DL);
				if ($DL[1])
				{
					$DL_Dir = str_replace('http:CPCrulez.fr', '', str_replace('/', '', $DL[0]));
		
					$DL = explode('"', $DL[1]);
					$DL = explode('&', $DL[0]);
					$DL = $DL[0];
		
					if (!$r_query[$DL_Dir.'*'.$DL])
					{
						$found[] = array($DL_Dir, $DL);
						$r_query[$DL_Dir.'*'.$DL] = true;
						$new++;
					}
					else
					{
						$old++;
					}
				}
			}
		
			print "new: ".$new.", old: ".$old."\n";
		}
	}
}

$Dirs = array(
	'applications_menu_AUDIO',
	'applications_menu_BUREAU',
	'applications_menu_CODING',
	'applications_menu_COMM',
	'applications_menu_CPM',
	'applications_menu_CRACK',
	'applications_menu_DISK',
	'applications_menu_ECO',
	'applications_menu_GRAPHIC',
	'applications_menu_OTHER_PLATEFORME',
	'applications_menu_PAO',
	'applications_menu_RSX',
	'applications_menu_freeware-coding',
	'applications_menu_freeware-disc',
	'applications_menu_freeware-divers',
	'applications_menu_freeware-graphic',
	'applications_menu_freeware-music',
	'applications_menu_listings-bureau',
	'applications_menu_listings-coding',
	'applications_menu_listings-disc',
	'applications_menu_listings-divers',
	'applications_menu_listings-graphic',
	'applications_menu_listings-music',
	'applications_menu_listings-RSX',
	'demoscene_tests_tools',
	'demoscene_tests_party',
	'demoscene_tests_discmags',
	'demoscene_tests',
);

$DLPages = array();

foreach ($Dirs as $Dir)
{
	$new = 0;
	$old = 0;
	$curDir = "http://cpcrulez.fr/".$Dir.".htm";
	print "load ".$curDir."\n";
	$query = get_data($curDir);
	$query = str_replace('<a href="/', '<a href="', $query);
	$query = explode('<a href="', $query);
	foreach ($query as $row)
	{
		$row = explode('"', $row);
		$row = explode('.', $row[0]);
		if ($row[1] == 'htm')
		{
			if (!$r2_query[$row[0]])
			{
				$DLPages[] = $row[0];
				$r2_query[$row[0]] = true;
				$new++;
			}
			else
			{
				$old++;
			}
		}
    }
	print "DLP new: ".$new.", old: ".$old."\n";
}

foreach ($DLPages as $Dir)
{
	$curDir = "http://cpcrulez.fr/".$Dir.".htm";
	print "load ".$curDir."\n";
	$query = get_data($curDir);
	$query = str_replace('../', '', $query);

	$new = 0;
	$old = 0;

	$DLs = explode('<a href="', $query);
	array_splice($DLs, 0, 1);

	foreach ($DLs as $DL)
	{
		$DL = explode('"', $DL);
		$DL = explode('/index.php?download=', $DL[0]);
		if ($DL[1])
		{
			$DL_Dir = str_replace('http:CPCrulez.fr', '', str_replace('/', '', $DL[0]));
			$DL = explode('&', $DL[1]);
			$DL = $DL[0];

			if (!$r_query[$DL_Dir.'*'.$DL])
			{
				$found[] = array($DL_Dir, $DL);
				$r_query[$DL_Dir.'*'.$DL] = true;
				$new++;
			}
			else
			{
				$old++;
			}
   		}
	}

	print "new: ".$new.", old: ".$old."\n";
}

print "\nnew dl-pages:\n\n";

print "<table><tr><td><pre>";
foreach ($DLPages as $row)
{
	print $row."\n";
}
print "</td></tr></table>";

print "\nnew urls:\n\n";

print "<table><tr><td><pre>";
foreach ($found as $row)
{
	print "<a href=\"http://cpcrulez.fr/".$row[0]."/index.php?download=".$row[1]."\" target=_blank>".$row[0].'*'.$row[1]."</a>\n";
}
print "</td></tr></table>";
	
?>