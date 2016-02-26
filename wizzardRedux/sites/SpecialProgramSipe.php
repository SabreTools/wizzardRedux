<?php

// Original code: The Wizard of DATz

print "<pre>";

$r_query = array_flip($r_query);

for ($x = 0; $x < count($r_query); $x++)
{
	$r_query[$x] = explode("\t", $r_query[$x]);
	$r_query[$x] = $r_query[$x][0];
}

$r_query = array_flip($r_query);

$badext = array('jpg', 'html', 'css', 'ico', 'php', 'rss', '#');

$new = 0;
$old = 0;
$other = 0;

$dirs = array(
	array("http://specialprogramsipe.altervista.org/download.html", ""),
	array("http://specialprogramsipe.altervista.org/spectrum48k/download.html", "spectrum48k/"),
	array("http://specialprogramsipe.altervista.org/playmp3/index.html", "playmp3/"),
	array("http://specialprogramsipe.altervista.org/playmp3/index1.html", "playmp3/"),
	array("http://specialprogramsipe.altervista.org/mp3special.html", ""),
);
	
foreach ($dirs as $dir)
{
	print $dir[0]."\n";
	$query2 = implode('', file($dir[0]));
	$query2 = explode('href="', $query2);
	$query2[0] = null;

	foreach ($query2 as $url)
	{
		if ($url)
		{
			$url = explode('"', $url);

			$split = $url[0];
			$split = explode(':', $split);

			if ($split[0] != "http")
			{
				$url = $dir[1].$url[0];
			}
			else
			{
				$url = $url[0];
			}

			$ext = explode('.', $url);
			$ext = $ext[count($ext) - 1];
			$url = str_replace('&amp;', '&', $url);
	
			if (!in_array($ext, $badext))
			{
				if (!$r_query[$url])
				{
					$found[] = $url;
					$r_query[$url] = true;
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
	
	print "new: ".$new.", old: ".$old.", other: ".$other."\n";
}

$found4 = array();
$found3 = array();
$found2 = array();

for ($page = 1; $page < 93; $page++)
{
	$dir = "http://specialprogramsipe.altervista.org/nuovosito/commodore64.php?rivista=".$page;
	
	print "load ".$dir."\n";
	
	$new = 0;
	$old = 0;
	
	$query2 = implode('', file($dir));
	$title = explode("<h1 id='page_title'>", $query2);
	$title = explode("<", $title[1]);
	$title = $title[0];
	
	$games = explode('<ul class="elenco">', $query2);
	$games = explode('</ul>', $games[1]);
	$games = explode('</li>', $games[0]);
	
	foreach ($games as $game)
	{
		$name1 = explode('<div class="gioco_nome_originale">', $game);
		$name1 = explode("<", $name1[1]);
		$name1 = $name1[0];
		if ($name1)
		{
			$name2 = explode('<div class="gioco_nome">', $game);
			$name2 = explode("<", $name2[1]);
			$name2 = $name2[0];
		
			$filetitel = $title." (".$name1." ~ ".$name2.")";
	
			$mp3s = explode('javascript:play_tape', $game);
			$mp3s = explode("'", $mp3s[1]);
	
			if (!$r_query["nuovosito".$mp3s[3]])
			{
				$found2[] = array("nuovosito".$mp3s[3], $filetitel.".mp3");
				$r_query["nuovosito".$mp3s[3]] = true;
				$new++;
			}
			else
			{
				$old++;
			}
	
			if (!$r_query["nuovosito".$mp3s[5]])
			{
				$found2[] = array("nuovosito".$mp3s[5], $filetitel." (Original).mp3");
				$r_query["nuovosito".$mp3s[5]] = true;
				$new++;
			}
			else
			{
				$old++;
			}
			
			$prgs = explode("a href='download_file.php?", $game);
			$prgs[1] = explode("'", $prgs[1]);
			$prgs[1] = $prgs[1][0];
			
			if (!$r_query["nuovosito/download_file.php?".$prgs[1]])
			{
				$found2[] = array("nuovosito/download_file.php?".$prgs[1], $filetitel.".prg");
				$r_query["nuovosito/download_file.php?".$prgs[1]] = true;
				$new++;
			}
			else
			{
				$old++;
			}
	
			$prgs[2] = explode("'", $prgs[2]);
			$prgs[2] = $prgs[2][0];
	
			if (!$r_query["nuovosito/download_file.php?".$prgs[2]])
			{
				$found2[] = array("nuovosito/download_file.php?".$prgs[2], $filetitel." (Original).prg");
				$r_query["nuovosito/download_file.php?".$prgs[2]] = true;
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}
	
	$types=array('tap','tap_divisi','prg','originali','originali_prg');
	
	foreach($types as $type){
	  		if(!$r_query["nuovosito/riviste/special_program/c64/program/".$type."_".$page.".zip"])
		{
			$found2[]=array("nuovosito/riviste/special_program/c64/program/".$type."_".$page.".zip",$title." (".$type.").zip");
			$r_query["nuovosito/riviste/special_program/c64/program/".$type."_".$page.".zip"]=true;
			$new++;
		}
		else
		{
			$old++;
		}
	}
	
	print "new: ".$new.", old: ".$old."\n";
}

for ($page = 1; $page < 93; $page++)
{
	$dir = "http://specialprogramsipe.altervista.org/nuovosito/spectrum.php?rivista=".$page;
	
	print "load ".$dir."\n";
	
	$new = 0;
	$old = 0;
	
	$query2 = implode('', file($dir));
	$title = explode("<h1 id='page_title'>", $query2);
	$title = explode("<", $title[1]);
	$title = $title[0];

	$games = explode('<ul class="elenco">', $query2);
	$games = explode('</ul>', $games[1]);
	$games = explode('</li>', $games[0]);
	
	foreach ($games as $game)
	{
		$name1 = explode('<div class="gioco_nome"><span>', $game);
		$name1 = explode("<", $name1[1]);
		$name1 = $name1[0];

		if ($name1)
		{
			$name2 = explode('<div class="gioco_nome">', $game);
			$name2 = explode("<", $name2[1]);
			$name2 = $name2[0];
		
			$filetitel = $title." (".$name1.")";

			$prgs = explode("a href='download_file.php?", $game);
			$prgs = explode("'", $prgs[1]);
			$prgs = $prgs[0];
			
			if (!$r_query["nuovosito/download_file.php?".$prgs])
			{
				$found3[] = array("nuovosito/download_file.php?".$prgs, $filetitel.".z80");
				$r_query["nuovosito/download_file.php?".$prgs]=true;
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}

	$types = array('tzx', 'z80');
	
	foreach ($types as $type)
	{
	  	if (!$r_query["nuovosito/riviste/special_program/spectrum/program/".$type."_".$page.".zip"])
		{
			$found3[] = array("nuovosito/riviste/special_program/spectrum/program/".$type."_".$page.".zip", $title." (".$type.").zip");
			$r_query["nuovosito/riviste/special_program/spectrum/program/".$type."_".$page.".zip"] = true;
			$new++;
		}
		else
		{
			$old++;
		}
	}

	print "new: ".$new.", old: ".$old."\n";
}

for ($page = 1; $page < 42; $page++)
{
	$dir="http://specialprogramsipe.altervista.org/nuovosito/commodore16.php?rivista=".$page;
	
	print "load ".$dir."\n";
	
	$new = 0;
	$old = 0;
	
	$query2 = implode('', file($dir));
	$title = explode("<h1 id='page_title'>", $query2);
	$title = explode("<", $title[1]);
	$title = $title[0];

	$games = explode('<ul class="elenco">', $query2);
	$games = explode('</ul>', $games[1]);
	$games = explode('</li>', $games[0]);
	
	foreach ($games as $game)
	{		
		$name1 = explode('<div class="gioco_nome">', $game);
		$name1 = explode("<", $name1[1]);
		$name1 = $name1[0];

		if ($name1)
		{
			$name2 = explode('<div class="gioco_nome">', $game);
			$name2 = explode("<", $name2[1]);
			$name2 = $name2[0];
		
			$filetitel = $title." (".$name1.")";

			$prgs = explode("a href='download_file.php?", $game);
			$prgs = explode("'", $prgs[1]);
			$prgs = $prgs[0];
			
			if (!$r_query["nuovosito/download_file.php?".$prgs])
			{
				$found4[] = array("nuovosito/download_file.php?".$prgs, $filetitel.".tap");
				$r_query["nuovosito/download_file.php?".$prgs] = true;
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}
	
	if ($games[1])
	{
		$types = array('tap', 'tap_divisi');
		
		foreach ($types as $type)
		{
		  	if (!$r_query["nuovosito/riviste/c16_msx/c16/program/".$type."_".$page.".zip"])
			{
				$found4[] = array("nuovosito/riviste/c16_msx/c16/program/".$type."_".$page.".zip", $title." (".$type.").zip");
				$r_query["nuovosito/riviste/c16_msx/c16/program/".$type."_".$page.".zip"] = true;
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

print "\n\nc16:\n\n";

foreach ($found4 as $url)
{
	print $url[0]."\t".$url[1]."\n";
}

print "\n\nzx:\n\n";

foreach ($found3 as $url)
{
	print $url[0]."\t".$url[1]."\n";
}

print "\n\nc64:\n\n";

foreach ($found2 as $url)
{
	print $url[0]."\t".$url[1]."\n";
}

print "\nnew urls:\n\n";

sort($found);

foreach ($found as $url)
{
	print $url."\n";
}

print "\n\n<a href=SpecialProgramSipe/xml.php>xml</a>";
?>