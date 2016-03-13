<?php

// Original code: The Wizard of DATz

$type = (isset($_GET["type"]) ? $_GET["type"] : "");

// Traverse the forums for new downloads
if ($type == "forum")
{
	$topics = array(
	//Atari Systems
		'16-atari-2600',								//	Atari 2600
			'50-atari-2600-programming', 				// 		Atari 2600 Programming 				//	139-atari-2600-programming
				'31-2600-programming-for-newbies', 		// 			2600 Programming For Newbies
				'65-batari-basic',						// 			batari Basic
			'59-2600-high-score-club',					//		2600 High Score Club
			'124-harmony-cartridge',					//		Harmony Cartridge
			'64-2600-game-descriptions',				//		2600 Game Descriptions
		'3-atari-5200',									//	Atari 5200
			'51-atari-5200-8-bit-programming',			// 		Atari 5200 / 8-bit Programming
			'88-5200-high-score-club',					//		5200 High Score Club
		'4-atari-7800',									//	Atari 7800
			'52-atari-7800-programming',				// 		Atari 7800 Programming
			'89-7800-high-score-club',					//		7800 High Score Club
		'13-atari-lynx',								//	Atari Lynx
			'53-atari-lynx-programming', 				//		Atari Lynx Programming
			'103-lynx-high-score-club',					//		Lynx High Score Club
		'14-atari-jaguar',								//	Atari Jaguar
			'54-atari-jaguar-programming', 				//		Atari Jaguar Programming
				'161-raptor-basic-and-raptor-basic', 	//			RAPTOR Basic and RAPTOR Basic+
				'160-raptor-api', 						//			RAPTOR API
			'98-jaguar-high-score-club',				//		Jaguar High Score Club
		'19-dedicated-systems',							//	Dedicated Systems
			'69-atari-flashback-consoles',				//		Atari Flashback Consoles
		'12-atari-8-bit-computers',						//	Atari 8-Bit Computers
			'51-atari-5200-8-bit-programming',			// 		Atari 5200 / 8-bit Programming
			'60-8-bit-high-score-club',					//		8-bit High Score Club
		'20-atari-sttt-computers',						//	Atari ST/TT Computers
	//Gaming General
		'17-classic-gaming-general',					//	Classic Gaming General
			'115-colecovision-adam',					//		ColecoVision / Adam
				'55-colecovision-programming',			//			ColecoVision Programming
				'96-colecovision-high-score-club',		//			ColecoVision High Score Club
				'117-opcode-games',						//			Opcode Games
			'125-intellivision-aquarius',				//		Intellivision / Aquarius
				'144-intellivision-programming',		//			Intellivision Programming
				'128-intellivision-high-score-club',	//			Intellivision High Score Club
			'109-x-port-emulation-development',			//		X-Port Emulation Development
			'92-nes-high-score-club',					//		NES High Score Club
			'93-sms-high-score-club',					//		SMS High Score Club
			'148-sega-genesis',							//		Sega Genesis
		'116-classic-computing',						//	Classic Computing
		'21-modern-gaming',								//	Modern Gaming
			'77-microsoft-xbox-360',					//		Microsoft Xbox 360
			'78-nintendo-wii',							//		Nintendo Wii
			'79-sony-playstation-3',					//		Sony Playstation 3
		'36-prototypes',								//	Prototypes
		'18-arcade-coin-ops',							//	Arcade Coin-ops
			'107-arcademame-high-score-club',			//	Arcade/MAME High Score Club
		'5-emulation',									//	Emulation
		'8-hardware',									//	Hardware
		'23-gaming-publications-and-websites',			//	Gaming Publications and Websites
			'101-2600-connection-newsletter',			//	2600 Connection Newsletter
		'9-international',								//	International
	//Community
		'85-high-score-clubs',							//	High Score Clubs
			'59-2600-high-score-club',					//		2600 High Score Club
			'88-5200-high-score-club',					//		5200 High Score Club
			'89-7800-high-score-club',					//		7800 High Score Club
			'98-jaguar-high-score-club',				//		Jaguar High Score Club
			'60-8-bit-high-score-club',					//		8-bit High Score Club
			'92-nes-high-score-club',					//		NES High Score Club
			'93-sms-high-score-club',					//		SMS High Score Club
			'96-colecovision-high-score-club',			//		ColecoVision High Score Club
			'128-intellivision-high-score-club',		//		Intellivision High Score Club
			'107-arcademame-high-score-club',			//	Arcade/MAME High Score Club
	//Game Programming
		'29-homebrew-discussion',						//	Homebrew Discussion
			'100-boulder-dash-development-blog',		//		Boulder Dash® Development Blog
		'11-programming',								// 	Programming
			'50-atari-2600-programming', 				// 		Atari 2600 Programming 				//	139-atari-2600-programming
				'31-2600-programming-for-newbies', 		// 			2600 Programming For Newbies
				'65-batari-basic',						// 			batari Basic
			'51-atari-5200-8-bit-programming',			// 		Atari 5200 / 8-bit Programming
			'52-atari-7800-programming',				// 		Atari 7800 Programming
			'53-atari-lynx-programming', 				//		Atari Lynx Programming
			'54-atari-jaguar-programming', 				//		Atari Jaguar Programming
				'161-raptor-basic-and-raptor-basic', 	//			RAPTOR Basic and RAPTOR Basic+
				'160-raptor-api', 						//			RAPTOR API
			'55-colecovision-programming',				//		ColecoVision Programming
			'144-intellivision-programming',			//		Intellivision Programming
			'119-ti-994a-programming',					//		TI-99/4A Programming
		'30-hacks',										// 	Hacks
			'66-atari-2600-hacks',						// 		Atari 2600 Hacks
			'67-atari-5200-hacks',						// 		Atari 5200 Hacks
			'68-atari-7800-hacks',						// 		Atari 7800 Hacks
	);

	$bad_ext = array(
		'asm',
		'bas',
		'bmp',
		'doc',
		'gif',
		'jpg',
		'pdf',
		'png',
		'rtf',
		'txt',
		'xls',
	);

	$max = 1000000;

	$newAttachIDs = array();
	$checked = array();

	error_reporting(E_ERROR | E_PARSE);

	echo "<table>\n";
	foreach ($topics as $topic)
	{
		// If the topic hasn't already been visited
		if (!$checked[$topic])
		{
			$checked[$topic] = true;
			for ($x = 1; $x < $max; $x++)
			{
				echo "<tr><td><b>".$topic." * ".$x."</b></td><td></td></tr>\n";
	
				$query = get_data("http://atariage.com/forums/forum/".$topic."/page-".$x."?prune_day=100&sort_by=Z-A&sort_key=last_post&topicfilter=all");
				
				// Figure out if there's a next page to go to
				$next = preg_match("/<link rel='next'/", $query);
				
				// Get the IDs of all pinned attachments so they aren't visited twice
				preg_match_all("/<span .+?>Pinned<\/span>.+?\s*<h4>\s*<a itemprop=\"url\" id=\"tid-link-(.+?)\"/", $query, $pinnedAttachments);
				$pinnedAttachments = $pinnedAttachments[1];
				$pinnedAttachments = array_fill_keys($pinnedAttachments, true);
				
				// Get the section name
				preg_match("/<h1 class='ipsType_pagetitle'>(.+?)<\/h1>/", $query, $section);
				$section = $section[1];

				// Get all topics on the current page
				preg_match_all("/\"tid-link-(.+?)\".*?<span itemprop=\"name\">(.*?)<\/span>.*?\"UserComments:(.+?)\"/s", $query, $query);
				$newrows = array();
				for ($index = 0; $index < sizeof($query[0]); $index++)
				{
					$newrows[] = array($query[1][$index], $query[2][$index], $query[3][$index]);
				}

				// For every topic, check for new attachments
				foreach ($newrows as $row)
				{
					$attachset = $row[0];
					$attachtitle = $row[1];
					$attachcount = $row[2];
					
					// If the page has the same number of comments still
					if ($r_query[$attachset.'*'.$attachcount])
					{
						// If it's a pinned topic, we want to check it anyway
						if ($pinnedAttachments[$attachset])
						{
							//print "skip pinned ".$attachset.'*'.$attachcount."\n";
							continue;
						}
						// Otherwise, stop processing
						else
						{
							//print "break by ".$attachset.'*'.$attachcount."\n";
							$x = $max;
							break;
						}
					}

					// Add the new page and comment value to the output set
					$newAttachIDs[] = $attachset.'*'.$attachcount;

					// For each page of attachments for a topic
					for ($y = 0; $y < 1000; $y++)
					{
						echo "<tr><td>".$attachset." * ".($y + 1)."</td>";
						$b_query = get_data("http://atariage.com/forums/index.php?app=forums&module=forums&section=attach&tid=".$attachset."&st=".($y * 50));						
						
						$new = 0;
						$old = 0;
						$reject = 0;
						
						// Figure out if there's a next page to go to
						$b_next = preg_match("/rel='next'>Next<\/a><\/li>/", $b_query);
						
						// Get information on all attachments from the page
						preg_match_all("/<tr class='.*?' id=\"(.+?)\">.+?title=\"(.+?)\".+?Posted on (.+?) \)/s", $b_query, $b_query);

						$newrowsb = array();
						for ($index = 0; $index < sizeof($query[0]); $index++)
						{
							if ($b_query[0][$index] !== NULL)
							{
								$newrowsb[] = array($b_query[1][$index], $b_query[2][$index], $b_query[3][$index]);
							}
						}
						
						// For each attachment that it finds
						foreach ($newrowsb as $dl)
						{
							$dl_id = $dl[0];

							// If the attachment isn't listed in the visited
							if (!$r_query[$dl_id.'#0'])
							{
								// Normalize the date and get the title
								$dl_date = date('Y.m.d H.i', strtotime($dl[2]));
								$dl_title = $dl[1];

								// Get the extension from the title
								$dl_ext = explode('.', $dl_title);
								$dl_ext = strtolower($dl_ext[count($dl_ext) - 1]);
								$dl_title = substr($dl_title, 0, -(strlen($dl_ext) + 1));
								
								// If the extension isn't a known bad one, add it to the list of found
								if (!in_array($dl_ext, $bad_ext))
								{
									$found[] = array($dl_id, "{".$section."}".$attachtitle." (".$dl_title.") (".$dl_date.").".$dl_ext);
									$newAttachIDs[] = $dl_id.'#0';
									$new++;
								}
								// Otherwise, reject it
								else
								{
									$reject++;
								}
							}
							// Otherwise, it's an old one
							else
							{
								$old++;
							}
						}
							
						echo "<td>Found new: ".$new.", old: ".$old.", reject: ".$reject."</tr>\n";

						// If there's no new page to go to, quit the loop
						if ($b_next !== 1)
						{
							break;
						}
					}
				}
				
				// If there's no new page to go to, quit the loop
				if ($next !== 1)
				{
					$x = $max;
					break;
				}
			}
		}
	}
	echo "</table>\n";
	
	if (sizeof($found) > 0)
	{
		echo "<h2>New files:</h2>";
	}
	
	foreach ($found as $row)
	{
		echo "<a href='http://atariage.com/forums/index.php?app=core&amp;module=attach&amp;section=attach&amp;attach_rel_module=post&amp;attach_id=".$row[0]."'>".$row[1]."</a><br/>\n";
	}
	
	echo "<br/>\n";

	print "new IDs:<br\>\n<table><tr><td><pre>";
	foreach ($newAttachIDs as $ID)
	{
		print $ID."\n";
	}
	print "</pre><br/>\n";
}
// Search through the games available direct through AtariAge
elseif ($type == "main")
{
	$systems = array(
		array('2600',	'Atari - 2600'),
		array('5200',	'Atari - 5200'),
		array('7800',	'Atari - 7800'),
		array('LYNX',	'Atari - Lynx'),
	);

	echo "<table>\n";
	foreach ($systems as $system)
	{
		$query2 = array();
		$count = 0;

		echo "<tr><td>".$system[1]."</td>";

		$query = get_data("http://www.atariage.com/software_list.html?SystemID=".$system[0]."&searchROM=checkbox&recordsPerPage=100000");
		
		preg_match_all("/atariage\.com\/software_page\.html\?SoftwareLabelID=(.+?)\"/", $query, $query);
		
		$new = 0;
		$old = 0;
		
		foreach ($query[1] as $id)
		{
			if (!$r_query[$id])
			{
				$query2[] = $id;
				$new++;
			}
			else
			{
				$old++;
			}
		}

		echo "<td>Found new: ".$new.", old: ".$old."</tr>\n";

		foreach ($query2 as $row)
		{
			$query = get_data("http://www.atariage.com/software_page.html?SoftwareLabelID=".$row);
			
			preg_match("/<span class=\"gametitle\">(.*?)<\/span>/", $query, $gametitle);
			$gametitle = $gametitle[1];
			
			preg_match("/<a href=\"http:\/\/atariage\.com\/common\/rarity_key\.php\" title=\"(.*?) - /", $query, $rarity);
			$rarity = $rarity[1];

			preg_match("/<a href=\"http:\/\/atariage\.com\/common\/region_key\.php\" title=\"(.*?) - /", $query, $region);
			$region = $region[1];

			preg_match("/<a href=\"http:\/\/atariage\.com\/common\/video_key.php\" title=\"(.*?) - /", $query, $video);
			$video = $video[1];
			
			preg_match("/<img src=\"http:\/\/atariage\.com\/images\/buttons\/ShotButton\.gif\"  width=\"18\" height=\"18\" \/><\/a>&nbsp;<a href=\"(.*?)\"/", $query, $url);
			$url = $url[1];
			
			preg_match("/<b>Company:<\/b>.*?<a.*?>(.*?)<\/a>/", $query, $company);
			$company = $company[1];
			
			preg_match("/<b>Developer:<\/b>.*?<a.*?>(.*?)<\/a>/", $query, $developer);
			$developer = $developer[1];
			
			preg_match("/<b>Model #:<\/b>\s*(.*?)\s*<\/td>/", $query, $model);
			$model = $model[1];
			
			preg_match("/<b>Year of Release:\s*<\/b>\s*(.*?)\s*<\/td>/", $query, $year);
			$year = $year[1];

			$name = $gametitle;

			$manufactor = array();
			if ($company !== NULL)
			{
				$manufactor[] = $company;
			}
			if ($developer !== NULL)
			{
				$manufactor[] = $developer;
			}
			if ($manufactor !== NULL)
			{
				$name = $name." (".implode(', ', $manufactor).")";
			}

			if ($year !== NULL && $year != 'n/a')
			{
				$name=$name." (".$year.")";
			}

			$location = array();
			if ($region !== NULL)
			{
				$location[] = $region;
			}
			if ($video !== NULL)
			{
				$location[] = $video;
			}
			if ($location !== NULL)
			{
				$name = $name." (".implode(', ', $location).")";
			}

			$properties = array();
			if (in_array($rarity, array('Homebrew', 'Reproduction', 'Prototype')))
			{
				$properties[] = $rarity;
			}
			if ($model !== NULL && $model != 'n/a')
			{
				$properties[] = $model;
			}
			if (sizeof($properties) > 0)
			{
				$name = $name." (".implode(', ', $properties).")";
			}
			
			$found[] = array($url, "{".$system[1]."}".$name.".zip");
		}
	}
	echo "</table>\n";
	
	if (sizeof($found) > 0)
	{
		echo "<h2>New files:</h2>";
	}
	
	foreach ($found as $row)
	{
		echo "<a href='http://atariage.com/forums/index.php?app=core&amp;module=attach&amp;section=attach&amp;attach_rel_module=post&amp;attach_id=".$row[0]."'>".$row[1]."</a><br/>\n";
	}
	
	echo "<br/>\n";
}
else
{
	print "<pre>";
	print "load <a href=?page=onlinecheck&source=AtariAge&type=main>main</a>\n";
	print "load <a href=?page=onlinecheck&source=AtariAge&type=forum>forum</a>\n";
	print "</pre>";
}

?>